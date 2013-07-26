<?php
function fenom_modifier_default($string, $default = '')
{
    if ($string === false || $string === null || $string === '') {
        return $default;
    }
    return $string;
}

?>
