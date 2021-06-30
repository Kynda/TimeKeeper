<?php

declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\Task;
use App\Domain\Task\TaskRecordNotFoundException;
use App\Domain\Task\TaskTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class TaskService
{
    /**
     * @var TaksRepository
     */
    private $taskRepository;

    /**
     * @param TaskRepository $taskRepository
     */
    public function __construct(
        TaskRepository $taskRepository
    ) {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return Collection
     */
    public function collectTasks(): Collection
    {
        return new Collection(
            $this->taskRepository->listDistinctTasks(),
            new TaskTransformer(),
            'task'
        );
    }

    /**
     * @param string $account
     * @return Collection
     */
    public function collectTasksInAccount(string $account): Collection
    {
        return new Collection(
            $this->taskRepository->listDistinctTasksInAccount($account),
            new TaskTransformer(),
            'task'
        );
    }

    /**
     * @param string $task
     * @return Item
     */
    public function findTaskResourceOfValue(string $task): Item
    {
        $task = $this->taskRepository->taskOfValue($task);

        if (!$task) {
            throw new TaskRecordNotFoundException();
        }

        return new Item($task, new TaskTransformer(), 'task');
    }
}
