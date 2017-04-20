<?php

namespace LeadThread\GoogleShortener\Exceptions;

class GoogleRateLimitException extends GoogleException
{
    public function __construct($message = "RATE_LIMIT_EXCEEDED", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}