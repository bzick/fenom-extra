<?php

namespace Fenom\Reparser;


use Fenom\Tokenizer;

class Template extends \Fenom\Template
{
    /**
     * @var \Closure[]
     */
    private $_parsers = array();


    public function parseArray(Tokenizer $tokens)
    {
        if (isset($this->_parsers["array"])) {
            return call_user_func($this->_parsers["array"]->bindTo($this), $tokens) ? : parent::parseArray($tokens);
        } else {
            return parent::parseArray($tokens);
        }
    }

    public function parseVar(Tokenizer $tokens, $options = 0)
    {
        if (isset($this->_parsers["var"])) {
            return call_user_func($this->_parsers["var"]->bindTo($this), $tokens, $options);
        } else {
            return parent::parseVar($tokens, $options);
        }
    }

    public function parseParams(Tokenizer $tokens, array $defaults = null)
    {
        if (isset($this->_parsers["params"])) {
            return call_user_func($this->_parsers["params"]->bindTo($this), $tokens, $defaults);
        } else {
            return parent::parseParams($tokens, $defaults);
        }
    }

    public function parseScalar(Tokenizer $tokens, $allow_mods = true)
    {
        if (isset($this->_parsers["scalar"])) {
            return call_user_func($this->_parsers["scalar"]->bindTo($this), $tokens, $allow_mods);
        } else {
            return parent::parseParams($tokens, $allow_mods);
        }
    }

    public function parseModifier(Tokenizer $tokens, $value)
    {
        if (isset($this->_parsers["modifier"])) {
            return call_user_func($this->_parsers["modifier"]->bindTo($this), $tokens, $value);
        } else {
            return parent::parseParams($tokens, $value);
        }
    }
}