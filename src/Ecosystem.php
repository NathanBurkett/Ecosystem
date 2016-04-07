<?php

namespace NathanBurkett\Ecosystem;

use NathanBurkett\Ecosystem\Contracts\EcosystemContract;
use NathanBurkett\Ecosystem\Contracts\AssetOutputContract;
use NathanBurkett\Ecosystem\Contracts\AssetCollectionContract;

class Ecosystem implements EcosystemContract
{
    /**
     * Asset colleciton manager
     * @var \NathanBurkett\Ecosystem\Contracts\AssetCollectionContract
     */
    protected $asset_manager;

    /**
     * Html builder
     * @var \NathanBurkett\Ecosystem\Contracts\HtmlOutputContract
     */
    protected $output;

    public function __construct(AssetCollectionContract $asset_manager, AssetOutputContract $output)
    {
        $this->asset_manager = $asset_manager;
        $this->output = $output;

        $this->asset_manager->loadDefaults();
    }

    /**
     * Front-facing method to output head scripts
     * @return string
     */
    public function getHeadScripts()
    {
        return $this->outputAssets($this->asset_manager->head_scripts, 'script');
    }

    /**
     * Front-facing method to output stylesheets
     * @return string
     */
    public function getStylesheets()
    {
        return $this->outputAssets($this->asset_manager->stylesheets, 'style');
    }

    /**
     * Front-facing method to output footer scripts
     * @return string
     */
    public function getFooterScripts()
    {
        return $this->outputAssets($this->asset_manager->footer_scripts, 'script');
    }

    /**
     * Output the collection assets
     * @param  \Illuminate\Support\Collection $collection
     * @param  string $type
     * @return string
     */
    public function outputAssets($collection, $type)
    {
        if (! $collection->isEmpty()) {
            $link = $this->determineLinkingAttribute($type);

            $output = array();

            foreach ($collection->all() as $name => $attr) {
                $output[$name] = $this->createAssetTag($type, $link, $attr);
            }

            return implode($output, '');
        }
    }

    /**
     * Create the html tag element
     * @param string $type
     * @param string $link
     * @param array $attr
     * @return string
     */
    protected function createAssetTag($type, $link, $attr)
    {
        $attr[$link] = $this->adaptIfLocalResource($attr[$link]);
        return $this->output->$type($attr);
    }

    /**
     * Check if file exists in current filesystem then append cache busting
     * @param  string $file
     * @return string
     */
    protected function adaptIfLocalResource($file)
    {
        $path = public_path($file);

        if (file_exists($path)) {
            return $this->devEnvironment() ? $file : $file . $this->appendCacheBusting($file, $path);
        } else {
            return $file;
        }
    }

    /**
     * Dynamically add an asset
     * @param string $collection collection name
     * @param string $name
     * @param path   $path
     * @param array  $attr
     */
    public function addAsset($collection, $name, $attr, $before)
    {
        if (!empty($before)) {
            $this->asset_manager->$collection = $this->before($collection, $name, $attr, $before);
        } else {
            $this->asset_manager->{$collection}->put($name, $attr);
        }
    }

    /**
     * Dynamically add an asset before another asset
     * @param  string $before
     * @param  string $collection name of collection adding to
     * @param  string $name       name of resource being added
     * @param  array  $attr       asset attributes
     * @return \Illuminate\Support\Collection
     */
    public function before($collection, $name, $attr, $before)
    {
        $collection = $this->asset_manager->$collection;

        if ($collection->has($before)) {
            $after = $collection->splice($collection->keys()->search($before));
            $collection->put($name, $attr);
            $collection = $collection->merge($after->toArray());
        }

        return $collection;
    }

    /**
     * Dynamically add script to head
     * @param string $name
     * @param string $path
     * @param array  $attr
     */
    public function addHeadScript($name, $attr = array(), $before = null)
    {

        $this->addAsset('head_scripts', $name, $attr, $before);
    }

    /**
     * Dynamically add stylesheet to head
     * @param string $name
     * @param string $path
     * @param array  $attr
     */
    public function addStylesheet($name, $attr = array(), $before = null)
    {
        $this->addAsset('stylesheets', $name, $attr, $before);
    }

    /**
     * Dynamically add script to footer
     * @param string $name
     * @param string $path
     * @param array  $attr
     */
    public function addFooterScript($name, $attr = array(), $before = null)
    {
        $this->addAsset('footer_scripts', $name, $attr, $before);
    }

    /**
     * Determine if asset requires src or href
     * @param  string $collection name of collection
     * @return string
     */
    protected function determineLinkingAttribute($collection)
    {
        return strpos($collection, 'script') !== false ? 'src' : 'href';
    }

    /**
     * Append a cache busting path to file src
     * @param  string $file
     * @param  string $path
     * @return string
     */
    protected function appendCacheBusting($file, $path)
    {
        return '?' . filemtime($path);
    }

    /**
     * Check if in a testing environment
     * @return mixed
     */
    protected function devEnvironment()
    {
        return array_search(app()->environment(), config('ecosystem.testing_envs')) !== false ? true : false;
    }
}
