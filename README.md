# Slim CLI Runner

Create and run command line tasks for the Slim PHP micro-framework

## Installation

**Installation**

```
composer require adrianfalleiro/slim-cli-runner ^2.6
```

**Register Middleware**

Register the middleware in `middleware.php`

```php
$app->add(\adrianfalleiro\SlimCLIRunner::class);
```

## Define, Register and run your tasks

**Task definition**

Tasks are simply classes which have a public `command()` method.

The dependency container is passed to the constructor, and console arguments are passed to the `command()` method.

```php
use \Psr\Container\ContainerInterface;
use \RuntimeException;

class SampleTask {

    /** @var ContainerInterface */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct($container)
    {
        // access container classes
        // eg $container->get('redis');
        $this->container = $container;
    }

    /**
     * SampleTask command
     *
     * @param array $args
     * @return void
     */
    public function command($args)
    {
        // Access items in container
        $settings = $this->container->get('settings');

        // Throw if no arguments provided
        if (empty($args)) {
            throw new RuntimeException("No arguments passed to command");
        }

        $firstArg = $args[0];

        // Output the first argument
        return $firstArg;
    }
}
```

**Tasks registration**

Add a new key in your `settings.php` file called `commands` and list your tasks.  
_Keep in mind that you should NOT add this within the 'settings' values_  
To define a default task (For use when no command name is provided) add a new task with `__default` as key

```php
'settings' => [
    // ...
],
'commands' => [
    'SampleTask' => \Namespace\To\Task::class
],
```

**Run Tasks**

There are multiple ways of doing this:  
Directly via commandline:

```
php /path/to/slim/public/index.php SampleTask argument1 argument2 argument3
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
        "cli": "php public/index.php"
    }
}
```

_The command_

```
composer cli SampleTask argument1 argument2 argument3
```

## Examples

Examples can be found in the `examples/` folder
