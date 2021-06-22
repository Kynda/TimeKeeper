<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionPayload;

class DeleteTimeActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $this
            ->timeServiceProphecy
            ->deleteTimeOfId(self::ID)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $request = $this->createRequest('DELETE', '/time/'.self::ID);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            204,
            ''
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
