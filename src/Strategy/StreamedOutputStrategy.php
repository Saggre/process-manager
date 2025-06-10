<?php

namespace Saggre\ProcessManager\Strategy;

use InvalidArgumentException;

class StreamedOutputStrategy extends OutputStrategy implements OutputStrategyInterface
{
    protected $outputHandler;

    public function __construct(
        callable $outputHandler,
    )
    {
        if (!is_callable($outputHandler)) {
            throw new InvalidArgumentException('Output handler must be callable.');
        }

        $this->outputHandler = $outputHandler;
    }

    public function processOutputChunk(string $chunk): void
    {
        call_user_func($this->outputHandler, $chunk);
    }
}
