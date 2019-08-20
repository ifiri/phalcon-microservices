<?php

namespace App\Contracts\Builder;

interface ApplicationBuilder extends Builder
{
    public function buildRouting();
    public function buildViews();
}