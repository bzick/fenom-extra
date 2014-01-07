Add-ons for Fenom [development]
=================

Extra add-ons pack for [Fenom](https://github.com/bzick/fenom) template engine.

> Composer package: `{"fenom/extra": "dev-master"}`. See on [Packagist.org](https://packagist.org/packages/fenom/extra)

[![Build Status](https://travis-ci.org/bzick/fenom-extra.png?branch=master)](https://travis-ci.org/bzick/fenom-extra)

***

The library is a collection of add-ons. Each add-on present as trait. This realization allow create your template engine with custom collection of add-ons.
Also library contain class `Fenom\Extra` - template engine which include all add-ons and ready out of the box.

Use class `Fenom\Extra` instead of `Fenom`:

```php
$fenom = Fenom\Extra::factory($template_dir, $compiled_dir, $options);
```

Or combine your own templater:

### [List of add-ons](./docs/readme.md)

For example:
```php
class Templater extends \Fenom {
    use \Fenom\StorageTrait, // add internal storage of variables
        \Fenom\LoaderTrait, // allow add yours loaders for modifiers and tags
        \Fenom\PluginLoaderTrait; // add loader for modifiers and tags in Smarty-like style
    // ...
}
```

All add-ons implemented in class `\Fenom\Extra`.

### Storage

Add-on: `Fenom\StorageTrait`

```php
<?php
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

### Loader

Add-on: `Fenom\LoaderTrait`

### Plugin loader

Add-on: `Fenom\SimpleLoaderTrait`
Require: `Fenom\LoaderTrait`

```php
<?php
$fenom->addPluginsDir($path);
```

### Smarty

Add-on: `Fenom\SmartyTrait`
Require: `Fenom\SimpleLoaderTrait`

```php
<?php
$fenom->setSmartySupport();  // enable smarty syntax support
```

Supported:

- backticks operator in quoted string: ```{func var="test `$foo[0]` test"}```
- global variable `$smarty` (without `section` data)
- tag `assign`
- tag `foreach`
- tag `section`
- tag `math`

Todo:

- invoke plugins in quoted strings: `{func var="test {counter} test"}`
- global variable `$smarty` with `section` data

### Assets