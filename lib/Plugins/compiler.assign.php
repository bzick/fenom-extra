<?php

/**
 * @param \Fenom\Tokenizer $tokenizer
 * @param \Fenom\Template $tpl
 */
function fenom_compiler_assign(\Fenom\Tokenizer $tokenizer, \Fenom\Template $tpl) {
    $params = $tpl->parseParams($tokenizer);
    if(!isset($params["var"])) {
        throw new LogicException("{assign} require 'var' attribute");
    }
    if(!isset($params["value"])) {
        throw new LogicException("{assign} require 'value' attribute");
    }
    return '$tpl['.$params["var"].'] = '.$params["value"];
}