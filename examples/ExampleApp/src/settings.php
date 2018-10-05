<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
return [
    'settings' => [
        'debug' => true,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => true
    ],
    // CLI config
    'commands' => [
        '__default' => \ExampleApp\Controller\Cli\Help::class,
        'Help' => \ExampleApp\Controller\Cli\Help::class,
        'Test' => \ExampleApp\Controller\Cli\Test::class
    ]
];