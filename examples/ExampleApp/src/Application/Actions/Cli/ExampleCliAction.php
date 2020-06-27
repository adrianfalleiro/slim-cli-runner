<?php
declare(strict_types=1);

namespace App\Application\Actions\Cli;

use Psr\Http\Message\ResponseInterface as Response;
use adrianfalleiro\SlimCliRunner\CliAction;

use Psr\Log\LoggerInterface;

class ExampleCliAction extends CliAction
{
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function action(): Response
    {
        $arg0 = $this->resolveArg(0);
        $this->logToConsole("arg 0 is {$arg0}");

        return $this->respond();
    }
}
