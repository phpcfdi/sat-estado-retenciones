<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\Exceptions;

use PhpCfdi\SatEstadoRetenciones\Exceptions\RetentionNotFoundException;
use PhpCfdi\SatEstadoRetenciones\Exceptions\SatEstadoRetencionesException;
use PhpCfdi\SatEstadoRetenciones\Parameters;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use RuntimeException;

final class RetentionNotFoundExceptionTest extends TestCase
{
    public function testExceptionTypes(): void
    {
        $parameters = new Parameters('12345678-1234-1234-1234-123456789012', 'AAA010101AAA', 'XXXX991231XX0');
        $exception = new RetentionNotFoundException($parameters);
        $this->assertInstanceOf(SatEstadoRetencionesException::class, $exception);
        $this->assertInstanceOf(RuntimeException::class, $exception);
    }

    public function testExceptionProperties(): void
    {
        $parameters = new Parameters('12345678-1234-1234-1234-123456789012', 'AAA010101AAA', 'XXXX991231XX0');
        $previous = new RuntimeException();
        $exception = new RetentionNotFoundException($parameters, $previous);
        $this->assertSame($parameters, $exception->getParameters());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
