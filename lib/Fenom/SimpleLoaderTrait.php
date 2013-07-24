<?php

namespace Fenom;


trait SimpleLoaderTrait {

    protected $_plugin_dir;
    protected $_modifier_func = "fenom_modifier_%s";
    protected $_modifier_file = "fenom_modifier_%s.php";

    public function setPluginDir($dir) {
        if(!is_dir($dir)) {
            throw new \InvalidArgumentException("Plugin directory $dir does not exist");
        }
        $this->setModifierLoader(array($this, "_modifierLoader"));
        $this->_plugin_dir = $dir;
    }
}