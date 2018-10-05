<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
declare(strict_types = 1);
namespace ExampleApp\Controller\Cli;

abstract class BaseController
{
    /**
     * Runs the actual command.
     *
     * @param string[] $aArgs Commandline arguments
     *
     * @return void
     */
    abstract protected function command(array $aArgs);

    /**
     * Write the provided text and append a system-specific new-line
     *
     * @param string $aText The text to write
     *
     * @return void
     */
    protected function writeln(string $aText = '') {
        print($aText . PHP_EOL);
    }
}
