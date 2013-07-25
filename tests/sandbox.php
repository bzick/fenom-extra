<?php

class A {
    public function method() {
        return 1;
    }
}

class B extends A {
    public $method;

    public function method() {
        return $this->method->bindTo($this)->__invoke();
    }

    public function _prev($name) {
        return call_user_func("parent::$name");
    }
}

$b = new B;
$b->method = function () {
  return $this->_prev('method');
};
var_dump($b->method());

exit;


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