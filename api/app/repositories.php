<?php
declare(strict_types=1);

use App\Domain\Account\AccountRepository;
use App\Domain\Task\TaskRepository;
use App\Domain\Time\TimeRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        AccountRepository::class => function (ContainerInterface $c) {
            return new AccountRepository(
                $c->get(PDO::class)
            );
        },
        TaskRepository::class => function (ContainerInterface $c) {
            return new TaskRepository(
                $c->get(PDO::class)
            );
        },
        TimeRepository::class => function (ContainerInterface $c) {
            return new TimeRepository(
                $c->get(PDO::class)
            );
        },
    ]);
};
