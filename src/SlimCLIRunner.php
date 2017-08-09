<?php

namespace adrianfalleiro;

use \Interop\Container\ContainerInterface;
use \RuntimeException;
use \ReflectionClass;

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
            
            $task = $task_class->newInstanceArgs([$this->container]);
            $cli_response = $task->command($args);
            $response->getBody()->write($cli_response . "\n");
        } else {
            $response->getBody()->write("Command not found\n");
        }
     
        return $response->withStatus(200);
    }
}
