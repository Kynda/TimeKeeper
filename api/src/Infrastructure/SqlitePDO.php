<?php
declare(strict_types=1);

namespace App\Infrastructure;

use PDO;

class SqlitePDO extends PDO
{
    /**
     * @var PDO
     */
    private static $instance;

    /**
     * Singleton
     *
     * @param string $dsn
     * @param ?array $options
     */
    private function __construct(
        string $dsn ,
        ?array $options = null
    ) {
        parent::__construct($dsn, null, null, $options);
    }

    /**
     * Create a new instance of PDO or return the previously created one.
     *
     * @param string $filename
     */
    public static function create(string $filename): PDO {
        if (self::$instance == null) {
            self::$instance = new self(
                'sqlite:' . $filename,
                [
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$instance;
    }
}
