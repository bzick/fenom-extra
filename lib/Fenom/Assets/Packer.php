<?php

namespace Fenom\Assets;


use Fenom\Tokenizer;

class Packer {

    public static $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_@&";
    public static $reg = '/[_@&\w]+/uS';
    /**
     *
     * a-z: 97-122
     * A-Z: 65-90
     * @param $code
     * @param bool $encode
     * @return string
     *
     */
    public static function minify($code, $encode = false) {
        $dict = array();
        $proc = function ($match) use (&$dict) {
            return self::_holder($match[0], $dict);
        };
        $replaces = Tokenizer::$macros[Tokenizer::MACRO_STRING];
        $replaces[\T_VARIABLE] = true;

        $code = str_replace('?>', "?\x07>", $code);
        $tokens = @token_get_all("<?php ".$code);
        array_shift($tokens);
        $min = "";
        $prev = 0;
        $length = count($tokens);
        for($p = 0; $p < $length; $p++) {
            $token = $tokens[$p];
            if(is_string($token)) {
                if($token === '"') {
                    $quote = $token;
                    for(;$p<$length; $p++) {
                        $token = $tokens[$p];
                        $quote .= is_string($token) ? $token : $token[1];
                        if($token === '"') {
                            break;
                        }
                    }
                    if($encode) {
                        $min .= preg_replace_callback(self::$reg, $proc, $quote);
                    } else {
                        $min .= $quote;
                    }
                } else {
                    $min .= $token;
                    $prev = $token;
                }
            } elseif ($token[0] === \T_WHITESPACE || $token[0] === \T_COMMENT || $token[0] === \T_DOC_COMMENT) {
                continue;
            } elseif($token[0] === \T_CONSTANT_ENCAPSED_STRING && $encode) {
                $min .= preg_replace_callback(self::$reg, $proc, $token[1]);
            } elseif(isset($replaces[$token[0]])) {
                if($encode) {
                    $holder = self::_holder($token[1], $dict);
                } else {
                    $holder = $token[1];
                }
                if(isset($replaces[$prev])) {
                    $min .= " ".$holder;
                } else {
                    $min .= $holder;
                    $prev = $token[0];
                }
            } elseif($token[0] === \T_INLINE_HTML) {
                $tokens = array_merge($tokens, token_get_all("<?php ".$token[1]));
                echo "somthing wrang: ".substr($token[1], 0, 100);
                exit;
            } elseif($token[0] === \T_BAD_CHARACTER) {
                exit("Bad chars ".$token[1]);
            } else {
                $min .= $token[1];
                $prev = $token[0];
            }
        }
        return $min."\n".self::$chars."\n".implode('|', array_keys($dict));
    }

    /**
     * @param $token
     * @param $dict
     * @return string
     */
    private static function _holder($token, &$dict) {
        if(isset($dict[ $token ])) {
            return $dict[ $token ];
        } else {
            $holder = "";
            $size = strlen(self::$chars);
            $i = count($dict);
            do {
                $holder = self::$chars[$i % $size].$holder;
                $i = intval($i / $size);
            } while($i);

            return $dict[ $token ] = $holder;
        }
    }
}