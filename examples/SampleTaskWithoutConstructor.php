<?php

use \RuntimeException;

class SampleTask {

    /** 
     * SampleTask command
     * 
     * @param array $args
     * @return void
     */
    public function command($args)
    {
        // Throw if no arguments provided and args less than 2
        if (empty($args) || count($args) < 2) {
            throw new RuntimeException("Invalid argument count");
        }

        $secondArg = $args[1];

        // Output the second argument
        return $secondArg;

    }
}
