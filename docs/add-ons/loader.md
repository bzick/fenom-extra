Loader
======

Allow add yours loaders for modifiers and tags.

### Setup

**Trait:** `Fenom\LoaderTrait`

```php
class Templater extends Fenom {
    use Fenom\LoaderTrait;
    /* ... */
}
```

### Usage

```php
$fenom->addModifierLoader(function ($modifier, $template) {
    /* @var string $modifier */
    /* @var \Fenom\Template|null $template */

    // modifier's load rule
});

$fenom->addTagLoader(function ($tag, $template) {
    /* @var string $tag */
    /* @var \Fenom\Template|null $template */

    // tag's load rule
});
```