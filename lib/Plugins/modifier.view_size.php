<?php

function fenom_modifier_view_size($size)
{
    $size = intval($size);
    if ($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 .' GiB';
    } elseif ($size >= 1048576) {
        $size = round($size / 1024 / 1024, 2) .' MiB';
    } elseif ($size >= 1024) {
        $size = round($size / 1024, 2) .' KiB';
    } else {
        $size = $size.' B';
    }
    return $size;
}