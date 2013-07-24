Assets
======

Declare the tags `{js}` and `{css}` for JavaScript and CSS static files.

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