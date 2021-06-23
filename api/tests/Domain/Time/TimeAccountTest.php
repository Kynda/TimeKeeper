<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\TimeAccount;

class TimeAccountTest extends TimeTestCase
{
    public function testJsonSerialize()
    {
        $expected = json_encode([
            'account' => self::ACCOUNT
        ]);

        $this->assertEquals($expected, json_encode(new TimeAccount(self::ACCOUNT)));
    }

    public function testWith()
    {
        $timeAccount = new TimeAccount(self::ACCOUNT);
        $timeAccountWithNewAccount = $timeAccount->with(['account' => 'Personal']);

        $this->assertEquals('Personal', $timeAccountWithNewAccount->getAccount());
        $this->assertEquals(self::ACCOUNT, $timeAccount->getAccount());
    }
}

