<?php

declare(strict_types=1);

namespace App\Domain\Time;

use PDO;

class TimeRepository
{
    const
        SELECT_TIME = "SELECT * FROM time WHERE id = :id";

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * __construct
     *
     * @param PDO $pdo
     */
    public function __construct(
        PDO $pdo
    ) {
        $this->pdo = $pdo;
    }

    /**
     * @param Time $time
     */
    public function deleteTime(Time $time): void
    {

    }

    /**
     * @param int $id
     * @return Time|null
     */
    public function timeOfId(int $id): ?Time
    {
        $statement = $this->pdo->prepare(self::SELECT_TIME);
        $statement->execute(['id' => $id]);
        $raw = $statement->fetch();
        return $raw ? new Time(
            (int)$raw['id'],
            $raw['date'],
            $raw['start'],
            $raw['end'],
            (float)$raw['hours'],
            $raw['account'],
            $raw['task'],
            $raw['notes'],
            (bool)$raw['billable']
        ) : null;
    }

    /**
     * @param Time $time
     * @return Time
     */
    public function saveTime(Time $time): Time
    {
        $timeSave = $this->timeUpdate ??
            $this->pdo->prepare(self::SAVE_TIME);

        $this->timeSave = $timeSave;

        $timeSave->execute($time->jsonSerialize());
    }

    /**
     * @param Time $time
     * @return Time
     */
    public function updateTime(Time $time): Time
    {
        $timeUpdate = $this->timeUpdate ??
            $this->pdo->prepare(self::UPDATE_TIME);

        $this->timeUpdate = $timeUpdate;

        $timeUpdate->execute($time->jsonSerialize());

        return $time;
    }
}
