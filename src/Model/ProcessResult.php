<?php

namespace Saggre\ProcessManager\Model;

class ProcessResult
{
    public function __construct(
        public int    $exitCode,
        public string $stdout,
    )
    {
    }
}
