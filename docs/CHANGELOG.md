# CHANGELOG

## SemVer 2.0

Utilizamos [Versionado Semántico 2.0.0](SEMVER.md).

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
