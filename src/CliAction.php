<?php
declare(strict_types=1);

namespace adrianfalleiro\SlimCliRunner;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class CliAction
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     * @return Response
     * @throws CliException
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (Exception $e) {
            throw new CliException($e->getMessage());
        }
    }

    /**
     * @return Response
     * @throws CliException
     */
    abstract protected function action(): Response;

    /**
     * @param  string $name
     * @return mixed
     * @throws CliException
     */
    protected function resolveArg(int $index)
    {
        if (!isset($this->args[$index])) {
            throw new CliException("Could not resolve arg for index {$index}");
        }

        return $this->args[$index];
    }

    /**
     * @param string $message
     * @return Response
     */
    protected function logToConsole(string $message): void
    {
        $this->response->getBody()->write($message . PHP_EOL);
    }

    /**
     * @param string $payload
     * @return Response
     */
    protected function respond(string $message = ''): Response
    {
        $this->response->getBody()->write($message);
        $this->response->withStatus(200);

        return $this->response;
    }
}
