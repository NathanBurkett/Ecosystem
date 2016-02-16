<?php

namespace RoadworkRah\Ecosystem\Contracts;

interface EcosystemContract
{
    public function getHeadScripts();
    public function getStylesheets();
    public function getFooterScripts();
    public function outputAssets($collection, $type);
    public function addAsset($collection, $name, $attr, $before);
    public function before($before, $collection, $name, $attr);
    public function addHeadScript($name, $attr = array(), $before = null);
    public function addStylesheet($name, $attr = array(), $before = null);
    public function addFooterScript($name, $attr = array(), $before = null);
}
