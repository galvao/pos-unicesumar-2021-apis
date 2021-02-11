<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;
use Mezzio\LaminasView\LaminasViewRenderer;
use Mezzio\Plates\PlatesRenderer;
use Mezzio\Router;
use Mezzio\Template\TemplateRendererInterface;
use Mezzio\Twig\TwigRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CategoriaHandler implements RequestHandlerInterface
{
    /** @var string */
    private $containerName;

    /** @var Router\RouterInterface */
    private $router;

    /** @var null|TemplateRendererInterface */
    private $template;
    
    private $dbAdapter;

    public function __construct(
        string $containerName,
        Router\RouterInterface $router,
        Adapter $dbAdapter,
        ?TemplateRendererInterface $template = null
    ) {
        $this->containerName = $containerName;
        $this->router        = $router;
        $this->template      = $template;
        $this->dbAdapter     = $dbAdapter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sql = new Sql($this->dbAdapter);
        $query = $sql->select('categoria');
        $stmt = $sql->prepareStatementForSqlObject($query);

        $dados = [];

        $recordSet = $stmt->execute();

        while ($registro = $recordSet->current()) {
            $dados[] = $registro;
            $recordSet->next();
        }

        return new JsonResponse([
            $dados
        ]);
    }
}
