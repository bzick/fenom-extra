<?php

namespace Fenom\Assets;


use Fenom\Tokenizer;

class Packer {

    const ENC62 = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const ENC64 = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_@";

    public static $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
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
    public static function minify($code, $encode = false, $chunk_size = 1024) {
        if(strlen($code) > $chunk_size) {
            $min = [];
            $start = 0;
            $end   = $chunk_size;

            while($end = strpos($code, "\n", $end)) {
                $min[] = self::_min(substr($code, $start, $end), $encode);
                $start = $end;
                $end  += $chunk_size;
                if($end > strlen($code)) {
                    $min[] = self::_min(substr($code, $start), $encode);
                }
            }
            return implode("", $min);
        } else {
            return self::_min($code, $encode);
        }
    }

    private static function _min($code, $encode = false) {
//        var_dump($code);
        $dict = array();
        $replaces = Tokenizer::$macros[Tokenizer::MACRO_STRING];
        $replaces[\T_VARIABLE] = true;

        $code = str_replace('?>', "=>php_close=>", $code);
        $code = str_replace('`', "=>php_bq=>", $code);
        $code = preg_replace('/([^\pL0-9]\s*)\/([^\/].*?[^\\\])\//', '$1`$2`', $code);
//        $code = str_replace('"', "=>double_quote=>", $code);
//        $code = str_replace("'", "=>single_quote=>", $code);
        $tokens = @token_get_all("<?php ".$code);
//        var_dump($tokens); exit;
        array_shift($tokens);
        $min = "";
        $prev = 0;
        $length = count($tokens);
        for($p = 0; $p < $length; $p++) {
            $token = $tokens[$p];
            if(is_string($token)) {
                $min .= $token;
                $prev = $token;
            } elseif ($token[0] === \T_WHITESPACE || $token[0] === \T_COMMENT || $token[0] === \T_DOC_COMMENT) {
                continue;
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
            } elseif($token[0] === \T_DOUBLE_ARROW && isset($tokens[$p+2][0]) && $tokens[$p+2][0] === \T_DOUBLE_ARROW) {
                if(isset($tokens[$p+1][0])) {
                    $next = $tokens[$p+1];
                    if($next[0] === \T_STRING) {
                        switch($next[1]) {
                            case 'php_bq':
                                $min .= "`";
                                break;
                            case 'php_close':
                                $min .= '?>';
                                break;
                            default:
                                $min .= $token[1];
                                continue;
                        }
                        $p+=2;
                    }
                }
            } else {
                $min .= $token[1];
                $prev = $token[0];
            }
        }
        return $min."\n".self::$chars."\n".implode(',', array_keys($dict));
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