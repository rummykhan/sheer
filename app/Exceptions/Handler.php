<?php

namespace App\Exceptions;

use Exception;

class Handler
{
    protected $trace = [];
    protected $exception = null;

    public function handle($exception)
    {
        $this->trace = array_reverse($exception->getTrace());
        $this->exception = $exception;

        include("../App/Exceptions/error.php");
    }
}