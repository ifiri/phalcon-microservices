<?php

namespace App\Contracts;

interface Repository
{
    public function get(string $key);
}