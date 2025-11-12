<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\Result;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusEfos;
use PHPUnit\Framework\Attributes\DataProvider;

final class ResultTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $result = new Result(
            'DCM991109KR2',
            'DEREMATE.COM DE MEXICO S DE RL DE CV',
            'SAZD861013FU2',
            'DANIEL SANCHEZ',
            '48C4CE37-E218-4AAE-97BE-20634A36C628',
            '2021-02-05T22:44:48',
            '2021-02-05T17:36:46',
            'TLE011122SC2',
            '$431.03',
            'Vigente',
            '200',
        );

        $this->assertInstanceOf(JsonSerializable::class, $result);
        $this->assertJsonStringEqualsJsonFile($this->filePath('result.json'), json_encode($result) ?: '');
    }

    /** @return array<string, array{string, StatusDocument}> */
    public static function providerStatusDocument(): array
    {
        return [
            'active' => ['Vigente', StatusDocument::Active],
            'cancelled' => ['Cancelado', StatusDocument::Cancelled],
            'empty' => ['', StatusDocument::Unknown],
            'other text' => ['Foo bar', StatusDocument::Unknown],
        ];
    }

    #[DataProvider('providerStatusDocument')]
    public function testStatusDocument(string $input, StatusDocument $expected): void
    {
        $result = new Result(
            'DCM991109KR2',
            'DEREMATE.COM DE MEXICO S DE RL DE CV',
            'SAZD861013FU2',
            'DANIEL SANCHEZ',
            '48C4CE37-E218-4AAE-97BE-20634A36C628',
            '2021-02-05T22:44:48',
            '2021-02-05T17:36:46',
            'TLE011122SC2',
            '$431.03',
            $input,
            '200',
        );

        $this->assertSame($expected, $result->statusDocument);
    }

    /** @return array<string, array{string, StatusEfos}> */
    public static function providerStatusEfos(): array
    {
        return [
            'included' => ['100', StatusEfos::Included],
            'excluded' => ['200', StatusEfos::Excluded],
            'empty' => ['', StatusEfos::Unknown],
            'other text' => ['Foo bar', StatusEfos::Unknown],
        ];
    }

    #[DataProvider('providerStatusEfos')]
    public function testStatusEfos(string $input, StatusEfos $expected): void
    {
        $result = new Result(
            'DCM991109KR2',
            'DEREMATE.COM DE MEXICO S DE RL DE CV',
            'SAZD861013FU2',
            'DANIEL SANCHEZ',
            '48C4CE37-E218-4AAE-97BE-20634A36C628',
            '2021-02-05T22:44:48',
            '2021-02-05T17:36:46',
            'TLE011122SC2',
            '$431.03',
            'Vigente',
            $input,
        );

        $this->assertSame($expected, $result->statusEfos);
    }
}
