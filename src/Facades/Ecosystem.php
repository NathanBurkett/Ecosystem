<?php

namespace RoadworkRah\Ecosystem\Facades;

use Illuminate\Support\Facades\Facade;

class Ecosystem extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ecosystem';
    }
}
