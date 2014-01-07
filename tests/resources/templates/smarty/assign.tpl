{var $a = ['b' => 6]}
{assign var="a1" value=$a.b+4}
{assign var="a2" value="hello `$a.b`"}
{assign var="a3" value=$a}
{*{assign var=a4 value=$a.b++}*}
{$a.b}, {$a1}, {$a2}, {$a3.b}