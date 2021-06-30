<?php

declare(strict_types=1);

namespace App\Domain\Task;

use PDO;

class TaskRepository
{
    const SELECT = <<<QUERY
SELECT DISTINCT(task)
FROM time
WHERE users_id = 4
AND task= :task
LIMIT 1
QUERY;

    const LIST = <<<QUERY
SELECT DISTINCT(task)
FROM time
WHERE users_id = 4
ORDER BY task
QUERY;

    const LIST_BY_ACCOUNT = <<<QUERY
SELECT DISTINCT(task)
FROM time
WHERE
    users_id = 4
    AND account = :account
ORDER BY task
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
     * @return Task[]
     */
    public function listDistinctTasks(): array
    {
        $statement = $this->pdo->prepare(self::LIST);
        $statement->execute();
        $results = $statement->fetchAll();

        return array_map(function(array $raw): Task {
            return new Task($raw['task']);
        }, $results);
    }

    /**
     * @param string $account
     * @return Task[]
     */
    public function listDistinctTasksInAccount(string $account): array
    {
        $statement = $this->pdo->prepare(self::LIST_BY_ACCOUNT);
        $statement->execute(['account' => $account]);
        $results = $statement->fetchAll();

        return array_map(function(array $raw): Task {
            return new Task($raw['task']);
        }, $results);
    }

    /**
     * @param string $task
     * @return Task|null
     */
    public function taskOfValue(string $task): ?Task
    {
        $statement = $this->pdo->prepare(self::SELECT);
        $statement->execute(['task' => $task]);
        $raw = $statement->fetch();
        return $raw ? new Task($raw['task']) : null;
    }
}
