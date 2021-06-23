<?php

declare(strict_types=1);

namespace App\Domain\Time;

use JsonSerializable;

class TimeAccount implements JsonSerializable
{
    /**
     * @var string
     */
    private $account;

    public function __construct(
        string $account
    ) {
        $this->account = $account;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'account' => $this->getAccount()
        ];
    }

    /**
     * @param array $args
     * @return TimeAccount
     */
    public function with(array $args): TimeAccount
    {
        return new self($args['account'] ?? $this->getAccount());
    }
}
