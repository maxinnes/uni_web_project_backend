<?php

class UserDoesNotExistException extends Exception{
    public function __construct($message = "This user does not exist.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}