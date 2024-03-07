# phpcfdi/sat-estado-retenciones

[![Source Code][badge-source]][source]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Total Downloads][badge-downloads]][downloads]

> Consulta el estado de un CFDI de Retenciones haciendo scrap del sitio del SAT

:us: The documentation of this project is in spanish as this is the natural language for the intended audience.

## Acerca de phpcfdi/sat-estado-retenciones

El Servicio de Administración Tributaria en México (SAT) expone algunos servicios para la comprobación fiscal.

Para el caso de *CFDI regulares* (CFDI de ingresos, egresos, traslados y nómina) ofrece un web service de tipo
SOAP para poder conocer el estado (vigente o cancelado) de un CFDI.

Para el caso de *CFDI de Retenciones e Información de Pagos (CFDI de retenciones) no ofrece un web service.
El SAT solo permite consultar su estado a través de una página de internet ubicada en
<https://prodretencionverificacion.clouda.sat.gob.mx/> y aparentemente protegida por un *captcha*.

Esta librería permite aprovechar que la herramienta del SAT tiene una incorrecta implementación del *captcha* y
no hay necesidad de resolverlo. Además, convierte la respuesta de la página de internet a propiedades de un objeto.

## Instalación

Usa [composer](https://getcomposer.org/)

```shell
composer require phpcfdi/sat-estado-retenciones
```

## Ejemplo de uso

```php
<?php

use PhpCfdi\SatEstadoRetenciones\Exceptions\HttpClientException;
use PhpCfdi\SatEstadoRetenciones\Exceptions\RetentionNotFoundException;
use PhpCfdi\SatEstadoRetenciones\Service;

$contents = file_get_contents('archivo-de-retenciones.xml');

$service = new Service();
$parameters = $service->makeParametersFromXml($contents);

try {
    $result = $service->query($parameters);
} catch (RetentionNotFoundException $exception) {
    echo "El CFDI de retenciones {$exception->getParameters()->uuid} no fue encontrado.\n";
    return;
} catch (HttpClientException $exception) {
    echo "No se pudo conectar al servicio en la URL {$exception->getUrl()}.\n";
    return;
}

if ($result->statusDocument->isActive()) {
    echo "El CFDI de retenciones {$result->uuid} de {$result->receiverName} se encuentra ACTIVO.\n";
}
```

## Funcionamiento

Esta librería ofrece un objeto de entrada `Service`, con el que se pueden ejecutar dos tareas:
generar los parámetros de consulta `Parameters` y consultar la página de internet con estos parámetros.
El resultado de la consulta es un objeto `Result`.

Actualmente, se aprovecha el error de la página del SAT donde no está implementando correctamente el *captcha*,
por lo que se puede consultar el estado brincando esta medida. Si en un futuro el SAT implenta un *Web Service*
o implementa correctamente el *captcha*, se espera que esta librería tenga que cambiar muy poco su interfaz de uso.

### Construcción de parámetros

El objeto de parámetros se puede construir a partir del UUID, RFC del emisor y RFC del receptor (si existe).
Igualmente, si se cuenta con el XML como texto o como un objeto DOM es posible obtener los
parámetros con los métodos `Service::makeParametersFromXml(string $xml): Parameters`
y `Service::makeParametersFromDocument(DOMDocument $document): Parameters` respectivamente.

Tanto los parámetros `Parameters` como el resultado `Result` implementan la interfaz `JsonSerializable`,
es decir, que exportan sus datos cuando se usa la función `json_encode()`.

### Ejecución de la consulta

#### Método `Service::query(Parameters $parameters): Result`

Ejecuta la consulta del estado de CFDI de retenciones y entrega el resultado en un objeto `Result`.
Si el CFDI de retenciones no fue encontrado genera una excepción de tipo `RetentionNotFoundException`.
Si la consulta falla por un error de conexión con el servidor se genera una excepción de tipo `HttpClientException`.

#### Método `Service::queryOrNull(Parameters $parameters): ?Result`

Existe el método `queryOrNull`, que es idéntico a `query` pero en lugar de generar la excepción
`RetentionNotFoundException` regresará `NULL`.

### Resultado

Al ejecutar la consulta se devuelve un objeto `Result` que contiene todas las propiedades que entrega
la página web. Adicionalmente, cuenta con 3 propiedades especiales para interpretar el estado del documento
(vigente o cancelado), el estado EFOS (incluido o excluido) y el total como valor flotante.

### Estrategia *scraper*

Actualmente, se obtiene el estado de CFDI de retenciones haciendo *scraping* a la página del SAT.

El scraper puede ser sustituido por otro objeto que implemente la interfaz `ScraperInterface`.

La implementación de `ScraperInterface` está en la clase `Scraper` y depende directamente de un
cliente HTTP para hacer una única petición de tipo `GET`.

El cliente puede ser sustituido por otro objeto que implemente la interfaz `HttpClientInterface`.

La implementación de `HttpClientInterface` está en la clase `PhpStreamContextHttpClient`,
que utiliza los *PHP Streams* para poder ejecutar la petición a la página del SAT.

En caso de querer sustiuir esta implementación por alguna librería como *Guzzle* o *Symfony HTTP Client*,
será necesario crear un objeto que implemente la interfaz `HttpClientInterface`.

### Excepciones

Cuando se ejecute la consulta de parámetros podrían ocurrir dos excepciones:
`RetentionNotFoundException` cuando no se encontró el CFDI de retenciones,
y `HttpClientException` cuando hubo un error para contactar al servicio.

Ambas excepciones son de tipo `\RuntimeException` y además implementan la interfaz `SatEstadoRetencionesException`.

## Soporte

Puedes obtener soporte abriendo un ticket en Github.

Adicionalmente, esta librería pertenece a la comunidad [PhpCfdi](https://www.phpcfdi.com), así que puedes usar los
mismos canales de comunicación para obtener ayuda de algún miembro de la comunidad.

## Compatibilidad

Esta librería se mantendrá compatible con al menos la versión con
[soporte activo de PHP](https://www.php.net/supported-versions.php) más reciente.

También utilizamos [Versionado Semántico 2.0.0](docs/SEMVER.md) por lo que puedes usar esta librería
sin temor a romper tu aplicación.

## Contribuciones

Las contribuciones con bienvenidas. Por favor lee [CONTRIBUTING][] para más detalles
y recuerda revisar el archivo de tareas pendientes [TODO][] y el archivo [CHANGELOG][].

## Copyright and License

The `phpcfdi/sat-estado-retenciones` library is copyright © [PhpCfdi](https://www.phpcfdi.com/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

[contributing]: https://github.com/phpcfdi/sat-estado-retenciones/blob/main/CONTRIBUTING.md
[changelog]: https://github.com/phpcfdi/sat-estado-retenciones/blob/main/docs/CHANGELOG.md
[todo]: https://github.com/phpcfdi/sat-estado-retenciones/blob/main/docs/TODO.md

[source]: https://github.com/phpcfdi/sat-estado-retenciones
[php-version]: https://packagist.org/packages/phpcfdi/sat-estado-retenciones
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/phpcfdi/sat-estado-retenciones/releases
[license]: https://github.com/phpcfdi/sat-estado-retenciones/blob/main/LICENSE
[build]: https://github.com/phpcfdi/sat-estado-retenciones/actions/workflows/build.yml?query=branch:main
[reliability]:https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-retenciones&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-retenciones&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=phpcfdi_sat-estado-retenciones&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=phpcfdi_sat-estado-retenciones&resolved=false
[downloads]: https://packagist.org/packages/phpcfdi/sat-estado-retenciones

[badge-source]: https://img.shields.io/badge/source-phpcfdi/sat--estado--retenciones-blue?logo=github
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-php-version]: https://img.shields.io/packagist/php-v/phpcfdi/sat-estado-retenciones?logo=php
[badge-release]: https://img.shields.io/github/release/phpcfdi/sat-estado-retenciones?logo=git
[badge-license]: https://img.shields.io/github/license/phpcfdi/sat-estado-retenciones?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/actions/workflow/status/phpcfdi/sat-estado-retenciones/build.yml?branch=main&logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_sat-estado-retenciones&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=phpcfdi_sat-estado-retenciones&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/phpcfdi_sat-estado-retenciones/main?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/phpcfdi_sat-estado-retenciones/main?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/phpcfdi/sat-estado-retenciones?logo=packagist
