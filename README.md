Add-ons for Fenom
=================

### Trait VarStorage

Gives the ability to store variables.

```smarty
class Templater extends Fenom {
    use Fenom\VarStorage;
    /* ... */
}
```
or use `Fenom\Extra`

### Global traitor

Simple implementation of Smarty template variable `$smarty`. Use object `Fenom\Traitor` as template variable.
Variable support:
* `{$var.const.CONST_NAME}` access to constants
* `{$var.get}` access to `$_GET` array
* `{$var.post}` access to `$_POST` array
* `{$var.cookie}` access to `$_COOKIE` array
* `{$var.request}` access to `$_REQUEST` array
* `{$var.server}` access to `$_SERVER` array
* `{$var.globals}` access to `$GLOBALS` array