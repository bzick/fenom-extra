Add-ons for Fenom [development]
=================

Extra add-ons pack for [Fenom](https://github.com/bzick/fenom) template engine.

> Composer package: `{"fenom/extra": "dev-master"}`. See on [Packagist.org](https://packagist.org/packages/fenom/extra)

[![Build Status](https://travis-ci.org/bzick/fenom-extra.png?branch=master)](https://travis-ci.org/bzick/fenom-extra)

***

The library is a collection of add-ons. Each add-on present as trait. This realization allow create your template engine with custom collection of add-ons.
Also library contain class `Fenom\Extra` - template engine which include all add-ons and ready out of the box.

### [List of add-ons](./docs/readme.md)

For example:
```php
class Templater extends \Fenom {
    use \Fenom\VarStorageTrait, // add internal storage of variables
        \Fenom\LoaderTrait, // allow add yours loaders for modifiers and tags
        \Fenom\PluginLoaderTrait; // add loader for modifiers and tags in Smarty-like style
    // ...
}
```
