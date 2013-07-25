Simple loader
=============

Allow load modifiers and tags from specified directory like smarty.

### Setup

**Trait:** `Fenom\SimpleLoaderTrait`
**Depends:** [Fenom\LoaderTrait](./loader.md)

```php
class Templater extends Fenom {
    use Fenom\LoaderTrait;
    use Fenom\SimpleLoaderTrait;
    /* ... */
}
```

### Usage

### Fenom\Extra

`Fenom\Extra` by default configured to `plugins/` directory.