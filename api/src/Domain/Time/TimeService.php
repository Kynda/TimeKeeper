<?php

declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\Time\TimeRecordNotFoundException;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class TimeService
{
    /**
     * @var TimeRepository $timeRepository
     */
    private $timeRepository;

    /**
     * @param TimeRepository $timeRepository
     */
    public function __construct(
        TimeRepository $timeRepository
    ) {
        $this->timeRepository = $timeRepository;
    }

    public function collectTime(): Collection
    {
        return new Collection(
            $this->timeRepository->listTime(),
            new TimeTransformer(),
            'time'
        );
    }

    /**
     * @param array $args
     * @return Item
     */
    public function createTimeResource(array $args): Item
    {
        $time = $this->timeRepository->createTime(
            (new TimeValidator($args))->get()
        );

        return new Item($time, new TimeTransformer(), 'time');
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteTimeOfId(int $id): bool
    {
        return $this->timeRepository->deleteTimeOfId($id);
    }

    /**
     * @param int $id
     * @return ITem
     */
    public function findTimeResourceOfId(int $id): Item
    {
        $time = $this->timeRepository->timeOfId($id);

        if (!$time) {
            throw new TimeRecordNotFoundException();
        }

        return new Item($time, new TimeTransformer(), 'time');
    }

    /**
     * @param int $id
     * @param array $args
     * @return Item
     */
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
