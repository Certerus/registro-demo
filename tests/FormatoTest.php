<?php

use PHPUnit\Framework\TestCase;

final class FormatoTest extends TestCase {

	public function test_convierte_fecha_iso_a_formato_local(): void {
		$this->assertSame( '20/10/2026', Registro_Demo_Formato::fecha( '2026-10-20' ) );
	}

	public function test_fecha_invalida_devuelve_guion(): void {
		$this->assertSame( '—', Registro_Demo_Formato::fecha( '2026-02-30' ) );
		$this->assertSame( '—', Registro_Demo_Formato::fecha( '' ) );
	}

	public function test_normaliza_folio(): void {
		$this->assertSame( 'CERT-0042', Registro_Demo_Formato::folio( '  cert-0042 ' ) );
		$this->assertSame( 'AB12', Registro_Demo_Formato::folio( 'a b/1*2' ) );
	}
}
