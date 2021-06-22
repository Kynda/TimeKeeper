<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Time\TimeRecordNotFoundException;
use Slim\Exception\HttpNotFoundException;

class ViewTimeActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $this
            ->timeServiceProphecy
            ->findTimeResourceOfId(self::ID)
            ->willReturn($this->item())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/time/'.self::ID);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->item())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsTimeRecordNotFoundException()
    {
        $this
            ->timeServiceProphecy
            ->findTimeResourceOfId(self::ID)
            ->willThrow(new TimeRecordNotFoundException())
            ->shouldBeCalledOnce();

        $request = $this->createRequesT('GET', '/time/'.self::ID);
        $response = $this->app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError();
        $expectedError->addError(
            404,
            ActionError::RESOURCE_NOT_FOUND,
            (new HttpNotFoundException($request))->getDescription()
        );
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
