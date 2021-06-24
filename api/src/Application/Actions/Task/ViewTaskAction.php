<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class ViewTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        return $this->respondWithResource(
            $this->taskService->findTaskResourceOfValue(
                $this->resolveArg('task')
            )
        );
    }
}
