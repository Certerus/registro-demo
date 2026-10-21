#!/usr/bin/env bash
# Revisión básica de secretos en texto plano (política §7).
# Complementa el "secret scanning" y "push protection" nativos de GitHub.
set -euo pipefail

cd "$(dirname "$0")/.."

PATRONES=(
	'-----BEGIN [A-Z ]*PRIVATE KEY-----'
	'gh[pousr]_[A-Za-z0-9]{36,}'
	'AKIA[0-9A-Z]{16}'
	"(DB_PASSWORD|AUTH_KEY|SECURE_AUTH_KEY)['\"]?[[:space:]]*,[[:space:]]*['\"][^'\"]{6,}"
	"(password|contrasena|contraseña|token|api_key|apikey|secret)[[:space:]]*[:=][[:space:]]*['\"][^'\"]{6,}['\"]"
)

HALLAZGOS=0
for patron in "${PATRONES[@]}"; do
	if grep -rInEi --exclude-dir={.git,vendor,scripts,.github} -- "$patron" . ; then
		HALLAZGOS=1
	fi
done

if [ "$HALLAZGOS" -ne 0 ]; then
	echo "::error::Se detectaron posibles secretos en texto plano. Muévelos a GitHub Secrets."
	exit 1
fi

echo "Sin secretos detectados en el código fuente."
