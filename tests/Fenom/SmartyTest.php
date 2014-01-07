<?php

namespace Fenom;

class SmartyTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \Fenom\Extra
     */
    public $fenom;

    public function setUp() {
        $this->fenom = Extra::factory(FENOM_RES_DIR.'/templates');
        $this->fenom->setSmartySupport();
    }

    public function testSandbox() {
        try {
            var_dump($this->fenom->compileCode('{foreach from=$list item="i" key="k" name="lister"} {$smarty.foreach.list.index} {/foreach}')->getBody());
        } catch(\Exception $e) {
            echo $e;
        }

        exit(1);
    }

    public function testGlobals() {


        var_dump($this->fenom->compileCode('{$smarty.get.one}')->getBody());
        var_dump($this->fenom->compileCode('{$smarty.session.one}')->getBody());
        var_dump($this->fenom->compileCode('{$smarty.foreach.one.index}')->getBody());

    }

    public function testAssignTag() {

//        var_dump($fenom->compile('smarty/assign.tpl')->fetch([]));

//        var_dump($fenom->compileCode('{assign var="a" value="sdf sf "}')->getBody());
//        var_dump($fenom->compileCode('{assign var="a" value="sdf sf {$c.d.e} $d "}')->getBody());
//        var_dump($fenom->compileCode('{assign var="a" value=$a+1}')->getBody());
    }

}