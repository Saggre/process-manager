![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/Saggre/process-manager/php.yml)
![Codecov](https://img.shields.io/codecov/c/github/Saggre/process-manager)

# Process Manager

A library used to call external binaries with PHP.

## Usage

```php
$processService = new ProcessService('/usr/bin/program');

try{
    $result = $processService->run('--version');
    
    $exitCode = $result->exitCode;
    $stdout = $result->stdout;
} catch (ProcessCreateException|ProcessRunException $e) {
    // TODO: Handle exceptions
}
```
