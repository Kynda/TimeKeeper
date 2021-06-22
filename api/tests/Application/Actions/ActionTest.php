<?php
declare(strict_types=1);

namespace Tests\Application\Actions;

use App\Application\Actions\Action;
use App\Application\Actions\ActionPayload;
use DateTimeImmutable;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

class ActionTest extends TestCase
{
    public function testActionSetsHttpCodeInRespond()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);

        $testAction = new class($logger) extends Action {
            public function __construct(
                LoggerInterface $loggerInterface
            ) {
                parent::__construct($loggerInterface);
            }

            public function action(): Response
            {
                return $this->respond(
                    new ActionPayload(
                        202,
                        [
                            'willBeDoneAt' => (new DateTimeImmutable())->format(DateTimeImmutable::ATOM)
                        ]
                    )
                );
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);

        $this->assertEquals(202, $response->getStatusCode());
    }

   public function testActionSetsHttpCodeRespondResource()
    {
        $app = $this->getAppInstance();
        $container = $app->getContainer();
        $logger = $container->get(LoggerInterface::class);

        $testAction = new class($logger) extends Action {
            public function __construct(
                LoggerInterface $loggerInterface
            ) {
                parent::__construct($loggerInterface);
            }

            public function action(): Response
            {
                $resource = new \stdClass();
                $resource->id = 1;
                $resource->willBeDoneAt = (new DateTimeImmutable())->format(DateTimeImmutable::ATOM);
                $item = new Item(
                    $resource,
                    function(\stdClass $resource) {
                        return [
                            'id'           => $resource->id,
                            'willBeDoneAt' => $resource->willBeDoneAt,
                            'links'        => [
                                'self' => '/resource/'.$resource->id
                            ]
                        ];
                    }
                );

                return $this->respondWithResource($item, 202);
            }
        };

        $app->get('/test-action-response-code', $testAction);
        $request = $this->createRequest('GET', '/test-action-response-code');
        $response = $app->handle($request);

        $this->assertEquals(202, $response->getStatusCode());
   }
}
