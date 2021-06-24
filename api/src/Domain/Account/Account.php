<?php

declare(strict_types=1);

namespace App\Domain\Account;

use JsonSerializable;

class Account implements JsonSerializable
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
     * @return Account
     */
    public function with(array $args): Account
    {
        return new self($args['account'] ?? $this->getAccount());
    }
}
