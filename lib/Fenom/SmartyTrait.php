<?php

namespace Fenom;

/**
 * Smarty fallback
 * @package Fenom
 */
trait SmartyTrait {
    public function setSmartySupport() {
        $this->addPluginsDir(dirname(FENOM_DEFAULT_PLUGINS).'/Smarty');
        /* @var \Fenom $this */
        $this->addTagFilter([$this, 'filterTag']);
    }

    /**
     * @param string $tag
     * @param Template $tpl
     * @return string
     */
    public function filterTag($tag, Template $tpl) {
        // convert variables
        if(stripos($tag, '$smarty.') !== false) {
            $tag = str_replace('$smarty.', '$.', $tag);
        }
        if(preg_match('/\$\.(foreach|section)/S', $tag)) {
            $tag = preg_replace('/\$\.(foreach|section)\.(\w+)\.(\w+)/S', '$_${1}_${2}_${3}', $tag); // $.foreach.name.index => $_foreach_name_index
        }
        $tag = preg_replace('/`\$(.*?)`/', '{$\1}', $tag); // replaces "a `$b.e` c" to "a {$b.e} c"
        $tag = preg_replace('/#([a-zA-Z0-9_]+)#/', '$.config.${1}', $tag); // replaces #VAL_NAME# to $.config.VAL_NAME
        // convert foreach
        if(strpos($tag, 'foreach') === 0) {
            $_tag = [];
            $tokens = new Tokenizer($tag);
            $tokens->skip(); // skip 'foreach' keyword
            $_tag[] = 'foreach';
            if($tokens->is(T_VARIABLE)) {
                $tpl->parseTerm($tokens);             // foreach [$item] as  $key  => $val
                $tokens->need(T_AS)->next();          // foreach  $item [as] $key  => $val
                $tpl->parseTerm($tokens);             // foreach  $item  as [$key] => $val
                if($tokens->is(T_DOUBLE_ARROW)) {
                    $tpl->parseTerm($tokens->skip()); // foreach  $item  as  $key [=> $val]
                }
                $params = $tpl->parseParams($tokens);
            } else {
                $params = $tpl->parseParams($tokens);
            }
            if(isset($params['name'])) {
                $_tag[] = 'index=$_foreach_'.$params['name'].'_index';
                $_tag[] = 'last=$_foreach_'.$params['name'].'_last';
                $_tag[] = 'first=$_foreach_'.$params['name'].'_first';
            }
            var_dump($params); exit;
            if(preg_match_all('/^foreach(\s+\w+=.*?)+$/', $tag, $matched)) { // smarty2
                var_dump($matched);
            } elseif (preg_match('/^foreach\s*as\s*(?:(.*?)=>)?(\$\w+)(?:\s*(\w+=.*?))$/', $tag, $matched)) { // smarty3

            }
        }

        return $tag;
    }
}