<?php

namespace Saggre\Test\Integration;

use PHPUnit\Framework\TestCase;
use Saggre\ProcessManager\Exception\ProcessCreateException;
use Saggre\ProcessManager\Exception\ProcessRunException;
use Saggre\ProcessManager\Service\ProcessService;

class ProcessServiceTest extends TestCase
{
    public function testRunPhp(): void
    {
        try {
            $result = (new ProcessService(PHP_BINARY))->run('-v');
        } catch (ProcessCreateException|ProcessRunException $e) {
            $this->fail("Process failed with message: {$e->getMessage()}");
        }

        $this->assertEquals(0, $result->exitCode);
        $this->assertStringContainsString(PHP_VERSION, $result->stdout);
    }

    public function testRunInvalid(): void
    {
        $this->expectException(ProcessRunException::class);

        try {
            (new ProcessService('foobar'))->run();
        } catch (ProcessCreateException $e) {
            $this->fail("Process failed with message: {$e->getMessage()}");
        }
    }
}
