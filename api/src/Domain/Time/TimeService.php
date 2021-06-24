<?php

declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\Time\TimeRecordNotFoundException;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class TimeService
{
    private $timeRepository;

    public function __construct(
        TimeRepository $timeRepository
    ) {
        $this->timeRepository = $timeRepository;
    }

    public function createTimeResource(array $args): Item
    {
        $time = $this->timeRepository->createTime(
            (new TimeValidator($args))->get()
        );

        return new Item($time, new TimeTransformer(), 'time');
    }

    public function deleteTimeOfId(int $id): bool
    {
        return $this->timeRepository->deleteTimeOfId($id);
    }

    public function findTimeResourceOfId(int $id): Item
    {
        $time = $this->timeRepository->timeOfId($id);

        if (!$time) {
            throw new TimeRecordNotFoundException();
        }

        return new Item($time, new TimeTransformer(), 'time');
    }

    public function updateTimeResourceOfId(int $id, array $args): Item
    {
        $time = $this->timeRepository->timeOfId($id);

        if (!$time) {
            throw new TimeRecordNotFoundException();
        }

        $time = $time->with(
            (new TimeValidator($args))->get()
        );

        $time = $this->timeRepository->saveTime($time);

        return new Item($time, new TimeTransformer(), 'time');
    }
}
