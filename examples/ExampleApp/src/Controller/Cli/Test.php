<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
declare(strict_types = 1);
namespace ExampleApp\Controller\Cli;

final class Test extends BaseController
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

        $this->writeln('Seeing this text means that the CLI router works as it should.');
        $counter = 0;

        foreach ($aArgs as $item) {
            $this->writeln("\033[35marg " . ++$counter . ' = ' . $item . "\033[m");
        }
    }
}
