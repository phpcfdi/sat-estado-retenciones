<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\HttpClients;

use PhpCfdi\SatEstadoRetenciones\Contracts\HttpClientInterface;
use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use Throwable;

final class PhpStreamContextHttpClient implements HttpClientInterface
{
    public function getContents(string $url): string
    {
        $previousErrorReporting = error_reporting(-1);
        try {
            $contents = file_get_contents($url) ?: '';
        } catch (Throwable $exception) {
            $status = $this->obtainStatusFromResponseHeader($http_response_header[0] ?? '');
            throw new HttpClientException($url, $status, $contents ?? '', $exception);
        } finally {
            error_reporting($previousErrorReporting);
        }
        return $contents;
    }

    public function obtainStatusFromResponseHeader(string $header): int
    {
        if (1 === preg_match('{HTTP/\S*\s(\d{3})}', $header, $match)) {
            return (int) $match[1];
        }
        return 500;
    }
}
