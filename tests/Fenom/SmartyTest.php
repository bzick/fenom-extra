<?php

namespace Fenom;


class SmartyTest extends \PHPUnit_Framework_TestCase {


    public function testAssignTag() {
        $fenom = Extra::factory('.');
        var_dump($fenom->compileCode('{assign var="a" value="sdf sf "}')->getBody());
        var_dump($fenom->compileCode('{assign var="a" value="sdf sf {$c.d.e} $d "}')->getBody());
        var_dump($fenom->compileCode('{assign var="a" value=$a+1}')->getBody());
    }
}