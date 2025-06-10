<?php

namespace Saggre\ProcessManager\Strategy;

interface OutputStrategyInterface
{
    public function processOutputChunk(string $chunk): void;
}
