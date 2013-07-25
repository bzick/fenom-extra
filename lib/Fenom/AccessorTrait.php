<?php

namespace Fenom;


trait AccessorTrait {
    private $_accessors = [];

    public function setAccessorName($name) {
        if(!$this->_accessors) {
            $accessors = &$this->_accessors;
            $this->_replaceParser("var", function (Tokenizer $tokens, $options = 0) use (&$accessors) {
                /* @var Template $this */
                if($tokens->is(T_VARIABLE)) {
                    $var = substr($tokens->current(), 1);
                    if(isset($accessors[$var])) {
                        // parse global var
                        $key = $tokens->next()->need(".")->next()->get(Tokenizer::MACRO_STRING);
                        switch($key) {
                            case "const":
                                break;
                            case "time":
                                break;
                            default:
                                break;
                        }
                    } else {
                        parent::parseVar($tokens, $options);
                    }
                }
            });
        }
        $this->_accessors[$name] = true;
        return $this;
    }
}