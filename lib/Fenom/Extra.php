<?php
namespace Fenom;

class Extra extends \Fenom {
    use StorageTrait;
    use AssetsTrait;
    use ReparserTrait;
    use AccessorTrait;
    use LoaderTrait;
    use SimpleLoaderTrait;

    public function __construct($provider) {
        parent::__construct($provider);

        // AssetsTrait
        $this->setAssets(true);

        // AccessorTrait
        $this->setAccessorName('fenom');

        // SimpleLoaderTrait
        $this->addPluginsDir(FENOM_DEFAULT_PLUGINS);
    }
}