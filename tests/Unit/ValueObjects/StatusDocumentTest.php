<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\ValueObjects;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\TestWith;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;

final class StatusDocumentTest extends TestCase
{
    public function testCreateStatusDocumentFromValueActive(): void
    {
        $status = StatusDocument::fromValue('Vigente');
        $this->assertTrue($status->isActive());
        $this->assertFalse($status->isCancelled());
        $this->assertFalse($status->isUnknown());
    }

    public function testCreateStatusDocumentFromValueCancelled(): void
    {
        $status = StatusDocument::fromValue('Cancelado');
        $this->assertFalse($status->isActive());
        $this->assertTrue($status->isCancelled());
        $this->assertFalse($status->isUnknown());
    }

    #[TestWith([''])]
    #[TestWith(['Otro valor'])]
    #[TestWith(['vigente'])]
    public function testCreateStatusDocumentFromValueNotFound(string $value): void
    {
        $status = StatusDocument::fromValue($value);
        $this->assertFalse($status->isActive());
        $this->assertFalse($status->isCancelled());
        $this->assertTrue($status->isUnknown());
    }

    public function testStatusDocumentCallInvalidMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        StatusDocument::Active->{'invalidMethod'}();
    }
}
