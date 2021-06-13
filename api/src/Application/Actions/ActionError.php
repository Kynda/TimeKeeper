<?php
declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionError implements JsonSerializable
{
    public const BAD_REQUEST             = 'Bad Request';
    public const INSUFFICIENT_PRIVILEGES = 'Insufficient Privileges';
    public const NOT_ALLOWED             = 'Not Allowed';
    public const NOT_IMPLEMENTED         = 'Not Implemented';
    public const RESOURCE_NOT_FOUND      = 'Resource Not Found';
    public const SERVER_ERROR            = 'Server Error';
    public const UNAUTHENTICATED         = 'Unauthenticated';
    public const VALIDATION_ERROR        = 'Validation Error';
    public const VERIFICATION_ERROR      = 'Verification Error';

    /**
     * @var Error[]
     */
    private $errors = [];

    /**
     * @param int $statusCode
     * @param string $title
     * @param string $description
     * @param ?string $pointer
     */
    public function addError(
        int $statusCode,
        string $title,
        ?string $description = null,
        ?string $pointer = null
    ): self
    {
        $this->errors[] = new Error(
            $statusCode,
            $title,
            $description,
            $pointer
        );

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function(Error $error): array {
            return $error->jsonSerialize();
        }, $this->errors);
    }
}
