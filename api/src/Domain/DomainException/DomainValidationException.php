<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class DomainValidationException extends DomainException
{
    /**
     * @var int
     */
    protected $code = 422;

    const
        MISSING_REQUIRED_FIELD = "The %s field is required",
        INVALID_TYPE = "The %s field must be parseable into a %s"
    ;

    /**
     * @var string|null
     */
    private $field;

    public function __construct(
        string $message,
        ?string $field = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->field = $field;
    }

    public function getField(): ?string
    {
        return $this->field;
    }
}

