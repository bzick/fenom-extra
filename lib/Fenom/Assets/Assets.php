<?php

namespace Fenom;


class Assets
{

    public static function add(\Fenom $fenom)
    {
        $fenom->addBlockCompiler("js", __CLASS__.'::jsOpen', __CLASS__.'::jsClose');
        $fenom->addBlockCompiler("css", __CLASS__.'::cssOpen', __CLASS__.'::cssClose');
        $fenom->addCompiler("assets", __CLASS__.'::tagAssets');
    }

    public static function jsOpen(Tokenizer $tokens, Scope $scope)
    {
        $params = $scope->tpl->parseParams($tokens);
        if (isset($params["src"])) {
            $scope->is_closed = true;
            return '$tpl["_assets_js"][] = array("code" => false, "src" => '.$params["src"].');';
        } else {
            return 'ob_start()';
        }
    }

    public static function jsClose()
    {
        return '$tpl["_assets_js"][] = array("code" => ob_get_flush(), "src" => $tpl->__toString(), "mtime" => $tpl->getTime());';
    }

    public static function cssOpen(Tokenizer $tokens, Scope $scope)
    {
        $params = $scope->tpl->parseParams($tokens);
        if (isset($params["src"])) {
            $scope->is_closed = true;
            return '$tpl["_assets_css"][] = array("code" => false, "src" => '.$params["src"].');';
        } else {
            return 'ob_start()';
        }
    }

    public static function cssClose()
    {
        return '$tpl["_assets_css"][] = array("code" => ob_get_flush(), "src" => $tpl->__toString(), "mtime" => $tpl->getTime());';
    }


    public static function tagAssets(Tokenizer $tokens, Template $tpl)
    {

    }

}