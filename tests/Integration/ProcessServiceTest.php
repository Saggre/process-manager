<?php

namespace Saggre\Test\Integration;

use PHPUnit\Framework\TestCase;
use Saggre\ProcessManager\Exception\ProcessCreateException;
use Saggre\ProcessManager\Exception\ProcessRunException;
use Saggre\ProcessManager\Service\ProcessService;
use Saggre\ProcessManager\Strategy\BufferedOutputStrategy;

class ProcessServiceTest extends TestCase
{
    public function testRunPhp(): void
    {
        $stdoutStrategy = new BufferedOutputStrategy();
        $stderrStrategy = new BufferedOutputStrategy();

        try {
            $result = (new ProcessService(PHP_BINARY))
                ->setStdoutStrategy($stdoutStrategy)
                ->setStderrStrategy($stderrStrategy)
                ->setInput('-v')
                ->run();
        } catch (ProcessCreateException|ProcessRunException $e) {
            $this->fail("Process failed with message: {$e->getMessage()}");
        }

        $this->assertEquals(0, $result->getExitCode());
        $this->assertStringContainsString(PHP_VERSION, $stdoutStrategy->getOutput());
        $this->assertEmpty($stderrStrategy->getOutput());
    }

    public function testRunInvalid(): void
    {
        $stdoutStrategy = new BufferedOutputStrategy();
        $stderrStrategy = new BufferedOutputStrategy();

        $this->expectException(ProcessRunException::class);

        try {
            (new ProcessService('foobar'))
                ->setStdoutStrategy($stdoutStrategy)
                ->setStderrStrategy($stderrStrategy)
                ->run();
        } catch (ProcessCreateException $e) {
            $this->fail("Process failed with message: {$e->getMessage()}");
        } catch (ProcessRunException $e) {
            $this->assertEquals(127, $e->result?->getExitCode());
            $this->assertEquals('', $stdoutStrategy->getOutput());
            $this->assertStringContainsString('not found', $stderrStrategy->getOutput());
            throw $e;
        }
    }
}
