<?php

namespace NathanBurkett\Ecosystem\Contracts;

interface AssetCollectionContract
{
    public function defaultHeadScripts();
    public function defaultStylesheets();
    public function defaultFooterScripts();
}
