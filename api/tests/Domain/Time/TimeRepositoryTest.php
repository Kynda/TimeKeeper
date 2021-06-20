<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use PDO;
use PDOStatement;
use App\Domain\DomainException\DomainStatementException;
use App\Domain\Time\Time;
use App\Domain\Time\TimeRepository;

class TimeRepositoryTest extends TimeTestCase
{
    private $pdoProphecy;
    private $pdoStatementProphecy;
    private $timeRepository;

    public function setUp(): void
    {
        $this->pdoProphecy          = $this->prophesize(PDO::class);
        $this->pdoStatementProphecy = $this->prophesize(PDOStatement::class);
        $this->timeRepository       = new TimeRepository($this->pdoProphecy->reveal());
    }

    public function testDeleteTimeOfId(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TimeRepository::DELETE)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['id' => self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->timeRepository->deleteTimeOfId((int)self::ID);
    }

    public function testTimeOfId(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['id' => (int)self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

         $this->assertEquals(
            $this->time(),
            $this->timeRepository->timeOfId((int)self::ID)
        );
    }

    public function testCreateTime(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TimeRepository::INSERT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoProphecy
            ->lastInsertId()
            ->willReturn((int)self::ID)
            ->shouldBeCalledOnce();

        $raw = $this->raw;
        unset($raw['id']);
        $this
            ->pdoStatementProphecy
            ->execute($raw)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['id' => (int)self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $this->assertEquals(
            $this->time(),
            $this->timeRepository->createTime($raw)
        );
    }

    public function testSaveTime(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TimeRepository::UPDATE)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute($this->raw)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoProphecy
            ->prepare(TimeRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['id' => (int)self::ID])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetch()
            ->willReturn($this->raw)
            ->shouldBeCalledOnce();

        $this->assertEquals(
            $this->time(),
            $this->timeRepository->saveTime($this->time())
        );
    }
}
