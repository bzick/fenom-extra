<?php

namespace Fenom;


class Traitor extends \ArrayObject {

    public function __construct() {
        $this["version"] = \Fenom::VERSION;
        $this["ldelim"]  = '{';
        $this["rdelim"]  = '}';
        $this['get']     = &$_GET;
        $this['post']    = &$_POST;
        $this['request'] = &$_REQUEST;
        $this['cookie']  = &$_COOKIE;
        $this['files']   = &$_FILES;
        $this['server']  = &$_SERVER;
        $this['session'] = &$_SESSION;
        $this['globals'] = &$GLOBALS;
        $this['now']     = new Traitor\Now();
        $this['const']   = new Traitor\Constant();
    }
}