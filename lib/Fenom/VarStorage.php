<?php

namespace Fenom;


trait VarStorage {
    /**
     * @var array storage
     */
    protected $_vars = array();

    /**
     * @param array $variables
     * @return $this
     */
    public function assignAll(array $variables) {
        $this->_vars = $variables + $this->_vars;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assign($name, $variable) {
        $this->_vars[$name] = $variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function assignByRef($name, &$variable) {
        $this->_vars[$name] = &$variable;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $variable
     * @return $this
     */
    public function prepend($name, $variable) {
        if(!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if(!is_array($this->_vars[$name])) {
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
    public function append($name, $variable) {
        if(!isset($this->_vars[$name])) {
            $this->_vars[$name] = array();
        }
        if(!is_array($this->_vars[$name])) {
            $this->_vars[$name] = (array)$this->_vars[$name];
        }
        $this->_vars[$name][] = $variable;
        return $this;
    }

    /**
     * Get collected variables
     * @return array
     */
    public function getVars() {
        return $this->_vars;
    }

    /**
     * Reset collected variables
     * @return $this
     */
    public function resetVars() {
        $this->_vars = array();
        return $this;
    }

    /**
     * @param string $template
     */
    public function display($template) {
        /* @var \Fenom|\Fenom\VarStorage $this */
        $this->_vars = parent::display($template, $this->_vars);
    }

    /**
     * @param $template
     * @return string
     * @throws \Exception
     */
    public function fetch($template) {
        /* @var \Fenom|\Fenom\VarStorage $this */
        $tpl = $this->getTemplate($template);
        ob_start();
        try {
            $this->_vars = $tpl->display($this->_vars);
            return ob_get_clean();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * @param string $template
     * @param $callback
     * @param float $chunk
     */
    public function pipe($template, $callback, $chunk = 1e6) {
        /* @var \Fenom|\Fenom\VarStorage $this */
        $this->_vars = parent::pipe($template, $this->_vars, $callback, $chunk);
    }
}