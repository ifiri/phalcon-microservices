<?php

namespace App\Exceptions;

class IncorrectCredentials extends ValidationException
{
    public $message = 'Incorrect or empty user credentials passed';
}