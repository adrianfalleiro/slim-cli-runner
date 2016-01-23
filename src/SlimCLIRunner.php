<?php

namespace adrianfalleiro;

class SlimCLIRunner {

    protected $container;
    
    public function __construct($container) {
        $this->container = $container;
    }  

    public function __invoke($request, $response, $next)
    {

        if (PHP_SAPI !== 'cli') return $next($request, $response);

        global $argv;

        $command = $argv[1];
        $args = array_slice ($argv, 2);
        $possible_commands = $this->container->get('commands');


        if (array_key_exists($command, $possible_commands)) {
            $class = $possible_commands[$command];

            // Bail if class doesn't exist
            if (!class_exists($class)) throw new RuntimeException(sprintf('Class %s does not exist', $class));

            $cli_task = (new $class($this->container))->command($args);

            $response->getBody()->write($cli_task . "\n");

        } else {
            $response->getBody()->write("Command not found\n");
        }
     
        return $response
            ->withStatus(200);
    }

}
