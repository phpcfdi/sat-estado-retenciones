<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\Tests\Unit;

use DOMDocument;
use PhpCfdi\SatEstadoRetenciones\Contracts\ScraperInterface;
use PhpCfdi\SatEstadoRetenciones\Exceptions\RetentionNotFoundException;
use PhpCfdi\SatEstadoRetenciones\Internal\ResultConverter;
use PhpCfdi\SatEstadoRetenciones\Parameters;
use PhpCfdi\SatEstadoRetenciones\Scraper;
use PhpCfdi\SatEstadoRetenciones\Service;
use PhpCfdi\SatEstadoRetenciones\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class ServiceTest extends TestCase
{
    public function testConstructHasDefaultScraper(): void
    {
        $service = new Service();
        $this->assertInstanceOf(Scraper::class, $service->scraper);
    }

    public function testConstructWithScraper(): void
    {
        $scraper = $this->createMock(ScraperInterface::class);
        $service = new Service($scraper);
        $this->assertSame($scraper, $service->scraper);
    }

    public function testQueryReturnsResult(): void
    {
        $parameters = new Parameters('12345678-1234-1234-1234-123456789012', 'AAA010101AAA', 'XXXX991231XX0');
        $result = (new ResultConverter())->createResultFromValues([]);

        /** @var ScraperInterface&MockObject $scraper */
        $scraper = $this->createMock(ScraperInterface::class);
        $scraper->expects($this->once())->method('obtainStatus')->willReturn($result);

        $service = new Service($scraper);

        $this->assertSame($result, $service->query($parameters));
    }

    public function testQueryOrNullReturnsNull(): void
    {
        $parameters = new Parameters('12345678-1234-1234-1234-123456789012', 'AAA010101AAA', 'XXXX991231XX0');
        $exception = new RetentionNotFoundException($parameters);

        /** @var ScraperInterface&MockObject $scraper */
        $scraper = $this->createMock(ScraperInterface::class);
        $scraper->expects($this->once())->method('obtainStatus')->willThrowException($exception);

        $service = new Service($scraper);

        $this->assertNull($service->queryOrNull($parameters));
    }

    public function testMakeParametersFromDocument(): void
    {
        $service = new Service();
        $document = new DOMDocument();
        $document->load($this->filePath('ret10-mexican-real.xml'));
        $parameters = $service->makeParametersFromDocument($document);
        $this->assertSame('48C4CE37-E218-4AAE-97BE-20634A36C628', $parameters->uuid);
        $this->assertSame('DCM991109KR2', $parameters->issuerRfc);
        $this->assertSame('SAZD861013FU2', $parameters->receiverRfc);
    }

    public function testMakeParametersFromDocumentWithNonMatchingXml(): void
    {
        $service = new Service();
        $document = new DOMDocument();
        $document->loadXML('<xml />');
        $parameters = $service->makeParametersFromDocument($document);
        $this->assertSame('', $parameters->uuid);
        $this->assertSame('', $parameters->issuerRfc);
        $this->assertSame('', $parameters->receiverRfc);
    }

    public function testMakeParametersFromContents(): void
    {
        $service = new Service();
        $parameters = $service->makeParametersFromXml($this->fileContents('ret10-mexican-real.xml'));
        $this->assertSame('48C4CE37-E218-4AAE-97BE-20634A36C628', $parameters->uuid);
        $this->assertSame('DCM991109KR2', $parameters->issuerRfc);
        $this->assertSame('SAZD861013FU2', $parameters->receiverRfc);
    }
}
