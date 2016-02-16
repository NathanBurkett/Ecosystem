<?php

namespace RoadworkRah\Ecosystem\Entities;

use Illuminate\Support\Collection;

abstract class AbstractEcosystem
{
    /**
    * Ecosystem head_scripts
    * @var \Illuminate\Support\Collection
    */
    public $head_scripts;

    /**
    * Ecosystem stylesheets
    * @var \Illuminate\Support\Collection
    */
    public $stylesheets;

    /**
    * Ecosystem footer_scripts
    * @var \Illuminate\Support\Collection
    */
    public $footer_scripts;

    /**
     * Set all default assets
     * @return void
     */
    public function loadDefaults()
    {
        foreach (['head_scripts', 'stylesheets', 'footer_scripts'] as $member) {
            $method = "default" . studly_case($member);
            $this->$member = new Collection($this->$method());
        }
    }

    /**
     * Array of default head scripts
     * @return array
     */
    abstract public function defaultHeadScripts();

    /**
     * Array of default stylesheets
     * @return array
     */
    abstract public function defaultStylesheets();

    /**
     * Array of default footer scripts
     * @return array
     */
    abstract public function defaultFooterScripts();
}
