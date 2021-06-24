<?php

declare(strict_types=1);

namespace Tests\Domain\Account;

use App\Domain\Account\Account;
use App\Domain\Account\AccountTransformer;
use App\Domain\Account\AccountRepository;
use App\Domain\Account\AccountRecordNotFoundException;
use App\Domain\Account\AccountService;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Prophecy\PhpUnit\ProphecyTrait;

use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT_DAYJOB   = 'DayJob',
        ACCOUNT_PERSONAL = 'Personal'
    ;

    private $repositoryProphecy;
    private $service;

    public function setUp(): void
    {
        $this->repositoryProphecy = $this->prophesize(AccountRepository::class);
        $this->service = new AccountService($this->repositoryProphecy->reveal());
    }

    public function testFindAccountResourceOfValue(): void
    {
        $this
            ->repositoryProphecy
            ->accountOfValue(self::ACCOUNT_DAYJOB)
            ->willReturn(new Account(self::ACCOUNT_DAYJOB))
            ->shouldBeCalledOnce();

        $expected = new Item(new Account(self::ACCOUNT_DAYJOB), new AccountTransformer(), 'account');

        $this->assertEquals($expected, $this->service->findAccountResourceOfValue(self::ACCOUNT_DAYJOB));
    }

    public function testFindAccountResourceOfValueNotFound(): void
    {
        $this->expectException(AccountRecordNotFoundException::class);

        $this
            ->repositoryProphecy
            ->accountOfValue(self::ACCOUNT_DAYJOB)
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $this->service->findAccountResourceOfValue(self::ACCOUNT_DAYJOB);
    }

    public function testCollectAccounts(): void
    {
        $accounts = [
            new Account(self::ACCOUNT_DAYJOB),
            new Account(self::ACCOUNT_PERSONAL),
        ];

        $this
            ->repositoryProphecy
            ->listDistinctAccounts()
            ->willReturn($accounts)
            ->shouldBeCalledOnce();

        $expected = new Collection($accounts, new AccountTransformer(), 'account');

        $this->assertEquals(
            $expected,
            $this->service->collectAccounts()
        );
    }
}
