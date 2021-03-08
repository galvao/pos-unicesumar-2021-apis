<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
    $app->get('/token', App\Handler\TokenHandler::class, 'token');
    $app->route(
        '/categoria', 
        App\Handler\CategoriaHandler::class, 
        ['GET', 'POST'],
        'listar-categorias'
    );
    $app->route(
        '/categoria/:id',
        App\Handler\CategoriaHandler::class,
        ['GET', 'PATCH', 'DELETE'],
        'operacoes-categoria'
    );
};
