<?php

trait T1 {
    public function m() {
        var_dump("t1");
    }
}

trait T2 {
    use T1;
}

trait T3 {
    use T1;

    public function m() {
        var_dump("t3");
    }
}

class C1 {
    public function m() {
        var_dump("c1");
    }
}

class C2 {
    use T2, T3;
}

$c = new C2();

$c->m();