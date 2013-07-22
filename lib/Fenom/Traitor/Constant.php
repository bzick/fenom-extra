<?php
namespace Fenom\Traitor;


class Constant implements \ArrayAccess {

    public function offsetExists($offset) {
        return defined($offset);
    }

    public function offsetGet($offset) {
        return constant($offset);
    }


    public function offsetSet($offset, $value) { }
    public function offsetUnset($offset) {}
}