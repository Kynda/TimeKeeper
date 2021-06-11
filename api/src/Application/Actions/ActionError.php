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
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string        $type
     * @param string|null   $description
     */
    public function __construct(string $type, ?string $description)
    {
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return self
     */
    public function setDescription(?string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $payload = [
            'type'        => $this->type,
            'description' => $this->description,
        ];

        return $payload;
    }
}