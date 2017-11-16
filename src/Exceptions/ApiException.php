<?php

namespace Postedin\Docode\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;

class ApiException extends Exception
{
    public $response;

    public function __construct($message = '', Response $response)
    {
        parent::__construct($message);

        $this->response = $response;
    }
}
