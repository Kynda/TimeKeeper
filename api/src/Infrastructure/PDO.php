<?php
declare(strict_types=1);

namespace App\Infrastructure;

use PDO as PhpPDO;

class PDO extends PhpPDO
{
    /**
     * @var PDO
     */
    private static $instance;

    /**
     * Singleton
     *
     * @param ?string $username
     * @param ?string $password
     * @param ?array $options
     */
    private function __construct(
        string $dsn ,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null
    ) {
        parent::__construct($dsn, $username, $password, $options);
    }

    /**
     * Create a new instance of PDO or return the previously created one.
     *
     * @param string $dsn
     * @param ?string $username
     * @param ?string $password
     * @param ?array $options
     */
    public static function create(
        string $dsn = null,
        ?string $username = null,
        ?string $password = null,
        ?array $options = null
    ) {
        if (self::$instance == null) {
            self::$instance = new PDO(
                $dsn,
                $username,
                $password,
                $options
            );
        }

        return self::$instance;
    }
}
