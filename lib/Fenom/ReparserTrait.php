<?php

namespace Fenom;


use Fenom\Reparser\Template;

class ReparserTrait
{

    private $_reparsers = [];

    /**
     * Replace any parser Fenom\Template::parser*
     * @param string $name parser name without prefix parser.
     * @param \Closure $parser new parser
     */
    protected function _replaceParser($name, \Closure $parser)
    {
        $this->_reparsers[$name] = $parser;
    }

    /**
     * @return Template
     */
    protected function getRawTemplate()
    {
        if ($this->_reparsers) {
            return new Template($this, $this->_options, $this->_reparsers);
        } else {
            return parent::getRawTemplate();
        }
    }
}