<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Account;

use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Account\Account;
use App\Domain\Account\AccountAccount;
use App\Domain\Account\AccountTransformer;
use App\Domain\Account\AccountService;
use Prophecy\PhpUnit\ProphecyTrait;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

abstract class AccountActionTestCase extends TestCase
{
    use ProphecyTrait;

    const
        ACCOUNT_DAYJOB   = 'DayJob',
        ACCOUNT_PERSONAL = 'Personal'
        ;

    protected $app;
    protected $accountServiceProphecy;

    protected $requestBody = [
        'account' => self::ACCOUNT_DAYJOB
    ];

    protected function accountItem(): Item
    {
        return new Item(
            new Account(self::ACCOUNT_DAYJOB),
            new AccountTransformer(),
            'account'
        );
    }

    protected function accountCollection(): Collection
    {
        return new Collection(
            new Account(self::ACCOUNT_DAYJOB),
            new Account(self::ACCOUNT_PERSONAL)
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
        $this->accountServiceProphecy = $this->prophesize(AccountService::class);
        $this->app = $this->getAppInstance();
        $this
            ->app
            ->getContainer()
            ->set(AccountService::class, $this->accountServiceProphecy->reveal());

        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware =  new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
        $this->app->add($errorMiddleware);
    }
}
