<?php

namespace Saggre\ProcessManager\Service;

use Saggre\ProcessManager\Exception\ProcessCreateException;
use Saggre\ProcessManager\Exception\ProcessRunException;
use Saggre\ProcessManager\Model\ProcessResult;
use Saggre\ProcessManager\Strategy\OutputStrategyInterface;
use Stringable;

class ProcessService
{
    public function __construct(
        protected string|Stringable        $binaryPath,
        protected string|Stringable        $input = '',
        protected ?OutputStrategyInterface &$stdoutStrategy = null,
        protected ?OutputStrategyInterface &$stderrStrategy = null,
    )
    {
    }

    public function setInput(Stringable|string $input): ProcessService
    {
        $this->input = $input;
        return $this;
    }

    public function setStdoutStrategy(OutputStrategyInterface $stdoutStrategy): ProcessService
    {
        $this->stdoutStrategy = $stdoutStrategy;
        return $this;
    }

    public function setStderrStrategy(OutputStrategyInterface $stderrStrategy): ProcessService
    {
        $this->stderrStrategy = $stderrStrategy;
        return $this;
    }

    /**
     * Run the defined process.
     *
     * @return ProcessResult
     * @throws ProcessRunException
     * @throws ProcessCreateException
     */
    public function run(): ProcessResult
    {
        $process = $this->createProcess($this->input, $pipes);

        // Close input pipe.
        fclose($pipes[0]);

        $this->processPipe(
            $pipes[1],
            fn($line) => $this->stdoutStrategy?->processOutputChunk($line)
        );

        $this->processPipe(
            $pipes[2],
            fn($line) => $this->stderrStrategy?->processOutputChunk($line)
        );

        fclose($pipes[1]);
        fclose($pipes[2]);

        $signal = proc_close($process);
        $result = new ProcessResult($signal);

        if ($signal > 2) {
            throw new ProcessRunException($result, "Process failed with signal $signal");
        }

        return $result;
    }

    /**
     * @return array[]
     *
     * Get the descriptor specification for the process.
     */
    protected function getDescriptorSpec(): array
    {
        return [
            ['pipe', 'r'], // stdin
            ['pipe', 'w'], // stdout
            ['pipe', 'w'], // stderr
        ];
    }

    /**
     * Create a new phpcs process.
     *
     * @param string|Stringable $input
     * @param array|null $pipes
     *
     * @return resource
     * @throws ProcessCreateException
     */
    protected function createProcess(string|Stringable $input, ?array &$pipes = null)
    {
        $command = "$this->binaryPath" . ($input ? " $input" : '');

        $proc = proc_open(
            $command,
            $this->getDescriptorSpec(),
            $pipes
        );

        if (!is_resource($proc)) {
            throw new ProcessCreateException("Unable to start $command");
        }

        return $proc;
    }

    /**
     * Process pipe output.
     *
     * @param $pipe
     * @param $callback
     * @return void
     */
    protected function processPipe(&$pipe, $callback = null): void
    {
        while (!feof($pipe)) {
            $line = fgets($pipe, 1024);
            if (strlen($line) == 0) {
                break;
            }

            if (is_callable($callback)) {
                call_user_func($callback, $line);
            }

            if (ob_get_level() > 0) {
                ob_flush();
            }

            flush();
        }
    }
}
