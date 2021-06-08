<?php

declare(strict_types=1);

namespace App\Domain\Time;

use PDO;

class TimeRepository
{
    const
        SELECT_TIME = "SELECT * FROM Time WHERE id = :id";

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var PDOStatement|null
     */
    private $timeSelect;

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
     * @param int $id
     * @return Time|null
     */
    public function time(int $id): ?Time
    {
        $timeSelect = $this->timeSelect ??
            $this->pdo->prepare(self::SELECT_TIME);

        $this->timeSelect = $timeSelect;

        $timeSelect->execute(['id' => $id]);
        $raw = $timeSelect->fetch(PDO::FETCH_ASSOC);

        return $raw ? new Time(
            $raw['id'],
            $raw['date'],
            $raw['start'],
            $raw['end'],
            $raw['hours'],
            $raw['account'],
            $raw['task'],
            $raw['notes'],
            $raw['billable']
        ) : null;
   }
}
