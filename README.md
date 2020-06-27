# Slim CLI Runner

Create and run command line tasks for the Slim PHP micro-framework

## Installation

**Installation**

```
composer require adrianfalleiro/slim-cli-runner ^3.0
```

*For Slim 3 support install version 2.6 or lower*

**Register Middleware**

Register the middleware in `middleware.php`

```php
$app->add(new adrianfalleiro\SlimCliRunner\CliRunner::class);
```

## Define, Register and run your tasks

**Task definition**

Tasks are classes which extend `CliAction` and have a public `command()` method.

You can inject dependencies through the class constructor.

```php
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
```

**Tasks registration**

Add a new key in your `settings.php` definitions file called `commands` and list your tasks.
To define a default task (For use when no command name is provided) add a new task with `__default` as key

```php
return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            ...
        ],
        'commands' => [
            '__default' => \Namespace\To\Task::class
            'SampleTask' => \Namespace\To\Task::class
        ]
    ]);
};
```

**Run Tasks**

There are multiple ways of doing this:  
Directly via command line:

```
php public/index.php SampleTask arg1 arg2 arg3
```

Via composer:  
_composer.json_

```json
{
    /*...*/
    "config": {
        "process-timeout" : 0
    },
    "scripts": {
        /*...*/
        "cli": "php public/index.php SampleTask arg1 arg2 arg3"
    }
}
```

_The command_

```
composer cli SampleTask argument1 argument2 argument3
```

## Examples

An example project can be found in the `examples/` folder
