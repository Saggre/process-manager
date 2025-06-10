<?php

namespace Saggre\ProcessManager\Strategy;

interface OutputStrategyInterface
{
    public function getChunkLength(): int;

    public function processOutputChunk(string $chunk): void;
}
