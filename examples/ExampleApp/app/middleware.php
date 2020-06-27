<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

use adrianfalleiro\SlimCliRunner\CliRunner;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->add(CliRunner::class);
};
