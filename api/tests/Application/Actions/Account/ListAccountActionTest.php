<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Account;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Account\Account;
use Tests\TestCase;

class ListAcountActionTest extends TestCase
{
    public function testAction()
    {
        $account= [
            new Account('DayJob'),
            new Account('Personal')
        ];

        $this
            ->accountServiceProphecy
            ->collectAccounts()
            ->willReturn($this->accountAccountCollection())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/account/account');
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->accountAccountCollection())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
