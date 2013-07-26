<?php

namespace Fenom;


class StorageTest extends \PHPUnit_Framework_TestCase {

    public function testAssign() {
        $fenom = new TemplaterStorage(new Provider(__DIR__));

        $var = "string2";
        $fenom->assign("var1", 'string1');
        $fenom->assignByRef("var2", $var);
        $var = "string3";
        $fenom->append("var3", "string4");
        $fenom->append("var3", "string5");
        $fenom->prepend("var3", "string6");

        $this->assertSame(array(
            "var1" => 'string1',
            "var2" => 'string3',
            "var3" => array(
                "string6",
                "string4",
                "string5",
            )
        ), $fenom->getVars());

        $fenom->resetVars();

        $this->assertEmpty($fenom->getVars());
    }

}

class TemplaterStorage extends \Fenom {
    use StorageTrait;
}