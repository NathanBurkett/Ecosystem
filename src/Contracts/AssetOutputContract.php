<?php

namespace NathanBurkett\Ecosystem\Contracts;

interface AssetOutputContract
{
    public function script($attributes = array());
    public function style($attributes = array());
}
