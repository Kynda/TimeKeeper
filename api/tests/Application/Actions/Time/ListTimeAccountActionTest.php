<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Time\TimeAccount;

class ListTimeAcountActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $timeAccounts = [
            new TimeAccount('DayJob'),
            new TimeAccount('Personal')
        ];

        $this
            ->timeServiceProphecy
            ->collectTimeAccounts()
            ->willReturn($this->timeAccountCollection())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/time/account');
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->timeAccountCollection())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
