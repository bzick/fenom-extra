<?php

namespace Fenom\Traitor;

class Traitor extends \ArrayObject
{
    public function __construct()
    {
        $data = [
            'version' => \Fenom::VERSION,
            'ldelim' => '{',
            'rdelim' => '}',
            'get' => filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING),
            'post' => filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING),
            'request' => filter_input_array(INPUT_REQUEST, FILTER_SANITIZE_STRING),
            'cookie' => filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING),
            'files' => isset($_FILES) ? $_FILES : [],
            'server' => filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING),
            'env' => filter_input_array(INPUT_ENV, FILTER_SANITIZE_STRING),
            'session' => isset($_SESSION) ? $_SESSION : [],
            'globals' => isset($GLOBALS) ? $GLOBALS : [],
            'now' => new Now(),
            'const' => new Constant(),
        ];
        parent::__construct($data, self::ARRAY_AS_PROPS);
    }
}
