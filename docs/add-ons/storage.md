Storage
=======

Add-on allow to store variables in the Fenom.

### Setup

**Trait:** `Fenom\StorageTrait`

```php
class Templater extends Fenom {
    use Fenom\StorageTrait;
    /* ... */
}
```

### Usage

```php
$fenom->assign("var_name", $value);
$fenom->assignByRef("var_name", $value);
$fenom->append("var_name", $value);
$fenom->prepend("var_name", $value);
$vars = $fenom->getVars();
$fenom->assignVars($vars);
$fenom->resetVars();

$fenom->pipe($template_name, $callback);
$fenom->fetch($template_name);
$fenom->display($template_name);
```
