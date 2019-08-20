<?php

namespace App\Core;

use App\Contracts;

use Phalcon\Config;

/**
 * Provides interface to getting resolved asset path.
 * It because it let encapsulate config structure
 * in one separate class. We should use it because
 * assets can be linked at multiple pages by
 * multiple controllers.
 */
class AssetsRepository implements Contracts\Repository
{
    public function __construct(Config $Config)
    {
        $this->Config = $Config->application;
    }

    public function get(string $asset)
    {
        return $this->Config->directories->assets . '/' . $asset;
    }
}