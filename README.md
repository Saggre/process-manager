# Process Manager

![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/Saggre/process-manager/php.yml)
![Codecov](https://img.shields.io/codecov/c/github/Saggre/process-manager)

A library used to call external binaries with PHP. It provides a simple interface to run processes, handle their input
and output, and manage their execution.

## Usage

### With no output

```php
try{
    $result = (new ProcessService('/usr/bin/node'))
                ->setInput('--version')
                ->run();
    
    echo $result->getExitCode();
    // 0
} catch (ProcessCreateException|ProcessRunException $e) {
    // TODO: Handle exceptions
}
```

### With buffered output

```php
try{
    $stdoutStrategy = new BufferedOutputStrategy();

    $result = (new ProcessService('/usr/bin/node'))
                ->setStdoutStrategy($stdoutStrategy)
                ->setInput('--version')
                ->run();
    
    echo $stdoutStrategy->getOutput();
    // v22.16.0
} catch (ProcessCreateException|ProcessRunException $e) {
    // TODO: Handle exceptions
}
```

### With streamed output

```php
try{
    $stdoutStrategy = new StreamedOutputStrategy(
        fn(string $data) => print $data
        // v22.16.0
    )->setChunkLength(128);

    $result = (new ProcessService('/usr/bin/node'))
                ->setStdoutStrategy($stdoutStrategy)
                ->setInput('--version')
                ->run();
} catch (ProcessCreateException|ProcessRunException $e) {
    // TODO: Handle exceptions
}
```
