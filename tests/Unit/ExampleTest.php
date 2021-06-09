<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit;

use PhpCfdi\SatEstadoRetenciones\Example;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class ExampleTest extends TestCase
{
    public function testAssertIsworking()
    {
        $example = new Example();
        $this->assertInstanceOf(Example::class, $example);
        $this->markTestSkipped('The unit test environment is working');
    }
}
