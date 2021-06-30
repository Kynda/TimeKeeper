<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Task\Task;
use App\Domain\Task\TaskTask;
use App\Domain\Task\TaskTransformer;
use App\Domain\Task\TaskService;
use Prophecy\PhpUnit\ProphecyTrait;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

abstract class TaskActionTestCase extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT       = 'Personal',
        TASK_SHOPPING = 'Shopping',
        TASK_CHORES   = 'Chores'
        ;

    protected $app;
    protected $taskServiceProphecy;

    protected $requestBody = [
        'task' => self::TASK_SHOPPING
    ];

    protected function taskItem(): Item
    {
        return new Item(
            new Task(self::TASK_SHOPPING),
            new TaskTransformer(),
            'task'
        );
    }

    protected function taskCollection(): Collection
    {
        return new Collection(
            new Task(self::TASK_SHOPPING),
            new Task(self::TASK_CHORES)
        );
    }

    protected function resourceArray(ResourceInterface $resource): array
    {
        $manager = new Manager();
        $manager->setSerializer(new JsonApiSerializer());
        return $manager->createData($resource)->toArray();
    }

    public function setUp(): void
    {
        $this->taskServiceProphecy = $this->prophesize(TaskService::class);
        $this->app = $this->getAppInstance();
        $this
            ->app
            ->getContainer()
            ->set(TaskService::class, $this->taskServiceProphecy->reveal());

        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware =  new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
        $this->app->add($errorMiddleware);
    }
}
