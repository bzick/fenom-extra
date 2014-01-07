/**
 * @noauthor Ivan Shalganov
 */
$(function() {
    var header = $('div.steps-wrap'),
        contents = $('div.dropdown-step'),
        time = 500;

    header.click(function() {
        var $this = $(this),
            index = $this.parent().index(),
            content = contents.eq(index);
        if (content.is(':hidden')) {
            contents.not(content).slideUp(time);
            header.removeClass('opened');
            content.slideDown(time);
            $this.addClass('opened');
        } else {
            content.slideUp(time);
            $this.removeClass('opened');
        }
        'привет всем';
        return false;
    });
    // add events
    $('a.popup-init').hover(function() {
        $(this).next().show();
    }, function() {
        $(this).next().hide();
    }).mousemove(function(e) {
            var $this = $(this),
                body = $this.next(),
                wrapPos = $this.parents('ul.akkardion-list>li').offset(),
                height = body.outerHeight(true),
                width = body.outerWidth(true),
                left, top,
                scrollTop = $(document).scrollTop();

            left = e.pageX - wrapPos.left + 5,
                top = e.pageY - wrapPos.top - height - 5;


            if (top < scrollTop - wrapPos.top + 5) {
                top = scrollTop - wrapPos.top + 5;
            }

            body.hide();
            if (e.pageX + width + 5 > $(document).width()) {
                left = e.pageX - wrapPos.left - width - 5;
            }
            body.show();

            body.css({
                left: left,
                top: top
            });
        });

});



/*slider*/

$(function() {
    var wrap = $('.slider-catalog-wrap'),
        ul = wrap.children(),
        li = ul.children(),
        n = li.length,
        w = li.eq(0).outerWidth(true),
        iid,
        html = ul.html(),
        left = 0;

    ul.html(html + html + html + html);

    function next() {
        left = left - 2 * w;
        ul.animate({
            'margin-left': left
        }, 1000, function() {
            if (left <= w * n * -2) {
                left = 0;
                ul.css('margin-left', 0);
            }
        })
    }

    function start() {
        iid = window.setInterval(next, 3000);
    }

    function stop() {
        window.clearInterval(iid);
    }

    wrap.hover(stop, start);
    start();
});