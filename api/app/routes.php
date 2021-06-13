<?php
declare(strict_types=1);

use App\Application\Actions\Time\CreateTimeAction;
use App\Application\Actions\Time\DeleteTimeAction;
use App\Application\Actions\Time\ListTimeAccountAction;
use App\Application\Actions\Time\ListTimeAction;
use App\Application\Actions\Time\ListTimeTaskAction;
use App\Application\Actions\Time\UpdateTimeAction;
use App\Application\Actions\Time\ViewTimeAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });
    $app->get('/time/account', ListTimeAccountAction::class);
    $app->get('/time/task', ListTimeTaskAction::class);
    $app->get('/time', ListTimeAction::class);
    $app->get('/time/{id}', ViewTimeAction::class);
    $app->post('/time', CreateTimeAction::class);
    $app->put('/time/{id}', UpdateTimeAction::class);
    $app->delete('/time/{id}', DeleteTimeAction::class);
};
