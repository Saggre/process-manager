<?php

namespace Saggre\ProcessManager\Strategy;

abstract class OutputStrategy
{
    protected int $chunkLength = 1024;

    public function setChunkLength(int $length): static
    {
        $this->chunkLength = $length;

        return $this;
    }

    public function getChunkLength(): int
    {
        return $this->chunkLength;
    }
}
