<?php

namespace Saggre\ProcessManager\Strategy;

use Stringable;

interface InputStrategyInterface
{
    function getNextInputChunk(): string|Stringable;
}
