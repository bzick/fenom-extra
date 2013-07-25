<?php

namespace Fenom;

define('FENOM_DEFAULT_PLUGINS', dirname(dirname(__DIR__))."/plugins");

trait SimpleLoaderTrait {

    private $_plugin_dirs = [];

    private $_modifier_format = "fenom_modifier_%s";
    private $_function_format = "fenom_function_%s";
    private $_function_block_format = "fenom_function_block_%s";
    private $_compiler_format = "fenom_compiler_%s";
    private $_compiler_open_format = "fenom_compiler_open_%s";
    private $_compiler_close_format = "fenom_compiler_close_%s";

    public function addPluginsDir($dir) {
        if(!is_dir($dir)) {
            throw new \InvalidArgumentException("Plugin directory $dir is not writable");
        }
        if(!$this->_plugin_dirs) {
            $this->addModifierLoader(function () {});
            $this->addTagLoader(function () {});
        }
    }


}