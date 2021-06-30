<?php

declare(strict_types=1);

namespace App\Domain\Task;

use JsonSerializable;

class Task implements JsonSerializable
{
    /**
     * @var string
     */
    private $task;

    public function __construct(
        string $task
    ) {
        $this->task = $task;
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'task' => $this->getTask()
        ];
    }

    /**
     * @param array $args
     * @return Task
     */
    public function with(array $args): Task
    {
        return new self($args['task'] ?? $this->getTask());
    }
}
