<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
declare(strict_types = 1);
namespace ExampleApp\Controller\Cli;

final class Help extends BaseController
{
    /**
     * {@inheritdoc}
     *
     * @param string[] $aArgs {@inheritdoc}
     *
     * @return void
     */
    public function command(array $aArgs)
    {
        global $container;

        $this->writeln('
This is the help display of the Example App CLI.

General commands:
    Help - Show this help menu
    Test - Run a simple test
');
    }
}
