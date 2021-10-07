<?php

/**
 * Fenom plugin
 *
 * @package Fenom
 * @subpackage PluginsModifier
 */

/**
 * Fenom capitalize modifier plugin
 *
 * Type:     modifier<br>
 * Name:     capitalize<br>
 * Purpose:  capitalize words in the string
 *
 * @param string $string string to capitalize
 * @param bool $uc_digits also capitalize "x123" to "X123"
 * @return string capitalized string
 */
function fenom_modifier_capitalize($string, $uc_digits = false)
{
    fenom_modifier_capitalize_ucfirst(null, $uc_digits);
    return preg_replace_callback('!\'?\b\w(\w|\')*\b!', 'fenom_modifier_capitalize_ucfirst', $string);
}

/**
 * @param string|null $string string to capitalize
 * @param bool|null $uc_digits also capitalize "x123" to "X123"
 * @return string
 */
function fenom_modifier_capitalize_ucfirst($string, $uc_digits = null)
{
    static $_uc_digits = false;
    if ($uc_digits !== null) {
        $_uc_digits = $uc_digits;
        return '';
    }
    if ($_uc_digits || substr($string[0], 0, 1) !== "'" && !preg_match('!\d!', $string[0])) {
        return ucfirst($string[0]);
    }
    return $string[0];
}
