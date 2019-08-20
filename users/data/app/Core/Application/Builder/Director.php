<?php

namespace App\Core\Application\Builder;

use App\Contracts;

class Director implements Contracts\Builder\Director
{
    private $Builder;

    public function __construct(Contracts\Builder\Builder $Builder)
    {
        $this->Builder = $Builder;
    }

    public function build()
    {
        $this->Builder->buildDependencies();
        $this->Builder->buildRouting();
        $this->Builder->buildViews();

        return $this->Builder->getProduct();
    }
}