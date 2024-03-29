<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Features;

use PhpCfdi\SatEstadoRetenciones\Service;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;

final class QueryWithRealCfdiTest extends TestCase
{
    public function testQueryWithRetentions10MexicanReal(): void
    {
        $contents = $this->fileContents('ret10-mexican-real.xml');
        $service = new Service();
        $parameters = $service->makeParametersFromXml($contents);
        $result = $service->query($parameters);

        $this->assertTrue($result->statusDocument->isActive());
        $this->assertSame($parameters->issuerRfc, $result->issuerRfc);
        $this->assertSame($parameters->receiverRfc, $result->receiverRfc);
        $this->assertSame($parameters->uuid, $result->uuid);
    }

    public function testQueryWithRetentions20MexicanReal(): void
    {
        $contents = $this->fileContents('ret20-mexican-real.xml');
        $service = new Service();
        $parameters = $service->makeParametersFromXml($contents);
        $result = $service->query($parameters);

        $this->assertTrue($result->statusDocument->isActive());
        $this->assertSame($parameters->issuerRfc, $result->issuerRfc);
        $this->assertSame($parameters->receiverRfc, $result->receiverRfc);
        $this->assertSame($parameters->uuid, $result->uuid);
    }
}
