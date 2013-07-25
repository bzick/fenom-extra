Reparser
========

Allow redefine some parser `\Fenom\Template::parser*` methods.

### Setup

**Trait:** `Fenom\ReparserTrait`

```php
class Templater extends Fenom {
    use Fenom\ReparserTrait;
    /* ... */
}
```

### Usage

```php

class Templater extends Fenom {
    use Fenom\ReparserTrait;
    /* ... */

    public function __construct($provider) {
        parent::__construct($provider);

        $this->_replaceParser('array', function (\Fenom\Tokenizer $tokens) {
            /* @var \Fenom\Template $this */
            // your array parser
        });
    }
}
```