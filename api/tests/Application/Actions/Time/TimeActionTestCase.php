<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Time\Time;
use App\Domain\Time\TimeAccount;
use App\Domain\Time\TimeTransformer;
use App\Domain\Time\TimeService;
use Prophecy\PhpUnit\ProphecyTrait;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

abstract class TimeActionTestCase extends TestCase
{
    use ProphecyTrait;

    protected $app;
    protected $timeServiceProphecy;

    const
        ID       = '1',
        DATE     = '2021-01-01',
        START    = '13:00',
        END      = '14:00',
        HOURS    = '1.00',
        ACCOUNT  = 'Dayjob',
        TASK     = 'Ticket',
        NOTES    = 'Code Review',
        BILLABLE = true
    ;

    protected $requestBody = [
        'date'     => self::DATE,
        'start'    => self::START,
        'end'      => self::END,
        'hours'    => self::HOURS,
        'account'  => self::ACCOUNT,
        'task'     => self::TASK,
        'notes'    => self::NOTES,
        'billable' => self::BILLABLE,
        'extra'    => 'Extra submitted field'
    ];

    protected function timeItem(): Item
    {
        return new Item(
            new Time(
                (int)self::ID,
                self::DATE,
                self::START,
                self::END,
                (float)self::HOURS,
                self::ACCOUNT,
                self::TASK,
                self::NOTES,
                self::BILLABLE
            ),
            new TimeTransformer(),
            'time'
        );
    }

    protected function timeAccountCollection(): Collection
    {
        return new Collection(
            new TimeAccount('DayJob'),
            new TimeAccount('Personal')
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
        $this->timeServiceProphecy = $this->prophesize(TimeService::class);
        $this->app = $this->getAppInstance();
        $this
            ->app
            ->getContainer()
            ->set(TimeService::class, $this->timeServiceProphecy->reveal());

        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware =  new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
        $this->app->add($errorMiddleware);
    }
}
