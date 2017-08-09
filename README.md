# Slim CLI Runner

Create and run command line tasks for the Slim PHP micro-framework

## Installation

**Installation**

`composer require adrianfalleiro/slim-cli-runner ^2.0`

**Register Middleware**

Register the middleware in `middleware.php`

```
use \adrianfalleiro\SlimCLIRunner;
$app->add(SlimCLIRunner::class);
```

## Define and run your tasks

**Define tasks**

Add a new key in `settings.php` called `commands` and list your tasks.  
_Keep in mind that you should NOT add this within the 'settings' values_

```
'commands' => [
    'SampleTask' => \Namespace\To\Task::class
],
```

**Run Tasks**

`php /path/to/slim/public/index.php SampleTask`

## Examples

Examples can be found in the `examples/` folder
