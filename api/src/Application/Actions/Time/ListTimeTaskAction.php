<?php

declare(strict_types=1);

namespace App\Application\Actions\Time;

use Psr\Http\Message\ResponseInterface as Response;

class ListTimeTaskAction extends TimeAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        var_dump($this->request->getQueryParams()); die();

        return $this->respondWithResource(
            $this->timeService->findTimeResourceOfId(
                (int)$this->resolveArg('id')
            )
        );
    }
}
