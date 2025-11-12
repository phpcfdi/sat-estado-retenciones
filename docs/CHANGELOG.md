# CHANGELOG

## SemVer 2.0

Utilizamos [Versionado Semántico 2.0.0](SEMVER.md).

## Versión 2.1.0

Esta versión principalmente arregla el soporte de PHP 8.4.

Adicionalmente:

- Se actualiza el año de la licencia a 2025.
- Se corrigen las insignias de SonarQube Cloud.

Cambios al entorno de desarrollo:

- Se corrige la integración con SonarQube Cloud.
- Se cambia de `httpbin.org` a `ree.mockerapi.com` debido a problemas de disponibilidad del servicio.
- Se actualiza el estándar de código.
- Se agrega la herramienta `composer-normalize`.
- En los flujos de trabajo de GitHub:
  - Se agrega PHP 8.4 a la matriz de pruebas.
  - Se ejecutan los trabajos en PHP 8.4.
  - Se agrega el trabajo `composer-normalize`.
  - Se agrega explícitamente la extensión SOAP cuando se instalan paquetes.
  - Se cambia el nombre de variable `matrix.php-version` a singular.
- Se mejora la configuración de PHPUnit para mostrar todos los problemas encontrados.
- Se actualizan las herramientas de desarrollo.

## Versión 2.0.0

Si ya habías implementado la versión 1.x, consula la [Guía de actualización de la versión 1.x a 2.x](UPGRADE_v1_v2.md).
Si es una implementación nueva, solamente sigue la documentación del proyecto.

Cambios más relevantes:

- La versión mínima es ahora PHP 8.2, se agrega PHP 8.3 a la matriz de pruebas.
- Se dejan de utilizar *getters* a favor de propiedades públicas de solo lectura, excepto en *Excepciones*.
- Los enumeradores cambian de `eclipxe/enum` a tipos de PHP.
- Se actualiza el año en el archivo de licencia. Feliz 2024.
- Se actualiza el flujo de trabajo para ejecutar los trabajos en PHP 8.3.
- Se actualizan las herramientas de desarrollo.

## Versión 1.1.0

- Se agrega el soporte para validar retenciones 2.0.

Estos cambios aplican de forma interna:

- Se refactorizaron los lectores de archivos de retenciones para hacerlos específicos a una versión.
- Se actualizan librerías de desarrollo.
- Se actualiza el archivo de configuración `.php-cs-fixer.php`.

## Versión 1.0.1

- Se actualiza el año del archivo de licencia a 2022.
- Se corrige el proceso de integración continua de los problemas detectados por PHPStan.
- Se corrige la exclusión del archivo de configuración de PHPStan.
- Se corrige la exclusión del archivo de configuración de PHP Coding Standards Fixer.
- Se actualiza la configuración de PHPUnit.
- Se corrige el grupo de mantenedores de GitHub en el archivo `.github/CODEOWNERS`.
- Migrar de `develop/install-development-tools` a `phive`.
- Se actualiza el flujo del proceso de integración continua para que los pasos sean trabajos.
- Se incluye PHP 8.1 en el proceso de integración continua.
- Se actualizan las reglas de estilo de código para basarse en las últimas revisiones de PhpCfdi.
- Se migra de Scrutinizer-CI a SonarCloud como herramienta de revisión del proyecto. ¡Gracias por todo Scrutinizer-CI!

## Versión 1.0.0

- Versión inicial.
