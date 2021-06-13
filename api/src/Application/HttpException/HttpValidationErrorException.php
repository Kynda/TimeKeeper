<?php

declare(strict_types=1);

namespace App\Application\HttpException;

use Slim\Exception\HttpSpecializedException;

class HttpValidationErrorException extends HttpSpecializedException
{
    /**
     * @var int
     */
    protected $code = 422;

    /**
     * @var string
     */
    protected $message = "Unprocessable Entity.";

    protected $title = "422 Unprocessable Entity";
    protected $description = "The server understood the request, but cannot complete it due to an apparent client error.";
}
