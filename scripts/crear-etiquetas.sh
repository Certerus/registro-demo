#!/usr/bin/env bash
# Crea las etiquetas que usan las plantillas de solicitud de cambio.
# Requiere GitHub CLI autenticado: gh auth login
# Uso: scripts/crear-etiquetas.sh ORGANIZACION/REPOSITORIO
set -euo pipefail

REPO="${1:?Indica el repositorio, p. ej. mentor-demo/registro-demo}"

crear() { gh label create "$1" --repo "$REPO" --color "$2" --description "$3" --force; }

crear "solicitud"           "1d4ed8" "Etapa 1 – Solicitud registrada"
crear "pendiente-analisis"  "f59e0b" "Etapa 2 – En espera de análisis de impacto"
crear "alcance-aprobado"    "16a34a" "Etapa 3 – Alcance confirmado por el responsable"
crear "en-desarrollo"       "7c3aed" "Etapa 4 – En desarrollo"
crear "en-pruebas"          "0891b2" "Etapa 5 – En validación en staging"
crear "liberado"            "15803d" "Etapa 9 – Liberado y validado en producción"
crear "cancelado"           "6b7280" "Cambio cancelado o pospuesto (§8)"
crear "rollback"            "b91c1c" "Se ejecutó reversión (§12)"
crear "mejora"              "0ea5e9" "Tipo: mejora"
crear "correccion"          "e11d48" "Tipo: corrección"
crear "urgente"             "dc2626" "Cambio urgente (§13)"
crear "impacto-bajo"        "a7f3d0" "Clasificación §5: impacto bajo"
crear "impacto-medio"       "fde68a" "Clasificación §5: impacto medio"
crear "impacto-alto"        "fca5a5" "Clasificación §5: impacto alto"

echo "Etiquetas listas en $REPO"
