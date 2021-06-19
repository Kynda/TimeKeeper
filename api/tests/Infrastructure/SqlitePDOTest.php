<?php

declare(strict_types=1);

namespace Tests\Infrastructure;

use App\Infrastructure\SqlitePDO;
use Tests\TestCase;

class SqlitePDOTest extends TestCase
{
    public function testSingleton()
    {
        $PDO1 = SqlitePDO::create(':memory:');
        $PDO2 = SqlitePDO::create(':memory:');

        $this->assertSame($PDO1, $PDO2);
    }
}
