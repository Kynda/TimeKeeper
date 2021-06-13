<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use PDO;
use PDOStatement;
use App\Domain\DomainException\DomainStatementException;
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
        NOTES    = 'YOLO',
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
            'notes'    => self::NOTES,
            'billable' => self::BILLABLE
        ];

        $this->pdoProphecy          = $this->prophesize(PDO::class);
        $this->pdoStatementProphecy = $this->prophesize(PDOStatement::class);
        $this->timeRepository       = new TimeRepository($this->pdoProphecy->reveal());
    }

    public function testDeleteTimeOfId(): void
    {
        $this->pdoProphecy
            ->prepare(TimeRepository::DELETE)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->execute(['id' => self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->timeRepository->deleteTimeOfId(self::ID);
    }

    public function testTimeOfId(): void
    {
        $this->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->execute(['id' => self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $expected = new Time(
            self::ID,
            self::DATE,
            self::START,
            self::END,
            self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::NOTES,
            self::BILLABLE
        );

        $this->assertEquals(
            $expected,
            $this->timeRepository->timeOfId(self::ID)
        );
    }

    public function testCreateTime(): void
    {
        $this->pdoProphecy
            ->prepare(TimeRepository::INSERT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoProphecy
            ->lastInsertId()
            ->willReturn(self::ID)
            ->shouldBeCalledOnce();

        $raw = $this->raw;
        unset($raw['id']);
        $this->pdoStatementProphecy
            ->execute($raw)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->execute(['id' => self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $expected = new Time(
            self::ID,
            self::DATE,
            self::START,
            self::END,
            self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::NOTES,
            self::BILLABLE
        );

        $this->assertEquals(
            $expected,
            $this->timeRepository->createTime($raw)
        );
    }

    public function testSaveTime(): void
    {
        $this->pdoProphecy
            ->prepare(TimeRepository::UPDATE)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->execute($this->raw)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->execute(['id' => self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $expected = new Time(
            self::ID,
            self::DATE,
            self::START,
            self::END,
            self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::NOTES,
            self::BILLABLE
        );

        $this->assertEquals(
            $expected,
            $this->timeRepository->saveTime($expected)
        );
    }

}
