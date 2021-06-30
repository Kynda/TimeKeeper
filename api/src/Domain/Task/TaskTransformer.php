<?php

declare(strict_types=1);

namespace App\Domain\Task;

use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    /**
     * @param Task $task
     * @return array
     */
    public function transform(Task $task)
    {
        return array_merge(
            $task->jsonSerialize(),
            ['id' => $task->getTask()]
        );
    }
}
