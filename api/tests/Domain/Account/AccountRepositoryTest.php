<?php

declare(strict_types=1);

namespace Tests\Domain\Account;

use App\Domain\DomainException\DomainStatementException;
use App\Domain\Account\Account;
use App\Domain\Account\AccountRepository;
use PDO;
use PDOStatement;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT_DAYJOB   = 'DayJob',
        ACCOUNT_PERSONAL = 'Personal'
    ;

    private $pdoProphecy;
    private $pdoStatementProphecy;
    private $accountRepository;

    public function setUp(): void
    {
        $this->pdoProphecy          = $this->prophesize(PDO::class);
        $this->pdoStatementProphecy = $this->prophesize(PDOStatement::class);
        $this->accountRepository    = new AccountRepository($this->pdoProphecy->reveal());
    }

    public function testAccountOfValue(): void
    {
        $this
            ->pdoProphecy
            ->prepare(AccountRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['account' => self::ACCOUNT_DAYJOB])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetch()
            ->willReturn(['account' => self::ACCOUNT_DAYJOB])
            ->shouldBeCalledOnce();

         $this->assertEquals(
            new Account(self::ACCOUNT_DAYJOB),
            $this->accountRepository->accountOfValue(self::ACCOUNT_DAYJOB)
        );
    }

    public function testListDistinctAccounts(): void
    {
        $this
            ->pdoProphecy
            ->prepare(AccountRepository::LIST)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute()
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetchAll()
            ->willReturn([
                ['account' => self::ACCOUNT_DAYJOB],
                ['account' => self::ACCOUNT_PERSONAL]
            ])
            ->shouldBeCalledOnce();

        $this->assertEquals(
            [new Account(self::ACCOUNT_DAYJOB), new Account(self::ACCOUNT_PERSONAL)],
            $this->accountRepository->listDistinctAccounts()
        );
    }
}
