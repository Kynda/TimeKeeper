<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Task\TaskRecordNotFoundException;
use Slim\Exception\HttpNotFoundException;

class ViewTaskActionTest extends TaskActionTestCase
{
    public function testAction()
    {
        $this
            ->taskServiceProphecy
            ->findTaskResourceOfValue(self::TASK_SHOPPING)
            ->willReturn($this->taskItem())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/task/'.self::TASK_SHOPPING);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->taskItem())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsTaskRecordNotFoundException()
    {
        $this
            ->taskServiceProphecy
            ->findTaskResourceOfValue(self::TASK_SHOPPING)
            ->willThrow(new TaskRecordNotFoundException())
            ->shouldBeCalledOnce();

        $request = $this->createRequesT('GET', '/task/'.self::TASK_SHOPPING);
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
