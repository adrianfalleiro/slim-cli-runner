<?php

class SampleTask {

    protected $container;

    public function __construct($container)
    {
        // access container classes
        // eg $container->get('redis');
        $this->container = $container;
    }

    public function command($args)
    {
        // do something
    }
}
