# Process Manager

![Codecov](https://img.shields.io/codecov/c/github/Saggre/process-manager)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2FSaggre%2Fprocess-manager.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2FSaggre%2Fprocess-manager?ref=badge_shield)

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

## License

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2FSaggre%2Fprocess-manager.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2FSaggre%2Fprocess-manager?ref=badge_large)
