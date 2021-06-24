<?php

declare(strict_types=1);

namespace Tests\Domain\Account;

use App\Domain\Account\Account;
use Tests\TestCase;

class AccountTest extends TestCase
{
    const
        ACCOUNT_DAYJOB   = 'DayJob',
        ACCOUNT_PERSONAL = 'Personal'
    ;

    public function testJsonSerialize()
    {
        $expected = json_encode([
            'account' => self::ACCOUNT_DAYJOB
        ]);

        $this->assertEquals($expected, json_encode(new Account(self::ACCOUNT_DAYJOB)));
    }

    public function testWith()
    {
        $timeAccount = new Account(self::ACCOUNT_DAYJOB);
        $timeAccountWithNewAccount = $timeAccount->with(['account' => self::ACCOUNT_PERSONAL]);

        $this->assertEquals(self::ACCOUNT_PERSONAL, $timeAccountWithNewAccount->getAccount());
        $this->assertEquals(self::ACCOUNT_DAYJOB, $timeAccount->getAccount());
    }
}

