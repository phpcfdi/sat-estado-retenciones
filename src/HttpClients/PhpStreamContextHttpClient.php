<?php

declare(strict_types=1);

namespace PhpCfdi\SatEstadoRetenciones\HttpClients;

use Exception;
use PhpCfdi\SatEstadoRetenciones\Contracts\HttpClientInterface;
use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use Throwable;

final readonly class PhpStreamContextHttpClient implements HttpClientInterface
{
    public function getContents(string $url): string
    {
        $previousErrorReporting = error_reporting(-1);
        try {
            $contents = @file_get_contents($url);
            if (false === $contents) {
                $lastMessage = trim((error_get_last() ?? [])['message'] ?? '');
                throw new Exception($lastMessage ?: sprintf('Unknown error reading %s', $url));
            }
            return $contents;
        } catch (Throwable $exception) {
            $status = $this->obtainStatusFromResponseHeader($http_response_header[0] ?? '');
            throw new HttpClientException($url, $status, body: '', previous: $exception);
        } finally {
            error_reporting($previousErrorReporting);
        }
    }

    public function obtainStatusFromResponseHeader(string $header): int
    {
        if (1 === preg_match('{HTTP/\S*\s(\d{3})}', $header, $match)) {
            return (int) $match[1];
        }
        return 500;
    }
}
