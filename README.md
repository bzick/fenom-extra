Add-ons for Fenom [development]
=================

Extra add-on pack for [Fenom](https://github.com/bzick/fenom) template engine.

### Collector Fenom\VarStorageTrait

*About:* trait gives the ability to store variables in Fenom.
*Setup:*
```php
class Templater extends Fenom {
    use Fenom\VarStorageTrait;
    /* ... */
}
```
*Usage:*
```php
$fenom->resetVars();
$fenom->assign("var_name", $value);
$fenom->assignByRef("var_name", $value);
$fenom->append("var_name", $value);
$fenom->prepend("var_name", $value);
$vars = $fenom->getVars();
$fenom->assertVars($vars);

$fenom->pipe($template_name, $callback);
$fenom->fetch($template_name);
$fenom->display($template_name);
```

### Accessor Fenom\Traitor

*About:* simple object implementation of Smarty template variable `$smarty`. Use object `Fenom\Traitor` as template variable.
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

*Setup:* add object manually or use trait `Fenom\AccessorTrait`:
```php
class Templater extends Fenom {
    use Fenom\AccessorTrait;
    /* ... */
}
// ...
$fenom->addAccessorAs(["smarty", "fenom"]);
```

### Fenom\Assets [dev]

*About:* declare tags `{js}` and `{css}` for JavaScript and CSS static files
*Setup:*
```php
class Templater extends Fenom {
    use Fenom\AssetsTrait;
    /* ... */
}
// ...
$fenom->enableAssets($settings);
```
*Usage:*
```smarty
{js src="/path/to/script.js"}
{js}
    tm.init(1000);
{/js}

{css src="/path/to/styles.css"}
{css}
div#content {
    color: black;
}
{/css}

{assets 'js'} flush all collected js scripts
{assets 'css'} flush all styles
```


### Cacher Fenom\CacheTrait

### Loader Fenom\ModifierLoaderTrait

