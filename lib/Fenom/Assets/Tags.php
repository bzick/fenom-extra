<?php

namespace Fenom\Assets;

use Fenom\Tokenizer;
use Fenom\Tag;
use Fenom\Template;

class Tags
{
    public static function jsOpen(Tokenizer $tokens, Tag $scope)
    {
        $params = $scope->tpl->parseParams($tokens);
        if (isset($params["src"])) {
            $scope->is_closed = true;
            return '$tpl->getStorage()->addJS(array("code" => false, "src" => '.$params["src"].'));';
        } else {
            return 'ob_start()';
        }
    }

    public static function jsClose()
    {
        return '$tpl["_assets_js"][] = array("code" => ob_get_flush(), "src" => $tpl->__toString(), "mtime" => $tpl->getTime());';
    }

    public static function cssOpen(Tokenizer $tokens, Tag $scope)
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
