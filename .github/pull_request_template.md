## Cambio

Resuelve #<!-- número de la solicitud -->

**Versión propuesta:** v<!-- X.Y.Z -->

## Descripción técnica

<!-- Qué se modificó y por qué. -->

## Clasificación de impacto (§5)

- [ ] Bajo
- [ ] Medio
- [ ] Alto

## Pruebas realizadas (Etapa 5 · §8)

- [ ] Pruebas automatizadas en verde (check **Pruebas automatizadas**)
- [ ] Despliegue y validación automática en staging (check **Despliegue y validación en staging**)
- [ ] Pruebas funcionales en staging según criterios de aceptación:
  - [ ] <!-- criterio 1 -->
  - [ ] <!-- criterio 2 -->
- [ ] Regresión: el listado y el endpoint de salud siguen funcionando

## Reversión (§10 · §12)

- **Versión estable anterior:** v<!-- X.Y.Z -->
- **Mecanismo:** respaldo automático previo al despliegue + workflow **Rollback** a la etiqueta anterior.

## Verificación del desarrollador

- [ ] Sin contraseñas, tokens ni llaves en el código
- [ ] Versión actualizada en cabecera, constante y CHANGELOG
- [ ] Alcance limitado a lo aprobado en la solicitud
