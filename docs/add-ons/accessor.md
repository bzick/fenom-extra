Accessor
========

Add global template variables which allow access to global data e.g. `$_GET`, `$_POST`.
Variable support:
* `{$var.const.CONST_NAME}` access to constants
* `{$var.get}` access to `$_GET` array
* `{$var.post}` access to `$_POST` array
* `{$var.files}` access to `$_FILES` array
* `{$var.session}` access to `$_SESSION` array
* `{$var.cookie}` access to `$_COOKIE` array
* `{$var.request}` access to `$_REQUEST` array
* `{$var.server}` access to `$_SERVER` array
* `{$var.globals}` access to `$GLOBALS` array
* `{$var.version}` return version of the Fenom
* `{$var.ldelim}`, `{$var.rdelim}` return right and left delimiters of the Fenom tags
* `{$var.now}` return current time stamp

### Setup

**Trait:** `Fenom\AccessorTrait`

```php
class Templater extends Fenom {
    use Fenom\AccessorTrait;
    /* ... */
}
```

### Usage

```php
$fenom->setAccessorName('fenom'); // add global variable $fenom
$fenom->setAccessorName('smarty'); // add global variable $smarty
```

```smarty
Name: {$fenom.get.name}
```

