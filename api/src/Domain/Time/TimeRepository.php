<?php

declare(strict_types=1);

namespace App\Domain\Time;

use PDO;

class TimeRepository
{
    const DELETE = <<<QUERY
DELETE FROM time WHERE id = :id
QUERY;

    const INSERT =  <<<QUERY
INSERT INTO time (
    date,
    start,
    end,
    hours,
    account,
    task,
    notes,
    billable
)VALUES (
    :date,
    :start,
    :end,
    :hours,
    :account,
    :task,
    :notes,
    :billable
)
QUERY;

    const SELECT = <<<QUERY
SELECT * FROM time WHERE id = :id
QUERY;

    const UPDATE = <<<QUERY
UPDATE time SET
    date     = :date,
    start    = :start,
    end      = :end,
    hours    = :hours,
    account  = :account,
    task     = :task,
    notes    = :notes,
    billable = :billable
WHERE id = :id
QUERY;

    const LIST_ACCOUNTS = <<<QUERY
SELECT DISTINCT(account) FROM time WHERE users_id = 4 ORDER BY account
QUERY;

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
     * @param array $args
     * @return Time
     */
    public function createTime(array $args): Time
    {
        $statement = $this->pdo->prepare(self::INSERT);
        $statement->execute($args);
        return $this->timeOfId((int)$this->pdo->lastInsertId());
    }

    /**
     * @param int $id
     */
    public function deleteTimeOfId(int $id): bool
    {
        $statement = $this->pdo->prepare(self::DELETE);
        return $statement->execute(['id' => $id]);
    }

    public function listDistinctAccounts(): array
    {
        $statement = $this->pdo->prepare(self::LIST_ACCOUNTS);
        $statement->execute();
        $accounts = [];
        $results = $statement->fetchAll();

        return array_map(function(array $raw): TimeAccount {
            return new TimeAccount($raw['account']);
        }, $results);
    }

    /**
     * @param Time $time
     * @return Time
     */
    public function saveTime(Time $time): Time
    {
        $statement = $this->pdo->prepare(self::UPDATE);
        $statement->execute($time->jsonSerialize());
        return $this->timeOfId($time->getId());
    }

    /**
     * @param int $id
     * @return Time|null
     */
    public function timeOfId(int $id): ?Time
    {
        $statement = $this->pdo->prepare(self::SELECT);
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
}
