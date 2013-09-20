<?php

namespace Fenom;


trait AssetsTrait
{
    /**
     * @var
     */
    private $_assets_root;
    /**
     * @var int
     */
    private $_assets_behavior = 0;

    public function setAssetsBehavior($behavior, $root = null)
    {
        $this->_assets_root = $root;
        $this->_assets_behavior = $behavior;
        /* @var \Fenom $this */
        $this->addBlockCompiler('js', 'Fenom\Assets\Tags::jsOpen', 'Fenom\Assets\Tags::jsClose');
        $this->addBlockCompiler('css', 'Fenom\Assets\Tags::cssOpen', 'Fenom\Assets\Tags::cssClose');
        $this->addCompiler('assets', 'Fenom\Assets\Tags::tagAssets');
    }

    public function setPackJS()
    {

    }

    public function addJS($url)
    {

    }

    public function addJSCode()
    {

    }

    public function addCSS()
    {

    }

    public function addCSSCode()
    {

    }

    public function resetAssets()
    {

    }

    public function getAssets()
    {

    }
}