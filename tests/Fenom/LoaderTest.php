<?php

namespace Fenom;


class LoaderTest extends \PHPUnit_Framework_TestCase {

    public static function inlineFunction() {
        return 'inline function';
    }

    public function testLoaders() {
        $fenom = new TemplaterLoader(new Provider(__DIR__));

        $fenom->addModifierLoader(function ($modifier, $template) {
            $this->assertSame('some_mod', $modifier);
            $this->assertInstanceOf('Fenom\Template', $template);
            return 'strtoupper';
        });

        $fenom->addTagLoader(function ($tag, $template) {
            $this->assertSame('some_tag', $tag);
            $this->assertInstanceOf('Fenom\Template', $template);
            return array(
                "type" => \Fenom::INLINE_FUNCTION,
                "function" => __CLASS__.'::inlineFunction',
                "parser" => \Fenom::DEFAULT_FUNC_PARSER
            );
        });

        $this->assertSame('inline function', $fenom->compileCode('{some_tag}')->fetch([]));
        $this->assertSame('MODIFIER', $fenom->compileCode('{$a|some_mod}')->fetch(["a" => "modifier"]));

    }

}

class TemplaterLoader extends \Fenom {
    use LoaderTrait;
}