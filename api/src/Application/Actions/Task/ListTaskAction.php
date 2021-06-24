<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class ListTaskAction extends TimeAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        return $this->respondWithResource(
            $this->taskService->collectTasks(
                (int)$this->resolveArg('id')
            )
        );
    }
}
