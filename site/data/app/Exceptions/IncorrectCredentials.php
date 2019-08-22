<?php

namespace App\Exceptions;

/**
 * This exception covers concrete case of error.
 * When provided user credentials (login and password)
 * are incorrect or empty, this exception should be thrown.
 */
class IncorrectCredentials extends ValidationException
{
    public $message = 'Incorrect or empty user credentials passed';
}