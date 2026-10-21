<?php
/**
 * Utilidades de formato sin dependencias de WordPress (probadas con PHPUnit).
 */

defined( 'ABSPATH' ) || exit;

final class Registro_Demo_Formato {

	/**
	 * Convierte una fecha ISO (AAAA-MM-DD) a DD/MM/AAAA.
	 * Devuelve un guion largo si la fecha no es válida.
	 */
	public static function fecha( string $iso ): string {
		$fecha = DateTimeImmutable::createFromFormat( '!Y-m-d', $iso );

		if ( false === $fecha || $fecha->format( 'Y-m-d' ) !== $iso ) {
			return '—';
		}

		return $fecha->format( 'd/m/Y' );
	}

	/**
	 * Normaliza un folio: mayúsculas, sin espacios, solo letras, números y guiones.
	 */
	public static function folio( string $folio ): string {
		$folio = strtoupper( trim( $folio ) );

		return (string) preg_replace( '/[^A-Z0-9\-]/', '', $folio );
	}
}
