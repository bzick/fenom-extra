<?php
namespace Fenom;

class Extra extends \Fenom
{
    use StorageTrait, AssetsTrait, LoaderTrait, SimpleLoaderTrait;

    public function __construct($provider)
    {
        parent::__construct($provider);

        // AssetsTrait
        $this->setAssetsBehavior(true);

        // SimpleLoaderTrait
        $this->addPluginsDir(FENOM_DEFAULT_PLUGINS);
    }
}