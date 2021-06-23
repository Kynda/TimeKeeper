<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\TimeAccount;
use App\Domain\Time\TimeAccountTransformer;
use App\Domain\Time\TimeRepository;
use App\Domain\Time\TimeRecordNotFoundException;
use App\Domain\Time\TimeService;
use App\Domain\Time\TimeTransformer;
use App\Domain\Time\TimeValidator;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class TimeServiceTest extends TimeTestCase
{
    private $repositoryProphecy;
    private $service;

    public function setUp(): void
    {
        $this->repositoryProphecy = $this->prophesize(TimeRepository::class);
        $this->service = new TimeService($this->repositoryProphecy->reveal());
    }

    public function testCreateTimeResource(): void
    {
        $this
            ->repositoryProphecy
            ->createTime((new TimeValidator($this->request))->get())
            ->willReturn($this->time())
            ->shouldBeCalledOnce();

        $expected = new Item($this->time(), new TimeTransformer(), 'time');

        $this->assertEquals($expected, $this->service->createTimeResource($this->request));
    }

    public function testDeleteTimeOfId(): void
    {
        $this
            ->repositoryProphecy
            ->deleteTimeOfId(self::ID)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this->assertTrue($this->service->deleteTimeOfId((int)self::ID));
    }

    public function testFindTimeResourceOfId(): void
    {
        $this
            ->repositoryProphecy
            ->timeOfId(self::ID)
            ->willReturn($this->time())
            ->shouldBeCalledOnce();

        $expected = new Item($this->time(), new TimeTransformer(), 'time');

        $this->assertEquals($expected, $this->service->findTimeResourceOfId((int)self::ID));
    }

    public function testFindtimeResourceOfIdNotFound(): void
    {
        $this->expectException(TimeRecordNotFoundException::class);

        $this
            ->repositoryProphecy
            ->timeOfId(self::ID)
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $this->service->findTimeResourceOfId((int)self::ID);
    }

    public function testUpdateTimeResourceOfId(): void
    {
        $time = $this->time();

        $this
            ->repositoryProphecy
            ->timeOfId((int)self::ID)
            ->willReturn($time)
            ->shouldBeCalledOnce();
        $this
            ->repositoryProphecy
            ->saveTime($time)
            ->willReturn($time)
            ->shouldBeCalledOnce();

        $expected = new Item($time, new TimeTransformer(), 'time');

        $this->assertEquals(
            $expected,
            $this->service->updateTimeResourceOfId((int)self::ID, $this->request)
        );
    }

    public function testUpdateTimeResourceOfIdNotFound(): void
    {
        $this->expectException(TimeRecordNotFoundException::class);

        $this
            ->repositoryProphecy
            ->timeOfId((int)self::ID)
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $this->service->updateTimeResourceOfId((int)self::ID, $this->request);
    }

    public function testCollectTimeAccounts(): void
    {
        $accounts = [
            new TimeAccount('DayJob'),
            new TimeAccount('Personal')
        ];

        $this
            ->repositoryProphecy
            ->listDistinctAccounts()
            ->willReturn($accounts)
            ->shouldBeCalledOnce();

        $expected = new Collection($accounts, new TimeAccountTransformer(), 'account');

        $this->assertEquals(
            $expected,
            $this->service->collectTimeAccounts()
        );
    }
}
