<?php
// Las clases probadas no dependen de WordPress; solo necesitan ABSPATH definido.
define( 'ABSPATH', __DIR__ . '/' );

foreach ( glob( dirname( __DIR__ ) . '/registro-demo/includes/class-*.php' ) as $archivo ) {
	if ( str_ends_with( $archivo, 'class-plugin.php' ) ) {
		continue; // Requiere WordPress.
	}
	require_once $archivo;
}
