# Slim CLI Runner

Create and run command line tasks for the Slim PHP micro-framework

## Installation

**Installation**

`composer require adrianfalleiro/slim-cli-runner`

**Register Middleware**

Register the middleware in `middleware.php`

`$app->add(\adrianfalleiro\SlimCLIRunner::class);`

## Define and run your tasks

**Define tasks**

Add a new key in `settings.php` called `commands`

```
'commands' => [
    'SampleTask' => \Namespace\To\Task::class
],
```

## Examples

Examples can be found in the `examples/` folder
