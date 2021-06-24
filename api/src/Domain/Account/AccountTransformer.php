<?php

declare(strict_types=1);

namespace App\Domain\Account;

use League\Fractal\TransformerAbstract;

class AccountTransformer extends TransformerAbstract
{
    public function transform(Account $account)
    {
        return array_merge(
            $account->jsonSerialize(),
            ['id' => $account->getAccount()]
        );
    }
}
