<?php

namespace Fenom\Traitor;


class Now {

    public function __toString() {
        return strval(time());
    }
}