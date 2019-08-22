<?php

namespace App\Contracts\Builder;

/**
 * Interface for builders that builds Phalcon Applications up
 */
interface ApplicationBuilder extends Builder
{
    public function buildRouting();
    public function buildViews();
}