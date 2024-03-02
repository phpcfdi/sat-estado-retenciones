<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit;

use JsonSerializable;
use PhpCfdi\SatEstadoRetenciones\Result;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusDocument;
use PhpCfdi\SatEstadoRetenciones\ValueObjects\StatusEfos;

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
            '200'
        );

        $this->assertInstanceOf(JsonSerializable::class, $result);
        $this->assertJsonStringEqualsJsonFile($this->filePath('result.json'), json_encode($result) ?: '');
    }

    /** @return array<string, mixed[]> */
    public function providerStatusDocument(): array
    {
        return [
            'active' => ['Vigente', StatusDocument::active()],
            'cancelled' => ['Cancelado', StatusDocument::cancelled()],
            'empty' => ['', StatusDocument::unknown()],
            'other text' => ['Foo bar', StatusDocument::unknown()],
        ];
    }

    /**
     * @dataProvider providerStatusDocument
     */
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
            '200'
        );

        $this->assertEquals($expected, $result->getStatusDocument());
    }

    /** @return array<string, mixed[]> */
    public function providerStatusEfos(): array
    {
        return [
            'included' => ['100', StatusEfos::included()],
            'excluded' => ['200', StatusEfos::excluded()],
            'empty' => ['', StatusEfos::unknown()],
            'other text' => ['Foo bar', StatusEfos::unknown()],
        ];
    }

    /**
     * @dataProvider providerStatusEfos
     */
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
            $input
        );

        $this->assertEquals($expected, $result->getStatusEfos());
    }
}
