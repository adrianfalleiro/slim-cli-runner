<?php

namespace adrianfalleiro;

use \Interop\Container\ContainerInterface;
use \RuntimeException;
use \ReflectionClass;
use \ReflectionMethod;
use \Exception;

/**
 * Slim PHP 3 CLI task runner
 *
 * @package SlimCLIRunner
 * @author  Adrian Falleiro <adrian@falleiro.com>
 * @license MIT http://www.opensource.org/licenses/mit-license.php
 */
class SlimCLIRunner
{

    /*
     * @var \Interop\Container\ContainerInterface 
     */
    protected $container;

    /**
     * Constructor
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Called when the class is invoked
     * @param $request
     * @param $response
     * @param $next
     */
    public function __invoke($request, $response, $next)
    {
        if (PHP_SAPI !== 'cli') {
            return $next($request, $response);
        }

        global $argv;

        $command = $argv[1];
        $args = array_slice($argv, 2);
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

                $cli_response = $task->command($args);
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
