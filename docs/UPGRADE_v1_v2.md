# Guía de actualización de la versión 1.x a 2.x

Para esta nueva versión hay cambios muy relevantes:

- La versión mínima es ahora PHP 8.2, se agrega PHP 8.3 a la matriz de pruebas.
- Se usan propiedades públicas de solo lectura en lugar de *getters*.
- Las clases ahora son finales y de solo lectura.
- Los enumeradores cambian de `eclipxe/enum` a tipos de PHP.

## PHP

La versión mínima es ahora PHP 8.2. Se comprueba la compatibilidad de PHP 8.3 con la matriz de pruebas.

## Propiedades públicas de solo lectura en lugar de *getters*

Aprovechando las mejoras de PHP y que las clases ahora son finales y de solo lectura, se han dejado de 
utilizar los métodos para consultar propiedades (*getters*) y en su lugar se usan las propiedades públicas 
en modo solo lectura. El código que seguramente deberás modificar es para las clases `Parameters` y `Result`.

```diff
- echo $result->getUuid();
+ echo $result->uuid;
```

## Clases finales y de solo lectura

Es bastante poco probable que hayas extendido las clases de esta librería, pero si ese es el caso,
encontrarás que la mayoría de las clases ahora son finales y sus propiedades de solo lectura.

## Enumeradores de estado

Los enumeradores ya no usan `eclipxe/enum`, ahora son enumeradores de PHP, por lo que las comparaciones idénticas son válidas.

Si estabas instanciando algún estado, tu código debería cambiar de un método a un valor de enumerador, por ejemplo:

```diff
- return StatusDocument::active();
+ return StatusDocument::Active;
```

Los métodos de comprobación `is*` siguen funcionando, por ejemplo: `$result->statusDocument->isActive()`.

Toma en cuenta que los enumeradores no son `Stringable`, por lo que debes usar la propiedad `name`.
También el texto cambió de ser la primera letra minúscula a la primera letra mayúscula.
Si necesitas el texto exacto puedes usar `lcfirst($enum->name)`.

```diff
- echo StatusDocument::active();        // active
+ echo StatusDocument::Active->name;    // Active
```
