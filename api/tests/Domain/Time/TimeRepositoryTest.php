<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use PDO;
use PDOStatement;
use App\Domain\Time\Time;
use App\Domain\Time\TimeRepository;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class TimeRepositoryTest extends TestCase
{
    use ProphecyTrait;

    const
        ID       = 1,
        DATE     = '2021-01-01',
        START    = '13:00',
        END      = '14:00',
        HOURS    = 1.00,
        ACCOUNT  = 'Dayjob',
        TASK     = 'Ticket',
        BILLABLE = true
    ;

    private $pdoProphecy;
    private $pdoStatementProphecy;
    private $raw;
    private $timeRepository;

    public function setUp(): void
    {
        $this->raw = [
            'id'       => self::ID,
            'date'     => self::DATE,
            'start'    => self::START,
            'end'      => self::END,
            'hours'    => self::HOURS,
            'account'  => self::ACCOUNT,
            'task'     => self::TASK,
            'billable' => self::BILLABLE
        ];

        $this->pdoProphecy          = $this->prophesize(PDO::class);
        $this->pdoStatementProphecy = $this->prophesize(PDOStatement::class);
        $this->timeRepository       = new TimeRepository($this->pdo);
    }

    public function testTime(): void
    {
        $this->pdoStatementProphecy
            ->execute()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $this->pdoProphecy
            ->prepare()
            ->willReturn($this->pdoStatementProphecy)
            ->shouldBeCalledOnce();

        $expected = new Time(
            self::ID,
            self::DATE,
            self::START,
            self::END,
            self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::BILLABLE
        );

        $this->assertEquals($expected, $this->timeRepository->time(self::ID));
    }
}
