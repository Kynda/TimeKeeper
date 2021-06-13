<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\HttpException\HttpValidationErrorException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $actionError = new ActionError();

        list($statusCode, $title, $description, $ptr) =
            $this->unpackException($exception);

        $actionError->addError(
            $statusCode,
            $title,
            $description,
            $ptr
        );

        $previous = $exception->getPrevious();
        while ($previous) {
            list($status, $title, $description, $ptr) = $this->unpackException($previous);
            $actionError->addError(
                $status,
                $title,
                $description,
                $ptr
            );
            $previous = $previous->getPrevious();
        }

        $payload = new ActionPayload($statusCode, null, $actionError);
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function unpackException(\Exception $exception): array
    {
        switch(true) {
            case $exception instanceof HttpNotFoundException:
                $title = ActionError::RESOURCE_NOT_FOUND;
            break;
            case $exception instanceof HttpMethodNotAllowedException:
                $title = ActionError::NOT_ALLOWED;
            break;
            case $exception instanceof HttpUnauthorizedException:
                $title = ActionError::UNAUTHENTICATED;
            break;
            case $exception instanceof HttpForbiddenException:
                $title = ActionError::INSUFFICIENT_PRIVILEGES;
            break;
            case $exception instanceof HttpBadRequestException:
                $title = ActionError::BAD_REQUEST;
            break;
            case $exception instanceof HttpNotImplementedException:
                $title = ActionError::NOT_IMPLEMENTED;
            break;
            case $exception instanceof HttpValidationErrorException:
            case $exception->getCode() === 422:
                $title = ActionError::VALIDATION_ERROR;
            break;
            default:
                $title = ActionError::SERVER_ERROR;
            break;
        }

        $statusCode = $exception->getCode() === 0 ? 500 : $exception->getCode();
        $description = $exception instanceof HttpException ?
            $exception->getDescription() : $exception->getMessage();

        $ptr = null;
        if (method_exists($exception, 'getField') && $exception->getField()) {
            $ptr = '/data/attributes/' . $exception->getField();
        }

        return [$statusCode, $title, $description, $ptr];
    }
}
