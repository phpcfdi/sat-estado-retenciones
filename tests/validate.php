<?php

declare(strict_types=1);

use PhpCfdi\SatEstadoRetenciones\Service;

error_reporting(-1);

require __DIR__ . '/../vendor/autoload.php';

exit((function () use ($argv): int {
    try {
        $filename = $argv[1] ?? '';
        if (in_array($filename, ['', '-h', '--help'])) {
            $command = basename($argv[0] ?? '');
            echo implode(PHP_EOL, [
                'Script to validate a CFDI retentions',
                '',
                'Syntax:',
                "   $command -h|--help show command help",
                "   $command retentions-file.xml show status for the given XML file",
                '',
                'Copyright and license: https://github.com/phpcfdi/sat-estado-retenciones',
                '',
                '',
            ]);
            return 0;
        }

        $contents = file_get_contents($filename) ?: '';
        $service = new Service();

        $parameters = $service->makeParametersFromXml($contents);
        echo json_encode(['Parameters' => $parameters], JSON_PRETTY_PRINT), PHP_EOL;
        if ('' === $parameters->getUuid()) {
            throw new Exception('The retention file does not have an UUID');
        }

        $result = $service->query($parameters);
        echo json_encode(['Result' => $result], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;

        return 0;
    } catch (Throwable $exception) {
        file_put_contents('php://stderr', 'ERROR: ' . $exception->getMessage() . PHP_EOL);
        return 2;
    }
})());
