Assets
======

Declare the tags `{js}` and `{css}` for JavaScript and CSS static files.

* `$fenom->setStaticCollect($flag)`
* `$fenom->setDocRoot($dir)` - set document root for static files
* `$fenom->setMinifyJS($fenom::MINIFY_ON_COMPILE | $fenom::PACK_TO_ONE)`
* `$fenom->setMinifyCSS($fenom::MINIFY_ON_COMPILE | $fenom::PACK_TO_ONE)`
* `$fenom->setMinifyDir($absolute, $url)`
* `$fenom->setStaticVersion($version)`
* `$fenom->addJS($url, $bundle = null)`
* `$fenom->addJSCode($code, $bundle = null)`
* `$fenom->addCSS($url, $bundle = null)`
* `$fenom->addCSSCode($code, $bundle = null)`
* `$fenom->addBundle($name, $urls)`
* `$fenom->addBundles($bundles)`
* `$fenom->setCheckFactor($factor)`

### Setup

**Trait:** `Fenom\AssetsTrait`

```php
class Templater extends Fenom {
    use Fenom\AssetsTrait;
    /* ... */
}
```

### Usage

```php
$fenom->setAssetsCollector(true);
```

```smarty
{js "/path/to/script.js"}
{js cdn="jquery/2.1"}
{cdn "jquery-ui/2.1"}
{js cdn="bootstrap"}
{js "http://yandex.st/path/to/script.js"}
{js}
    tm.init(1000);
{/js}

{css src="/path/to/styles.css"}
{css}
div#content {
    color: black;
}
{/css}

{assets:js minify factor=6} flush all collected js scripts
{assets:css minify optimize factor=6} flush all styles
```