<?php
declare(strict_types=1);

namespace App\Application\Actions\Cli;

use Psr\Http\Message\ResponseInterface as Response;
use adrianfalleiro\SlimCliRunner\CliAction;

class ExampleWithoutConstructorCliAction extends CliAction
{
    protected function action(): Response
    {
        $arg0 = $this->resolveArg(0);
        $this->logToConsole("arg 0 is {$arg0}");

        return $this->respond();
    }
}
