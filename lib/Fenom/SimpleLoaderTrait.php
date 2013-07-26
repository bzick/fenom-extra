<?php

namespace Fenom;

define('FENOM_DEFAULT_PLUGINS', dirname(__DIR__)."/plugins");

trait SimpleLoaderTrait
{
    /* use LoaderTrait */

    private $_plugin_dirs = [];
    private $_plugins = [];

    private $_modifier_format = 'fenom_modifier_%s';
    private $_tag_formats = [
        'function' => 'fenom_function_%s',
        'compiler' => 'fenom_compiler_%s',
        'function.smart' => 'fenom_function_smart_%s',
        'function.block' => 'fenom_function_block_%s',
        'compiler.block' => 'fenom_compiler_block_%s',
    ];

    /**
     * @param $dir
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addPluginsDir($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException("Plugin directory $dir not found");
        }
        if (!$this->_plugin_dirs) {
            $this->addModifierLoader([$this, '_modifierLoader']);
            $this->addTagLoader([$this, '_tagLoader']);
        }
        $this->_plugin_dirs[] = $dir;
        return $this;
    }

    /**
     * @param $modifier
     * @param $template
     * @return bool|callable|string
     */
    private function _modifierLoader($modifier, $template)
    {
        /* @var Template $template */
        $name = sprintf($this->_modifier_format, $modifier);
        if (isset($this->_plugins[$name])) {
            if ($template) {
                $template->before("if (!is_callable('$name')) {\n\tinclude_once '{$this->_plugins[$name]}';\n}\n");
            }
            return $name;
        }
        foreach ($this->_plugin_dirs as $dir) {
            $path = $dir.DIRECTORY_SEPARATOR.'modifier.'.$modifier.'.php';
            if (is_file($path) && is_readable($path)) {
                if ($template) {
                    $template->before("if (!is_callable('$name')) {\n\tinclude_once '$path';\n}\n");
                }

                require_once $path;

                if (is_callable($name)) {
                    $this->_plugins[$name] = $path;
                    return $name;
                }
            }
        }
        return false;
    }

    /**
     * @param $tag
     * @param $template
     * @return mixed
     */
    private function _tagLoader($tag, $template)
    {
        if (isset($this->_plugins[$tag])) {
            if ($template) {
                $name = $this->_plugins[$tag]["name"];
                $path = $this->_plugins[$tag]["path"];
                $template->before("if (!is_callable('$name')) {\n\tinclude_once '$path';\n}\n");
            }
            return $this->_plugins[$tag];
        }
        foreach ($this->_plugin_dirs as $dir) {
            foreach ($this->_tag_formats as $prefix => $format) {
                $name = sprintf($format, $tag);
                $path = $dir.DIRECTORY_SEPARATOR.$prefix.'.'.$tag.'.php';
                if (is_file($path) && is_readable($path)) {
                    $c = require_once($path);
                } else {
                    continue;
                }

                if (!is_callable($name)) {
                    continue;
                }

                switch ($prefix) {
                    case 'function':
                        $info = array(
                            'type' => \Fenom::INLINE_FUNCTION,
                            'function' => $name,
                            'parser' => \Fenom::DEFAULT_FUNC_PARSER,
                            'name' => $name,
                            'path' => $path
                        );
                        break;
                    case 'function_smart':
                        $info = array(
                            'type' => \Fenom::INLINE_FUNCTION,
                            'function' => $name,
                            'parser' => \Fenom::SMART_FUNC_PARSER,
                            'name' => $name,
                            'path' => $path
                        );
                        break;
                    case 'compiler':
                        $info = array(
                            'type' => \Fenom::INLINE_COMPILER,
                            'parser' => $name,
                            'name' => $name,
                            'path' => $path
                        );
                        break;
                    case 'compiler_block':
                        if (!is_callable($name."_close")) {
                            continue;
                        }
                        $info = array(
                            'type' => \Fenom::BLOCK_COMPILER,
                            'open' => $name,
                            'close' => $name."_close",
                            'tags' => isset($c["tags"]) ? $c["tags"] : [],
                            'floats' => isset($c["floats"]) ? $c["floats"] : [],
                            'name' => $name,
                            'path' => $path
                        );
                        break;
                    default:
                        continue;
                }

                if ($template) {
                    $template->before("if (!is_callable('$name')) {\n\tinclude_once '$path';\n}\n");
                }

                $this->_plugins[$tag] = $info;

                return $info;
            }
        }
        return false;
    }

}