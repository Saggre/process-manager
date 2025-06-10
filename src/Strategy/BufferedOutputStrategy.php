<?php

namespace Saggre\ProcessManager\Strategy;

class BufferedOutputStrategy implements OutputStrategyInterface
{
    protected string $output = '';

    public function processOutputChunk(string $chunk): void
    {
        $this->output .= $chunk;
    }

    public function getOutput(): string
    {
        return $this->output;
    }
}
