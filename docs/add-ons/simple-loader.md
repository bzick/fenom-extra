Simple loader
=============

Allow load modifiers and tags from specified directory like smarty.
The agreement to create plugins:
* Modifiers
    * File name: `modifier.{$modifier_name}.php`
    * Function name: `fenom_modifier_{$modifier_name}`
    * Arguments: `function ($value, ...) { ... }`
        * Variable value
        * ... other modifier arguments
    * Return: scalar
* Functions
    * File name: `function.{$function_name}.php`
    * Function name: `fenom_function_{$function_name}`
    * Arguments: `function (array $args) { ... }`
    * Return: scalar
* Smart Functions
    * File name: `function.smart.{$function_name}.php`
    * Function name: `fenom_function_{$function_name}`
    * Arguments: any. Fenom auto-detect all arguments for function.
    * Return: scalar
* Block Functions
    * File name: `function.block.{$function_name}.php`
    * Function name: `fenom_function_block_{$function_name}`
    * Arguments: `function ($content, array $args) { ... }`
        * Content of the block
        * All arguments
    * Return: scalar
* Compilers
    * File name: `compiler.{$compiler_name}.php`
    * Function name: `fenom_compiler_{$compiler_name}`
    * Arguments: `function (\Fenom\Tokenizer $tokens, \Fenom\Template $tpl) { ... }`
    * Return: PHP code
* Block Compilers
    * File name: `compiler.block.{$compiler_name}.php`
    * Function name:
        * Open tag: `fenom_compiler_{$compiler_name}`
        * Close tag: `fenom_compiler_{$compiler_name}_close`
    * Arguments: `function (\Fenom\Tokenizer $tokens, \Fenom\Scope $scope) { ... }`
    * Inner-block tags must be return in format: ```php
        return array(
            "tags" => array( // list of compilers for each inner-block tags
                "tag_name" => (callable) $compiler_name,
                // ...
            ),
            "floats" => array(  // which tags may by used in another block (e.g. {break} or {continue})
                "tag_name" => true, // or false
                // ...
            )
        );
    ```
    * Return: PHP code

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

```php
$fenom->addPluginsDir($dir);
```

### Fenom\Extra

`Fenom\Extra` by default configured to `lib/Plugins/` directory.