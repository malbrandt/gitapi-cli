<p align="center"><img src="https://ci6.googleusercontent.com/proxy/OrcPNNK8XdciG_GuNdkzm9C5OcnNXOV2Fmer-lio59NPVJsmi5zcp2nHzxG-QWvwxa7cHUlC=s0-d-e1-ft#https://insee.pl/signature/logo.png" width="90"></p>

# GitAPI CLI
This is a simple recruitment task - wrapper around Git-based services with CLI interface.

## Docs

### Usage (CLI)

If you want to show `sha` (hash) of the last commit in default repo branch, you should use `repo:last-commit-hash` command, i.e.:
```bash
./app repo:latest-commit sebastianbergmann/phpunit
```

#### Options

Command `repo:latest-commit` has following options:

- [-s|--service]? Which service driver to use (default: github, available: github)
- [-m|--more]? Whether to show more info about commit (default: false)

### Extending

#### Add/override services

In order to support another Git service (or replace driver of existing one), such as BitBucket, you should create your own API driver and register it using `Malbrandt\Git\Drivers\GitDriver::register($name, $class)` static method, i.e. in file `bin/gitapi.php`.

Every driver needs to extend `Malbrandt\Git\Drivers\GitDriver` class.

#### Add/override API Methods

GitDriver has [Macroable](https://github.com/spatie/macroable) trait, therefore if you need to add support for some API methods (or replace implementation of them) you'll need to add macro to some driver conrete, i.e.:
```php
\Malbrandt\Git\Drivers\GitHubDriver::macro(
    'listBranches', 
    function () {
        // some api call
        // return list of branches 
    }
);
```