<?php

namespace App\Contracts;

/**
 * Base interface for all data-representing classes
 */
interface DataRepresentation
{
    /**
     * Should return representation of data, i.e. formatted data
     * 
     * @return mixed
     */
    public function getRepresentation();

    /**
     * Should return original data, i.e. non-formatted data
     * 
     * @return mixed
     */
    public function getOriginal();
}