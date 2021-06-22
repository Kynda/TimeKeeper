<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Time;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\HttpException\HttpValidationErrorException;
use App\Domain\Time\TimeValidator;
use App\Domain\Time\TimeValidationException;

class CreateTimeActionTest extends TimeActionTestCase
{
    public function testAction()
    {
        $this
            ->timeServiceProphecy
            ->createTimeResource($this->requestBody)
            ->willReturn($this->item());

        $request = $this->createRequest('POST', '/time', $this->requestBody);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            201,
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
                ->createTimeResource([])
                ->willThrow($e)
                ->shouldBeCalledOnce();

            $request = $this->createRequest('POST', '/time', []);
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
}
