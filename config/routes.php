<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');
    $app->get('/detalhar-produto', App\Handler\ProdutoHandler::class, 'api.produto');
    $app->get('/listar-produtos', App\Handler\ProdutoHandler::class, 'api.produtos');
    $app->get('/detalhar-categoria', App\Handler\CategoriaHandler::class, 'api.categoria');
    $app->delete('/excluir-categoria', App\Handler\CategoriaHandler::class, 'api.categoria.excluir');
    $app->post('/cadastrar-categoria', App\Handler\CategoriaHandler::class, 'api.categoria.cadastrar');
    $app->patch('/alterar-categoria', App\Handler\CategoriaHandler::class, 'api.categoria.alterar');
    $app->get('/listar-categorias', App\Handler\CategoriaHandler::class, 'api.categorias');
};
