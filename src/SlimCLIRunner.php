<?php

namespace adrianfalleiro;

use DI\Container;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;

use \RuntimeException;
use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

/**
 * Slim 4 PHP CLI task runner
 *
 * @package SlimCLIRunner
 * @author  Adrian Falleiro <adrian@falleiro.com>
 * @license MIT http://www.opensource.org/licenses/mit-license.php
 */
class SlimCLIRunner implements MiddlewareInterface
{

    /*
     * @var \DI\Container
     */
    protected $container;

    /**
     * Constructor
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Called when the class is invoked
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (PHP_SAPI !== 'cli') {
            return $handler->handle($request);
        }

        global $argv;
        $response = new Response();

        if (count($argv) > 1) {
            $command = $argv[1];
            $args = array_slice($argv, 2);
        } else {
            $command = '__default';
            $args = [];
        }

        $possible_commands = $this->container->get('commands');

        try {
            if (array_key_exists($command, $possible_commands)) {
                $class = $possible_commands[$command];

                // Bail if class doesn't exist
                if (!class_exists($class)) {
                    throw new RuntimeException(sprintf('Class %s does not exist', $class));
                }

                $task_class = new ReflectionClass($class);

                if (!$task_class->hasMethod('command')) {
                    throw new RuntimeException(sprintf('Class %s does not have a command() method', $class));
                }

                if ($task_class->getConstructor()) {
                    $task_construct_method = new ReflectionMethod($class,  '__construct');
                    $construct_params = $task_construct_method->getParameters();

                    if (count($construct_params) == 0) {
                        // Create a new instance without any args
                        $task = $task_class->newInstanceArgs();
                    } elseif (count($construct_params) == 1) {
                        // Create a new instance and pass the container by reference, if needed
                        if ($construct_params[0]->isPassedByReference()) {
                            $task = $task_class->newInstanceArgs([&$this->container]);
                        } else {
                            $task = $task_class->newInstanceArgs([$this->container]);
                        }
                    } else {
                        throw new RuntimeException(sprintf('Class %s has an unsupported __construct method', $class));
                    }
                } else {
                    $task = $task_class->newInstanceWithoutConstructor();
                }

                ob_start();
                $cli_response = $task->command($args);
                if (empty($cli_response)) {
                    $cli_response = ob_get_contents();
                }
                ob_end_clean();
                $response->getBody()->write($cli_response);
            } else {
                $response->getBody()->write("Command not found");
            }

            return $response->withStatus(200);

        } catch(Exception $e) {
            $response->getBody()->write($e->getMessage());
            return $response->withStatus(500);
        }
    }
}
