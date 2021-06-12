<?php

declare(strict_types=1);

namespace App\Application\Actions\Time;

use App\Application\Actions\ActionPayload;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteTimeAction extends TimeAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        $this->timeService->deleteTimeOfId(
            (int)$this->resolveArg('id')
        );
        return $this->respond(
            new ActionPayload(204, '')
        );
    }
}
