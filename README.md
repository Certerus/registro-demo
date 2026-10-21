# Registro Demo · Ejercicio de gestión de cambios

Repositorio de demostración que aplica el **Procedimiento de gestión de cambios** de Mentor
sobre un plugin de WordPress con datos ficticios. Todo el contenido es de prueba; no contiene
información de clientes reales ni secretos (las credenciales viven en *GitHub Secrets* por ambiente).

## Ambientes

| Ambiente | Propósito | Cómo se actualiza |
|---|---|---|
| Desarrollo | Construcción del cambio | Equipo local de cada desarrollador, rama `feature/*` o `hotfix/*` |
| Staging | Pruebas técnicas y funcionales | Automático en cada Pull Request (check obligatorio) |
| Producción | Servicio a usuarios | Solo al publicar un Release **y** con autorización del responsable |

## Cómo se aplica el procedimiento

| Etapa | Mecanismo en este repositorio |
|---|---|
| 1. Solicitud | Issue con la plantilla **Solicitud de cambio** o **Cambio urgente** |
| 2. Análisis | Comentario de análisis de impacto (`docs/plantillas/analisis-de-impacto.md`) |
| 3. Alcance | Comentario de alcance + aprobación del responsable; etiqueta `alcance-aprobado` |
| 4. Desarrollo | Rama `feature/<issue>-<descripcion>`; commits que referencian `#<issue>` |
| 5. Pruebas | Workflow **CI** (sintaxis, versión, secretos, PHPUnit) + workflow **Staging** (despliegue y validación) |
| Revisión de código | Pull Request con aprobación obligatoria de otra persona; `main` protegida |
| 6. Aprobación | Ambiente `produccion` con revisores obligatorios; el autor no puede autoaprobarse |
| 7. Preparación | Respaldo automático del estado productivo antes de cada despliegue |
| 8. Implementación | Workflow **Producción** al publicar un Release `vX.Y.Z` |
| 9. Validación y liberación | Validación posterior automática (salud, versión, sitio) + verificación funcional |
| 10. Rollback | Workflow **Rollback** a una etiqueta estable, también con autorización |

## Reglas que no se pueden saltar

- `main` solo acepta cambios por Pull Request aprobado y con los checks **Pruebas automatizadas** y **Despliegue y validación en staging** en verde.
- Producción solo acepta despliegues de etiquetas `v*` o del workflow de rollback, y solo tras aprobación registrada.
- La versión liberada es identificable: cabecera del plugin = constante = CHANGELOG = etiqueta.
- No se permiten secretos en el código: revisión en CI + *secret scanning* y *push protection* de GitHub.
