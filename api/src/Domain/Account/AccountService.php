<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Account\Account;
use App\Domain\Account\AccountRecordNotFoundException;
use App\Domain\Account\AccountTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class AccountService
{
    private $accountRepository;

    public function __construct(
        AccountRepository $accountRepository
    ) {
        $this->accountRepository = $accountRepository;
    }

    public function collectAccounts(): Collection
    {
        return new Collection(
            $this->accountRepository->listDistinctAccounts(),
            new AccountTransformer(),
            'account'
        );
    }

    public function findAccountResourceOfValue(string $account): Item
    {
        $account = $this->accountRepository->accountOfValue($account);

        if (!$account) {
            throw new AccountRecordNotFoundException();
        }

        return new Item($account, new AccountTransformer(), 'account');
    }
}
