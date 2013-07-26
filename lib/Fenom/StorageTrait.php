<?php

namespace Fenom;


trait StorageTrait
{
    /**
     * @var array storage
     */
    protected $_vars = array();

    /**
     * @param array $variables
     * @return $this
     */
    public function assignAll(array $variables)
    {
        $this->_vars = $variables + $this->_vars;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assign($name, $variable)
    {
        $this->_vars[$name] = $variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assignByRef($name, &$variable)
    {
        $this->_vars[$name] = & $variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function prepend($name, $variable)
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        array_unshift($this->_vars[$name], $variable);
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function append($name, $variable)
    {
        if (!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if (!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        $this->_vars[$name][] = $variable;
        return $this;
    }

    /**
     * Get collected variables
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Reset collected variables
     * @return $this
     */
    public function resetVars()
    {
        $this->_vars = array();
        return $this;
    }

    /**
     * @param string $template
     * @param array $vars
     */
    public function display($template, array $vars = array())
    {
        /* @var \Fenom|\Fenom\VarStorageTrait $this */
        $this->_vars = parent::display($template, $this->_vars + $vars);
    }

    /**
     * @param string $template
     * @param array $vars
     * @throws \Exception
     * @return string
     */
    public function fetch($template, array $vars = array())
    {
        /* @var \Fenom|\Fenom\VarStorageTrait $this */
        $tpl = $this->getTemplate($template);
        ob_start();
        try {
            $this->_vars = $tpl->display($this->_vars + $vars);
            return ob_get_clean();
        } catch(\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * @param string $template
     * @param $callback
     * @param array $vars
     * @param float $chunk
     */
    public function pipe($template, $callback, array $vars = array(), $chunk = 1e6)
    {
        /* @var \Fenom|\Fenom\VarStorageTrait $this */
        $this->_vars = parent::pipe($template, $this->_vars + $vars, $callback, $chunk);
    }
}