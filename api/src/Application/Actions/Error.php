<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class Error implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $description;

    /**
     * @var string|null
     */
    private $pointer;

    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $title;

    /**
     * @param int $status
     * @param string $title
     * @param ?string $description
     * @param ?string $pointer
     */
    public function __construct(
        int $status,
        string $title,
        ?string $description,
        ?string $pointer
    ) {
        $this->status      = $status;
        $this->title       = $title;
        $this->description = $description;
        $this->pointer     = $pointer;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $serialized = [
            'status'      => $this->status,
            'title'       => $this->title,
        ];

        if ($this->description) {
            $serialized['description'] = $this->description;
        }

        if ($this->pointer) {
            $serialized['source'] = [
                'pointer' => $this->pointer
            ];
        }

        return $serialized;
    }
}
