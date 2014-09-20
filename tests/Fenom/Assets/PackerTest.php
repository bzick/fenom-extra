<?php

namespace Fenom\Assets;

use Fenom\Assets\Packer;

class PackerTest extends \PHPUnit_Framework_TestCase {


    public function testMinify() {

        $t = microtime(1);
//        $packed = Packer::minify(file_get_contents($file = FENOM_RES_DIR.'/jquery.js'), true);
        $packed = Packer::minify(file_get_contents($file = FENOM_RES_DIR.'/unpacked.js'), true, 99999999);
        $t = microtime(1) - $t;

        print_r("\npacked ".strlen($packed)."/".filesize($file).": ".(strlen($packed)/filesize($file))." in $t sec");
//        file_put_contents(FENOM_RES_DIR.'/compiled/jquery.min.js', $packed);
        file_put_contents(FENOM_RES_DIR.'/compiled/unpacked.min.js', $packed);
    }
}