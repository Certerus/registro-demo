#!/usr/bin/env bash
# Verifica que la versión sea identificable y consistente (política §7):
#   - cabecera "Version:" del plugin
#   - constante REGISTRO_DEMO_VERSION
#   - entrada en CHANGELOG.md
#   - (opcional) etiqueta de Git vX.Y.Z
#
# Uso:
#   scripts/verificar-version.sh                 # valida consistencia
#   scripts/verificar-version.sh --imprimir      # valida e imprime la versión
#   scripts/verificar-version.sh --etiqueta v1.4.0
set -euo pipefail

cd "$(dirname "$0")/.."
ARCHIVO="registro-demo/registro-demo.php"

CABECERA=$(sed -n 's/^[[:space:]]*\*[[:space:]]*Version:[[:space:]]*\([0-9][0-9.]*\).*/\1/p' "$ARCHIVO" | head -1)
CONSTANTE=$(sed -n "s/.*define([[:space:]]*'REGISTRO_DEMO_VERSION',[[:space:]]*'\([0-9][0-9.]*\)'.*/\1/p" "$ARCHIVO" | head -1)

error() { echo "::error::$1" >&2; exit 1; }

[ -n "$CABECERA" ]  || error "No se encontró 'Version:' en la cabecera de $ARCHIVO"
[ -n "$CONSTANTE" ] || error "No se encontró la constante REGISTRO_DEMO_VERSION en $ARCHIVO"
[ "$CABECERA" = "$CONSTANTE" ] || error "Versión inconsistente: cabecera=$CABECERA, constante=$CONSTANTE"
grep -q "^## \[$CABECERA\]" CHANGELOG.md || error "CHANGELOG.md no tiene entrada para la versión $CABECERA"

case "${1:-}" in
	--imprimir)
		echo "$CABECERA"
		;;
	--etiqueta)
		ETIQUETA="${2:-}"
		[ "$ETIQUETA" = "v$CABECERA" ] || error "La etiqueta '$ETIQUETA' no coincide con la versión del código (v$CABECERA)"
		echo "Etiqueta $ETIQUETA coincide con la versión del código."
		;;
	*)
		echo "Versión consistente: $CABECERA (cabecera, constante y CHANGELOG)."
		;;
esac
