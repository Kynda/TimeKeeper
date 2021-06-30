<?php

declare(strict_types=1);

namespace App\Domain\Account;

use PDO;

class AccountRepository
{
    const SELECT = <<<QUERY
SELECT DISTINCT(account)
FROM time
WHERE users_id = 4
AND account= :account
LIMIT 1
QUERY;

    const LIST = <<<QUERY
SELECT DISTINCT(account)
FROM time
WHERE users_id = 4
ORDER BY account
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
     * @return Account[]
     */
    public function listDistinctAccounts(): array
    {
        $statement = $this->pdo->prepare(self::LIST);
        $statement->execute();
        $results = $statement->fetchAll();

        return array_map(function(array $raw): Account {
            return new Account($raw['account']);
        }, $results);
    }

    /**
     * @param string $account
     * @return Account|null
     */
    public function accountOfValue(string$account): ?Account
    {
        $statement = $this->pdo->prepare(self::SELECT);
        $statement->execute(['account' => $account]);
        $raw = $statement->fetch();
        return $raw ? new Account($raw['account']) : null;
}
}
