<?php
declare(strict_types=1);

use App\Domain\Time\TimeRepository;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        TimeRepository::class => function (ContainerInterface $c) {
            return new TimeRepository(
                $c->get(PDO::class)
            );
        }
    ]);
};
