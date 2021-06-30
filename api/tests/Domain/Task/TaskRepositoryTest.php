<?php

declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\DomainException\DomainStatementException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepository;
use PDO;
use PDOStatement;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

class TaskRepositoryTest extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT       = 'Personal',
        TASK_SHOPPING = 'Shopping',
        TASK_CHORES   = 'Chores'
    ;

    private $pdoProphecy;
    private $pdoStatementProphecy;
    private $taskRepository;

    public function setUp(): void
    {
        $this->pdoProphecy          = $this->prophesize(PDO::class);
        $this->pdoStatementProphecy = $this->prophesize(PDOStatement::class);
        $this->taskRepository       = new TaskRepository($this->pdoProphecy->reveal());
    }

    public function testTaskOfValue(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TaskRepository::SELECT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['task' => self::TASK_SHOPPING])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetch()
            ->willReturn(['task' => self::TASK_SHOPPING])
            ->shouldBeCalledOnce();

         $this->assertEquals(
            new Task(self::TASK_SHOPPING),
            $this->taskRepository->taskOfValue(self::TASK_SHOPPING)
        );
    }

    public function testListDistinctTasks(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TaskRepository::LIST)
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
                ['task' => self::TASK_SHOPPING],
                ['task' => self::TASK_CHORES]
            ])
            ->shouldBeCalledOnce();

        $this->assertEquals(
            [new Task(self::TASK_SHOPPING), new Task(self::TASK_CHORES)],
            $this->taskRepository->listDistinctTasks()
        );
    }

    public function testListDistinctTasksInAccount(): void
    {
        $this
            ->pdoProphecy
            ->prepare(TaskRepository::LIST_BY_ACCOUNT)
            ->willReturn($this->pdoStatementProphecy->reveal())
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->execute(['account' => self::ACCOUNT])
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $this
            ->pdoStatementProphecy
            ->fetchAll()
            ->willReturn([
                ['task' => self::TASK_SHOPPING],
                ['task' => self::TASK_CHORES]
            ])
            ->shouldBeCalledOnce();

        $this->assertEquals(
            [new Task(self::TASK_SHOPPING), new Task(self::TASK_CHORES)],
            $this->taskRepository->listDistinctTasksInAccount(self::ACCOUNT)
        );
    }
}
