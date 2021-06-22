<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\HttpException\HttpValidationErrorException;
use App\Domain\Time\TimeValidator;
use App\Domain\Time\TimeRecordNotFoundException;
use App\Domain\Time\TimeValidationException;
use Slim\Exception\HttpNotFoundException;

class UpdateTimeActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $this
            ->timeServiceProphecy
            ->updateTimeResourceOfId(self::ID, $this->requestBody)
            ->willReturn($this->item());

        $request = $this->createRequest('PUT', '/time/' . self::ID, $this->requestBody);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->item())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsTimeValidationException()
    {
        try {
            (new TimeValidator([]))->get();
        } catch (TimeValidationException $e) {
            $this
                ->timeServiceProphecy
                ->updateTimeResourceOfId(self::ID, [])
                ->willThrow($e)
                ->shouldBeCalledOnce();

            $request = $this->createRequest('PUT', '/time/' . self::ID, []);
            $response = $this->app->handle($request);

            $payload = (string)$response->getBody();

            $expectedError = new ActionError();
            $expectedError->addError(
                422,
                ActionError::VALIDATION_ERROR,
                (new HttpValidationErrorException($request))->getDescription()
            );
            $previous = $e;
            while ($previous) {
                $expectedError->addError(
                    422,
                    ActionError::VALIDATION_ERROR,
                    $previous->getMessage(),
                    '/data/attributes/' . $previous->getField()
                );
                $previous = $previous->getPrevious();
            }

            $expectedPayload = new ActionPayload(422, null, $expectedError);
            $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

            $this->assertEquals($serializedPayload, $payload);
        }
    }

    public function testActionThrowsTimeRecordNotFoundException()
    {
        $this
            ->timeServiceProphecy
            ->updateTimeResourceOfId(self::ID, $this->requestBody)
            ->willThrow(new TimeRecordNotFoundException())
            ->shouldBeCalledOnce();

        $request = $this->createRequesT('PUT', '/time/'.self::ID, $this->requestBody);
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
