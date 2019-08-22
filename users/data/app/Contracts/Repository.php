<?php

namespace App\Contracts;

/**
 * Base interface for all repositories
 */
interface Repository
{
    public function get(string $key);
}