<?php

use \Interop\Container\ContainerInterface;
use \RuntimeException;

class SampleTask {

    /** @var ContainerInterface */
    protected $container;

    /** 
     * Constructor
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        // access container classes
        // eg $container->get('redis');
        $this->container = $container;
    }

    /** 
     * SampleTask command
     * 
     * @param array $args
     * @return void
     */
    public function command($args)
    {
        // Access items in container
        $settings = $this->container->get('settings');
        
        // Throw if no arguments provided
        if (empty($args)) {
            throw new RuntimeException("No arguments passed to command");
        }

        $firstArg = $args[0];

        // Output the first argument
        return $firstArg;
    }
}
