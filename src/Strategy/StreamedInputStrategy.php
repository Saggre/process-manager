<?php

namespace Saggre\ProcessManager\Strategy;

use Stringable;

class StreamedInputStrategy extends InputStrategy implements InputStrategyInterface
{
    protected mixed $react;
    protected int $chunkIndex = 0;

    public function __construct(callable $react)
    {
        $this->react = $react;
    }

    public function getNextInputChunk(): string|Stringable
    {
        $chunk = (string)call_user_func($this->react, $this);
        $this->chunkIndex++;

        return $chunk;
    }

    public function getChunkIndex(): int
    {
        return $this->chunkIndex;
    }
}
