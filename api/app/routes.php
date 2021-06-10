<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Domain\Time\TimeRepository;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/time/{id}',  function (Request $request, Response $response, array $args) {
        $timeRepository = $this->get(TimeRepository::class);
        $time = $timeRepository->timeOfId((int)$args['id']);

        if (!$time) {
            throw new \InvalidArgumentException("Time Not Found");
        }

        $manager = new Manager();
        $baseUrl = 'http://localhost';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));
        $resource = new Item($time, new TimeTransformer(), 'time');
        $json = json_encode(
            $manager->createData($resource)->toArray(),
            JSON_PRETTY_PRINT
        );
        $response->getBody()->write($json);

        return $response;
    });
};
