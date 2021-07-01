<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Time\Time;

class ListTimeActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $this
            ->timeServiceProphecy
            ->collectTime()
            ->willReturn($this->timeCollection())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/time');
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->timeCollection())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
