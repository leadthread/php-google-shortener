<?php

namespace LeadThread\GoogleShortener\Exceptions;

class GoogleAuthException extends GoogleException
{
    public function __construct($message = "INVALID_LOGIN", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}