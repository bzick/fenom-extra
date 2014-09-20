/**
 * Feedback reporter
 *
 * megagroup.ru (c) 20014
 */
(function (w) {

    "use strict";

    function log() {
        if(w.console.log) {
            w.console.log.apply(w, arguments);
        }
    }

    var Feedback = w.Feedback = {

        layout           : false,
        enabled          : false,
        canvas           : false,
        selection        : false,
        img              : false,
        events_set       : false,
        popup            : false,
        xhr              : false,
        initialPositionX : 0,
        initialPositionY : 0,
        selection_inc    : 0,
        tabs             : {},
        selections       : {},
        selections_count : 0,
        scroll_top       : -1,
        data : {
            comment          : "",
            info             : false,
            screenshot       : false,
            scroll           : 0,
            selections       : {}
        },

        setup : function (factory) {
            this.factory = factory;
            return this;
        },

        opt : function (name) {
            return this.factory.options[name];
        },

        txt : function (code) {
            if(this.factory.texts[code]) {
                return this.factory.texts[code]
            } else {
                return code;
            }
        },

        render : function () {
            var self = this;

            if (self.layout) return;
            this.data.scroll = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
            html2canvas(document.body, {"onrendered" : function (canvas) {
                self.start(canvas);
            }});
        },

        start : function (canvas) {

            var self = this;
            document.body.scrollTop = this.data.scroll;
            if (!this.events_set) {  // for multiple feedback reports
                this.factory.addEvent(document.body, "mousedown", function (e) {
                    self.drawSelection(e);
                });
                this.factory.addEvent(document.body, "mousemove", function (e) {
                    self.resizeSelection(e);
                });
                this.factory.addEvent(document.body, "mouseup", function (e) {
                    self.stopSelection(e);
                });
                this.events_set = true;
            }

            this.canvas = canvas;

            this.layout = document.createElement('div');
            this.layout.id = "feedback-layout";
            this.layout.style.width = canvas.width + "px";
            this.layout.style.height = canvas.height + "px";

            this.popup = this.createPopup();
            this.factory.addEvent(this.popup, "mousedown", function (e) {
                e.stopPropagation();
            });

            document.body.appendChild(this.layout);
            this.layout.appendChild(this.popup);
            this.layout.appendChild(canvas);
            this.setPopupMode('comment');
        },

        stop : function () {
            if (this.layout) {
                this.layout.parentNode.removeChild(this.layout);
                this.layout = this.canvas = this.selection = this.img = this.popup = false;
                this.data.screenshot = this.data.info = false;
                this.selections_count = 0;
                this.data.comment = "";
                this.selections = {};
                this.tabs = {};
            }
        },

        enableSelection : function () {
            this.enabled = true;
            this.layout.className = "feedback-active";
            return this;
        },

        disableSelection : function () {
            this.enabled = false;
            this.layout.className = "";
            return this;
        },

        drawSelection : function (e) {

            if (!this.enabled) {
                return;
            }
            e.preventDefault();

            var areaSelection = document.createElement('div');
            var areaClose = document.createElement('a');

            this.img = new Image();

            areaSelection.className  = "area-selection";
            areaSelection.style.left = parseInt(e.clientX) + "px";
            areaSelection.style.top  = parseInt(e.clientY) + "px";
            areaSelection.id         = "feedback-selection-" + (++this.selection_inc);

            areaClose.className = "area-selection-close";
            areaClose.href      = "javascript: Feedback.deleteSelection(" + this.selection_inc + ")";

            this.initialPositionX = parseInt(e.clientX + Math.max(document.documentElement.scrollLeft, document.body.scrollLeft));
            this.initialPositionY = parseInt(e.clientY + Math.max(document.documentElement.scrollTop, document.body.scrollTop));

            this.img.style.width  = this.canvas.width;
            this.img.style.height = this.canvas.height;
            this.img.src          = this.canvas.toDataURL();

            this.layout.className = "feedback-active-selection";

            this.selection = areaSelection;
            this.layout.appendChild(areaSelection);
            areaSelection.appendChild(this.img);
            areaSelection.appendChild(areaClose);
            this.selections[this.selection_inc] = areaSelection;

            // cancel click by selection
            this.factory.addEvent(areaSelection, "mousedown", function (e) {
                e.stopPropagation();
                return false;
            });
        },

        resizeSelection : function (e) {
            if (!this.selection || !this.enabled) {
                return;
            }
            e.preventDefault();
            var offsetX   = e.clientX + Math.max(document.documentElement.scrollLeft, document.body.scrollLeft),
                offsetY   = e.clientY + Math.max(document.documentElement.scrollTop, document.body.scrollTop),
                docWidth  = Math.max(document.body.clientWidth, document.documentElement.clientWidth),
                docHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);

            if (offsetX >= this.initialPositionX) {
                this.img.style.left = -this.initialPositionX + "px";
                this.selection.style.left = this.initialPositionX + "px";
                this.selection.style.right = (docWidth - offsetX) + "px";
                this.selection.style.width = (offsetX - this.initialPositionX) + "px";
            } else if (offsetX < this.initialPositionX) {
                this.img.style.left = -offsetX + "px";
                this.img.style.right = (docWidth - this.initialPositionX) + "px";
                this.selection.style.width = (this.initialPositionX - offsetX) + "px";
                this.selection.style.left = offsetX + "px";
            }
            if (offsetY >= this.initialPositionY) {
                this.img.style.top = -this.initialPositionY + "px";
                this.selection.style.top = this.initialPositionY + "px";
                this.selection.style.bottom = (docHeight - offsetY) + "px";
                this.selection.style.height = (offsetY - this.initialPositionY) + "px";
            } else if (offsetY < this.initialPositionY) {
                this.img.style.top = -offsetY + "px";
                this.img.style.bottom = (docHeight - this.initialPositionY) + "px";
                this.selection.style.height = (this.initialPositionY - offsetY) + "px";
                this.selection.style.top = offsetY + "px";
            }
        },

        stopSelection : function (e) {
            if (!this.selection || !this.enabled) {
                return;
            }
            this.layout.className = "feedback-active";
            // remove small-size selection
            if (parseInt(this.selection.style.width) < 10 || parseInt(this.selection.style.height) < 10) {
                this.selection.parentNode.removeChild(this.selection);
                delete this.selections[this.selection_inc];
            } else {
                this.selection.className += " area-selection-finished";
            }
            this.selections_count++;
            this.selection = false;
            this.initialPositionY = 0;
            this.initialPositionY = 0;
        },

        deleteSelection : function (selection_id) {
            if (this.selections[selection_id]) {
                this.selections_count--;
                var selectionElem = this.selections[selection_id];
                selectionElem.parentNode.removeChild(selectionElem);
                delete this.selections[selection_id];
            }
        },

        createPopup : function () {
            var popup = document.createElement('div');
            popup.id = "feedback-popup";
            // comment
            var comment_tab = document.createElement('div');
            comment_tab.className = "feedback-tab feedback-tab-comment";
            this.tabs["comment"] = comment_tab;
            popup.appendChild(comment_tab);
            // selection
            var selection_tab = document.createElement('div');
            selection_tab.className = "feedback-tab feedback-tab-selection";
            this.tabs["selection"] = selection_tab;
            popup.appendChild(selection_tab);
            // report
            var report_tab = document.createElement('div');
            report_tab.className = "feedback-tab feedback-tab-report";
            this.tabs["report"] = report_tab;
            popup.appendChild(report_tab);
            // sending
            var sending_tab = document.createElement('div');
            sending_tab.className = "feedback-tab feedback-tab-sending";
            this.tabs["sending"] = sending_tab;
            popup.appendChild(sending_tab);

            return popup;
        },

        getScreenShot : function () {
            if (!this.layout) {
                throw "Screenshot not inited";
            }
            var layout      = document.createElement('canvas');
            var layout_ctx  = layout.getContext('2d');
            var canvas      = document.createElement('canvas');
            var canvas_ctx  = canvas.getContext('2d');
            var shadow      = document.createElement('canvas');
            var shadow_ctx  = shadow.getContext('2d');
            var proto       = this.canvas.getContext('2d');

            layout.width  = this.canvas.width;
            layout.height = this.canvas.height;
            canvas.width  = this.canvas.width;
            canvas.height = this.canvas.height;
            shadow.width  = this.canvas.width;
            shadow.height = this.canvas.height;

            layout_ctx.drawImage(this.canvas, 0, 0);
            if(this.selections_count == 0) {
                return layout;
            }

            var min_top = this.canvas.height;

            for (var k in this.selections) {
                if (!this.selections.hasOwnProperty(k)) {
                    continue;
                }
                var selection = this.selections[k];
                this.data.selections[k] = {
                    left   : parseInt(selection.style.left),
                    top    : parseInt(selection.style.top),
                    width  : parseInt(selection.style.width),
                    height : parseInt(selection.style.height)
                };
                if(this.data.selections[k].top < min_top) {
                    min_top = this.data.selections[k].top;
                }
                var fragment = proto.getImageData(
                    parseInt(selection.style.left),
                    parseInt(selection.style.top),
                    parseInt(selection.style.width),
                    parseInt(selection.style.height)
                );
                shadow_ctx.lineWidth = 1;
                shadow_ctx.shadowBlur = 20;
                shadow_ctx.shadowColor = "black";
                shadow_ctx.fillStyle = this.opt('stroke_color');
                shadow_ctx.fillRect(
                    parseInt(selection.style.left) - 1 ,
                    parseInt(selection.style.top) - 1,
                    parseInt(selection.style.width) + 2,
                    parseInt(selection.style.height) + 2
                );
                shadow_ctx.stroke();
                canvas_ctx.putImageData(fragment, parseInt(selection.style.left), parseInt(selection.style.top));
            }

            if(min_top != this.canvas.height) {
                this.scroll_top = min_top;
            } else {
                this.scroll_top = 0;
            }

            shadow_ctx.drawImage(canvas, 0, 0);
            layout_ctx.fillStyle = "rgba(0, 0, 0, 0.4)";
            layout_ctx.fillRect(
                parseInt(0),
                parseInt(0),
                parseInt(layout.width),
                parseInt(layout.height)
            );
            layout_ctx.drawImage(shadow, 0, 0);
            return layout;
        },

        getInfo : function () {
            function _browser () {
                var ua = window.navigator.userAgent, tem,
                    M = ua.toLowerCase().match(/(opera|chrome|safari|firefox|msie)\/?\s*([\d\.]+)/i) || [];

                if (M[1] == "msie") {
                    return ['Internet Explorer', ua.match(/msie\s+(\d+.\d)/i)[1]];
                }

                M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];

                if ((tem = ua.match(/version\/([\.\d]+)/i)) != null) {
                    M[2] = tem[1];
                }

                return M;
            }
            function _pluginsList () {
                var pluginsList = [];
                for(var i = 0; i < navigator.plugins.length; i++) {
                    var pluginItem = navigator.plugins[i];
                    var pluginDscr = pluginItem.name+" "+(pluginItem.version || '');
                    if (!pluginItem) {
                        continue;
                    }
                    pluginsList.push(pluginDscr);
                }
                return pluginsList;
            }
            function _getOS () {
                var osInfo        = [],
                    appVersion    = navigator.appVersion,
                    userAgent     = navigator.userAgent,
                    os            = "",
                    osVersion     = "",
                    clientStrings = [
                        {s:'Windows 3.11', r:/Win16/},
                        {s:'Windows 95', r:/(Windows 95|Win95|Windows_95)/},
                        {s:'Windows ME', r:/(Win 9x 4.90|Windows ME)/},
                        {s:'Windows 98', r:/(Windows 98|Win98)/},
                        {s:'Windows CE', r:/Windows CE/},
                        {s:'Windows 2000', r:/(Windows NT 5.0|Windows 2000)/},
                        {s:'Windows XP', r:/(Windows NT 5.1|Windows XP)/},
                        {s:'Windows Server 2003', r:/Windows NT 5.2/},
                        {s:'Windows Vista', r:/Windows NT 6.0/},
                        {s:'Windows 7', r:/(Windows 7|Windows NT 6.1)/},
                        {s:'Windows 8.1', r:/(Windows 8.1|Windows NT 6.3)/},
                        {s:'Windows 8', r:/(Windows 8|Windows NT 6.2)/},
                        {s:'Windows NT 4.0', r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
                        {s:'Windows ME', r:/Windows ME/},
                        {s:'Android', r:/Android/},
                        {s:'Open BSD', r:/OpenBSD/},
                        {s:'Sun OS', r:/SunOS/},
                        {s:'Linux', r:/(Linux|X11)/},
                        {s:'iOS', r:/(iPhone|iPad|iPod)/},
                        {s:'Mac OS X', r:/Mac OS X/},
                        {s:'Mac OS', r:/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
                        {s:'QNX', r:/QNX/},
                        {s:'UNIX', r:/UNIX/},
                        {s:'BeOS', r:/BeOS/},
                        {s:'OS/2', r:/OS\/2/},
                        {s:'Search Bot', r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
                    ];

                for (var id in clientStrings) {
                    var cs = clientStrings[id];
                    if (cs.r.test(userAgent)) {
                        os = cs.s;
                        break;
                    }
                }

                if (/Windows/.test(os)) {
                    osVersion = /Windows (.*)/.exec(os)[1];
                    os = 'Windows';
                }

                osInfo.push(os);

                switch (os) {
                    case 'Mac OS X':
                        osVersion = /Mac OS X (10[\.\_\d]+)/.exec(userAgent)[1];
                        osVersion = osVersion.replace(/_/g, '.');
                        break;

                    case 'Android':
                        osVersion = /Android ([\.\_\d]+)/.exec(userAgent)[1];
                        break;

                    case 'iOS':
                        osVersion = /OS (\d+).(\d+).?(\d+)?/.exec(appVersion);
                        osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                        break;

                }
                osInfo.push(osVersion);

                return osInfo;
            }

            var browserInfo = _browser(),
                osInfo      = _getOS();

            return {
                os             : {
                    name       : osInfo[0],
                    version    : osInfo[1],
                    platform   : navigator.platform
                },
                browser        : {
                    name       : browserInfo[0],
                    version    : browserInfo[1],
                    plugins    : _pluginsList(),
                    extensions : []
                },
                resolution     : {
                    width       : window.screen.width,
                    height      : window.screen.height,
                    screen_type : (window.devicePixelRatio == 2) ? "retina" : "regular"
                },
                url: {
                    uri        : window.location.pathname +  window.location.search + window.location.hash,
                    host       : window.location.host,
                    protocol   : window.location.protocol
                }
            };
        },

        /**
         * mode: comment, selection, report, sending
         */
        setPopupMode : function (mode) {
            if (!this.popup) {
                return this;
            }
            this.popup.className = "feedback-popup-" + mode;
            var callback = mode.charAt(0).toUpperCase() + mode.slice(1);
            if (this["_mode" + callback]) { // invoke callback
                this["_mode" + callback].call(this, this.tabs[mode]);
            } else {
                throw "No collback found for tab " + mode;
            }
            return this;
        },

        validateComment: function (tab, textarea_id) {
            if(!document.getElementById(textarea_id).value.replace(/^\s+|\s+$/g, '')) {
                document.querySelector(".feedback-tab-"+tab+" .feedback-tab-error").style.display = "inline";
                return false;
            } else {
                document.querySelector(".feedback-tab-"+tab+" .feedback-tab-error").style.display = "none";
                this.data.comment = document.getElementById(textarea_id).value;
                return true;
            }
        },

        _modeComment : function (tab) {

            this.disableSelection();
            tab.innerHTML =
                "<div class='popup-close' onclick='Feedback.stop();'></div>"+
                    "<div class='popup-content'>" + this.txt('enter_comment') + "</div>"+
                    "<div class='feedback-tab-message'>"+
                    "<textarea id='feedback-comment'></textarea>"+
                    "</div>"+
                    "<div class='feedback-tab-controls'>"+
                    "<span class='feedback-tab-error'>" + this.txt('error_comment') + "</span>"+
                    "<button onclick='Feedback.stop();'>" + this.txt('button_cancel') + "</button>"+
                    "<button class='feedback-btn-next' onclick='return Feedback.validateComment(\"comment\", \"feedback-comment\") && Feedback.setPopupMode(\"selection\");'>" + this.txt('button_next') + "</button>"+
                    "</div>";
            document.getElementById('feedback-comment').value = this.data.comment;
            document.getElementById('feedback-comment').focus();
        },

        _modeSelection : function (tab) {
            this.enableSelection();
            tab.innerHTML =
                "<div class='popup-close' onclick='Feedback.stop();'></div>"+
                    "<div class='popup-content'>"+this.txt('select_problem') +"</div>"+
                    "<div class='feedback-tab-controls'>" +
                    "<button onclick='Feedback.setPopupMode(\"comment\");'>" + this.txt('button_back') + "</button>" +
                    "<button class='feedback-btn-next' onclick='Feedback.setPopupMode(\"report\");'>" + this.txt('button_next') + "</button>" +
                    "</div>";
        },

        _modeReport : function (tab) {
            this.disableSelection();
            var info = this.getInfo();
            this.data.info = info;
            this.data.screenshot = this.getScreenShot();

            tab.innerHTML =
                "<div class='popup-close' onclick='Feedback.stop();'></div>"+
                    "<div>" + this.txt('report') + "</div>" +
                    "<div class='feedback-report-system'>" +
                    "<ul onclick='Feedback.listControl(this);'><li><span>"+this.txt('field_page')+"</span><ul><li>" + info.url.protocol + "//" + info.url.host + info.url.uri + "</li></ul></li></ul>" +
                    "<ul onclick='Feedback.listControl(this);'><li><span>"+this.txt('field_os')+"</span><ul><li>" + info.os.name + " " + info.os.version + " (" + info.os.platform + ")</li></ul></li></ul>" +
                    "<ul onclick='Feedback.listControl(this);'><li><span>"+this.txt('field_browser')+"</span><ul><li>" + info.browser.name + " " + info.browser.version + "</li></ul></li></ul>" +
                    "<ul onclick='Feedback.listControl(this);'><li><span>"+this.txt('field_plugins')+"</span><ul><li>" + info.browser.plugins.join("</li><li>") + "</li></ul></li></ul>" +
                    "<ul onclick='Feedback.listControl(this);'><li><span>"+this.txt('field_screen')+"</span><ul>" +
                    "<li>" + this.txt('field_screen_resolution') + ': ' + info.resolution.width + "x" + info.resolution.height +"</li>" +
                    "<li>" + this.txt('field_screen_type') + ': ' + info.resolution.screen_type + "</li>" +
                    "</ul></li>" +
                    "</ul>" +
                    "</div>" +
                    "<div class='feedback-report-user'>" +
                    "<dt>"+this.txt('field_screenshot') + "</dt><dd class='feedback-report-image'><img id='feedback-field-screen' src='http://placehold.it/350x150' /></dd>" +
                    "<dt>"+this.txt('field_comment')+"</dt><textarea id='feedback-field-comment'></textarea>"+
                    "</div>" +
                    "<div class='feedback-tab-controls'>" +
                    "<span class='feedback-tab-error'>" + this.txt('error_comment') + "</span>" +
                    "<button onclick='return Feedback.validateComment(\"report\", \"feedback-field-comment\") && Feedback.setPopupMode(\"selection\")'>" + this.txt('button_back') + "</button>" +
                    "<button class='feedback-btn-next' onclick='return Feedback.validateComment(\"report\", \"feedback-field-comment\") && Feedback.setPopupMode(\"sending\");'>" + this.txt('button_send') + "</button>" +
                    "</div>";
            document.getElementById('feedback-field-screen').src = this.data.screenshot.toDataURL();
            var sc = document.querySelector('.feedback-report-image');
            sc.scrollTop = parseInt(sc.offsetWidth / this.data.screenshot.width * ( this.scroll_top * 0.9 ));
            document.getElementById('feedback-field-comment').value = this.data.comment;
        },

        _modeSending : function (tab) {
            this.disableSelection();
            var self = this;
            tab.innerHTML =
                '<div class="popup-close" onclick="Feedback.stop();"></div>'+
                    '<div id="feedback-sending-message">'+
                    '<div class="feedback-sending-loader">'+
                    '<span id="bubblingG_1"></span>'+
                    '<span id="bubblingG_2"></span>'+
                    '<span id="bubblingG_3"></span>'+
                    '</div>'+
                    '</div>';
            var info = this.data.info;
            info['screenshot'] = this.data.screenshot.toDataURL();
            info['comment']    = this.data.comment;
            info['selections'] = this.data.selections;
            info['scroll']     = this.data.scroll;
            info['data']       = this.factory.getAllData();
            var data = JSON.stringify(info);
            this.xhr = this.getXHR();
            this.xhr.open('post', this.opt('url'), true);
            this.xhr.setRequestHeader("Content-Type","text/json");
            this.xhr.onreadystatechange = function () {
                if (self.xhr.readyState == 4) {
                    if(self.xhr.status == 200) {
                        self.xhr = false;
                        self.stop();
                    } else {
                        var sending_body = document.getElementById("feedback-sending-message");
                        sending_body.innerHTML = this.txt('error_sending');
                        sending_body.className = "feedback-sending-error";
                    }
                }
            };
            this.xhr.send(data);
        },

        getXHR : function() {
            return window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
        },

        listControl : function (elem) {
            function addClass(el, cls) {
                for(var c = el.className.split(' '),i=c.length-1; i>=0; i--) {
                    if (c[i] == cls) {
                        return;
                    }
                }
                if (el.className == "") {
                    el.className = cls;
                } else {
                    el.className += ' '+cls;
                }
            }

            function hasClass(el, cls) {
                for(var c = el.className.split(' '),i=c.length-1; i>=0; i--){
                    if (c[i] == cls){
                        return true;
                    }
                }
                return false;
            }

            function removeClass(el, cls) {
                for(var c = el.className.split(' '),i=c.length-1; i>=0; i--) {
                    if (c[i] == cls) {
                        c.splice(i,1);
                    }
                }

                el.className = c.join(' ');
            }

            if (hasClass(elem, 'open-nav')) {
                removeClass(elem, 'open-nav');
            } else {
                addClass(elem, 'open-nav');
            }
        }
    }
})(window);

//window.onload = function () {
//	Feedback.init();
//};