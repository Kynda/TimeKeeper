<?php

declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\Task\Task;
use App\Domain\Task\TaskTransformer;
use App\Domain\Task\TaskRepository;
use App\Domain\Task\TaskRecordNotFoundException;
use App\Domain\Task\TaskService;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Prophecy\PhpUnit\ProphecyTrait;

use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT       = 'Personal',
        TASK_SHOPPING = 'Shopping',
        TASK_CHORES   = 'Chores'
    ;

    private $repositoryProphecy;
    private $service;

    public function setUp(): void
    {
        $this->repositoryProphecy = $this->prophesize(TaskRepository::class);
        $this->service = new TaskService($this->repositoryProphecy->reveal());
    }

    public function testFindTaskResourceOfValue(): void
    {
        $this
            ->repositoryProphecy
            ->taskOfValue(self::TASK_SHOPPING)
            ->willReturn(new Task(self::TASK_SHOPPING))
            ->shouldBeCalledOnce();

        $expected = new Item(new Task(self::TASK_SHOPPING), new TaskTransformer(), 'task');

        $this->assertEquals($expected, $this->service->findTaskResourceOfValue(self::TASK_SHOPPING));
    }

    public function testFindTaskResourceOfValueNotFound(): void
    {
        $this->expectException(TaskRecordNotFoundException::class);

        $this
            ->repositoryProphecy
            ->taskOfValue(self::TASK_SHOPPING)
            ->willReturn(null)
            ->shouldBeCalledOnce();

        $this->service->findTaskResourceOfValue(self::TASK_SHOPPING);
    }

    public function testCollectTasks(): void
    {
        $tasks = [
            new Task(self::TASK_SHOPPING),
            new Task(self::TASK_CHORES),
        ];

        $this
            ->repositoryProphecy
            ->listDistinctTasks()
            ->willReturn($tasks)
            ->shouldBeCalledOnce();

        $expected = new Collection($tasks, new TaskTransformer(), 'task');

        $this->assertEquals(
            $expected,
            $this->service->collectTasks()
        );
    }

    public function testCollectTasksInAccount(): void
    {
        $tasks = [
            new Task(self::TASK_SHOPPING),
            new Task(self::TASK_CHORES),
        ];

        $this
            ->repositoryProphecy
            ->listDistinctTasksInAccount(self::ACCOUNT)
            ->willReturn($tasks)
            ->shouldBeCalledOnce();

        $expected = new Collection($tasks, new TaskTransformer(), 'task');

        $this->assertEquals(
            $expected,
            $this->service->collectTasksInAccount(self::ACCOUNT)
        );
    }

}
