<?php
declare(strict_types=1);

namespace adrianfalleiro\SlimCliRunner;

use DI\Container;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use RuntimeException;
use ReflectionClass;
use ReflectionMethod;
use Exception;

/**
 * Class CliRunner
 *
 * @author  Adrian Falleiro <adrian@falleiro.com>
 * @license MIT http://www.opensource.org/licenses/mit-license.php
 */
class CliRunner implements MiddlewareInterface
{

    /*
     * @var DI\Container
     */
    protected $container;

    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Called when the middleware is invoked
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (PHP_SAPI !== 'cli') {
            return $handler->handle($request);
        }

        global $argv;

        if (count($argv) > 1) {
            $command = $argv[1];
            $args = array_slice($argv, 2);
        } else {
            $command = '__default';
            $args = [];
        }

        $possible_commands = $this->container->get('commands');

        if (!array_key_exists($command, $possible_commands)) {
            throw new CliException(sprintf('Command %s not found', $command));
        }
        
        $class = $possible_commands[$command];

        // Bail if class doesn't exist
        if (!class_exists($class)) {
            throw new CliException(sprintf('Class %s does not exist', $class));
        }
      
        $task_class = new ReflectionClass($class);

        if (!$task_class->hasMethod('action')) {
            throw new CliException(sprintf('Class %s does not have a action() method', $class));
        }

        $task_instance = $this->container->get($class);

        $response = new Response();

        try {
            $response = $task_instance($request, $response, $args);
        } catch (CliException $e) {
            $response->getBody()->write($e->getMessage());
            $response->withStatus(200);
        }

        return $response;
    }
}
