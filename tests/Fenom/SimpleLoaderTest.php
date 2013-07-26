<?php

namespace Fenom;


class SimpleLoaderTest extends \PHPUnit_Framework_TestCase {

    public static function inlineFunction() {
        return 'inline function';
    }

    public function testLoader() {
        $fenom = new TemplaterSimpleLoader(new Provider(__DIR__));
        $fenom->addPluginsDir(FENOM_DEFAULT_PLUGINS);

        $this->assertSame('3', $fenom->compileCode('{$a|count_words}')->fetch(["a" => "modifier from fs"]));

    }

}

class TemplaterSimpleLoader extends \Fenom {
    use LoaderTrait;
    use SimpleLoaderTrait;
}