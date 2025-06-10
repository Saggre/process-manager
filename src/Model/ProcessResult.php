<?php

namespace Saggre\ProcessManager\Model;

class ProcessResult
{
    public function __construct(
        protected int $exitCode,
    )
    {
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }
}
