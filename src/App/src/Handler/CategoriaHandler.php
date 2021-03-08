<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\EmptyResponse;
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
        $token = null;
        $method = $request->getMethod();
        $sql = new Sql($this->dbAdapter);

        $header = $request->getHeader('token');

        if (!$header) {
            return new JsonResponse(
                'Token de acesso não informado',
                401
            );
        }

        $token = $header[0];

        if ($token === null) {
            return new JsonResponse(
                'Token de acesso não informado',
                401
            );
        }

        if ($method == 'GET') {
            $query = $sql->select('categoria');

            if ($request->getAttribute('id') != null) {
                $query->where(['id' => $request->getAttribute('id')]);
                $stmt = $sql->prepareStatementForSqlObject($query);
                $recordSet = $stmt->execute();

                $registro = $recordSet->current();

                return new JsonResponse(
                    $registro
                );
            } else {
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
        } else if ($method == 'POST') {
            $data = json_decode($request->getBody()->getContents());

            $query = $sql->insert();
            $query->into('categoria');
            $query->columns(['nome']);
            $query->values([$data->nome]);

            $stmt = $sql->prepareStatementForSqlObject($query);

            $stmt->execute();

            $id = $this->dbAdapter->getDriver()->getConnection()->getLastGeneratedValue();

            return new JsonResponse(
                $id,
                201
            );
        } else if ($method == 'PATCH') {
            $id = $request->getAttribute('id');
            $data = json_decode($request->getBody()->getContents());

            $query = $sql->update('categoria');
            $query->set(['nome' => $data->nome]);
            $query->where(['id' => $id]);

            $stmt = $sql->prepareStatementForSqlObject($query);

            $stmt->execute();

            return new EmptyResponse(204);
        } else if ($method == 'DELETE') {
            $id = $request->getAttribute('id');

            $query = $sql->delete();
            $query->from('categoria');
            $query->where(['id' => $id]);

            $stmt = $sql->prepareStatementForSqlObject($query);

            $stmt->execute();

            return new EmptyResponse(204);
        }
    }
}
