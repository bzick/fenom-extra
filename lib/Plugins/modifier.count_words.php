<?php

function fenom_modifier_count_words($string)
{
    return sizeof(preg_grep('#\w+#u', preg_split('#\s+#', $string)));
}
