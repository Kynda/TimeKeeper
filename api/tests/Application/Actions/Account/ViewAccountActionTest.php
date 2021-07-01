<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Account;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Domain\Account\AccountRecordNotFoundException;
use Slim\Exception\HttpNotFoundException;

class ViewAccountActionTest extends AccountActionTestCase
{
    public function testAction()
    {
        $this
            ->accountServiceProphecy
            ->findAccountResourceOfValue(self::ACCOUNT_PERSONAL)
            ->willReturn($this->accountItem())
            ->shouldBeCalledOnce();

        $request = $this->createRequest('GET', '/account/'.self::ACCOUNT_PERSONAL);
        $response = $this->app->handle($request);

        $payload = (string)$response->getBody();

        $expectedPayload = new ActionPayload(
            200,
            $this->resourceArray($this->accountItem())
        );
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsAccountRecordNotFoundException()
    {
        $this
            ->accountServiceProphecy
            ->findAccountResourceOfValue(self::ACCOUNT_PERSONAL)
            ->willThrow(new AccountRecordNotFoundException())
            ->shouldBeCalledOnce();

        $request = $this->createRequesT('GET', '/account/'.self::ACCOUNT_PERSONAL);
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
