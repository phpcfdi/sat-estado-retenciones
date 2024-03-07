<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit\ValueObjects;

use BadMethodCallException;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusEfos;
use PHPUnit\Framework\Attributes\TestWith;

final class StatusEfosTest extends TestCase
{
    public function testCreateStatusEfosFromValueIncluded(): void
    {
        $status = StatusEfos::fromValue('100');
        $this->assertTrue($status->isIncluded());
        $this->assertFalse($status->isExcluded());
        $this->assertFalse($status->isUnknown());
    }

    public function testCreateStatusEfosFromValueExcluded(): void
    {
        $status = StatusEfos::fromValue('200');
        $this->assertFalse($status->isIncluded());
        $this->assertTrue($status->isExcluded());
        $this->assertFalse($status->isUnknown());
    }

    #[TestWith([''])]
    #[TestWith(['Otro valor'])]
    #[TestWith(['300'])]
    public function testCreateStatusEfosFromValueUnknown(string $value): void
    {
        $status = StatusEfos::fromValue($value);
        $this->assertFalse($status->isIncluded());
        $this->assertFalse($status->isExcluded());
        $this->assertTrue($status->isUnknown());
    }

    public function testStatusEfosCallInvalidMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        StatusEfos::Excluded->{'invalidMethod'}();
    }
}
