<?php

declare(strict_types=1);

namespace App\Application\Actions\Time;

use App\Domain\Time\TimeRecordNotFoundException;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface as Response;

class ViewTimeAction extends TimeAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        $timeId = (int) $this->resolveArg('id');
        $time = $this->timeRepository->timeOfId($timeId);

        if (!$time) {
            throw new TimeRecordNotFoundException();
        }

        return $this->respondWithResource(
            new Item($time, new TimeTransformer(), 'time')
        );
    }
}
