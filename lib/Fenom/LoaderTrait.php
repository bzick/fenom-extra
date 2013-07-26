<?php

namespace Fenom;


trait LoaderTrait
{

    /**
     * @var callable[]
     */
    private $_tag_loaders = [];
    /**
     * @var callable[]
     */
    private $_modifier_loaders = [];

    /**
     * @param $modifier
     * @param Template $template
     * @return bool|mixed
     */
    protected function _loadModifier($modifier, $template)
    {
        foreach ($this->_modifier_loaders as $loader) {
            $mod = call_user_func($loader, $modifier, $template);
            if ($mod) {
                return $mod;
            }
        }

        return false;
    }

    /**
     * @param $tag
     * @param Template $template
     * @return bool|array
     */
    protected function _loadTag($tag, $template)
    {
        foreach ($this->_tag_loaders as $loader) {
            $info = call_user_func($loader, $tag, $template);
            if ($info) {
                return $info;
            }
        }

        return false;
    }

    /**
     * Add modifiers loader
     *
     * @param callable $loader
     * @param bool $prepend
     * @return $this
     */
    public function addModifierLoader(callable $loader, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_modifier_loaders, $loader);
        } else {
            $this->_modifier_loaders[] = $loader;
        }
        return $this;
    }

    /**
     * Add tags loader
     *
     * @param callable $loader
     * @param bool $prepend
     * @return $this
     */
    public function addTagLoader(callable $loader, $prepend = false)
    {
        if ($prepend) {
            array_unshift($this->_tag_loaders, $loader);
        } else {
            $this->_tag_loaders[] = $loader;
        }
        return $this;
    }
}