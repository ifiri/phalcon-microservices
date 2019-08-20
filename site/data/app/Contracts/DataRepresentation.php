<?php

namespace App\Contracts;

interface DataRepresentation
{
    public function getRepresentation();
    public function getOriginal();
}