<?php
namespace Fenom;

class Extra extends \Fenom
{
    use StorageTrait, AssetsTrait, ReparserTrait, AccessorTrait, LoaderTrait, SimpleLoaderTrait;

    public function __construct($provider)
    {
        parent::__construct($provider);

        // AssetsTrait
        $this->setAssets(true);

        // AccessorTrait
        $this->setAccessorName('fenom');

        // SimpleLoaderTrait
        $this->addPluginsDir(FENOM_DEFAULT_PLUGINS);
    }
}