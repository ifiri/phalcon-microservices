<?php

namespace App\Core\Application\Builder;

use App\Contracts;

/**
 * Build Director manage all steps of building process,
 * then returns final product.
 */
class Director implements Contracts\Builder\Director
{
    private $Builder;

    public function __construct(Contracts\Builder\Builder $Builder)
    {
        $this->Builder = $Builder;
    }

    /**
     * Entrypoint of Builder. Makes all building steps,
     * then returns final product.
     * 
     * @return object
     */
    public function build(): object
    {
        $this->Builder->buildDependencies();
        $this->Builder->buildRouting();
        $this->Builder->buildViews();

        return $this->Builder->getProduct();
    }
}