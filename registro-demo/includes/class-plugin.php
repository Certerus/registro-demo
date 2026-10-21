<?php
/**
 * Integración con WordPress: tipo de contenido, campos, shortcode,
 * endpoint de salud y aviso de ambiente.
 */

defined( 'ABSPATH' ) || exit;

final class Registro_Demo_Plugin {

	const TIPO      = 'rd_registro';
	const NONCE     = 'registro_demo_guardar';
	const NAMESPACE = 'registro-demo/v1';

	public static function iniciar(): void {
		add_action( 'init', array( __CLASS__, 'registrar_tipo' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'agregar_campos' ) );
		add_action( 'save_post_' . self::TIPO, array( __CLASS__, 'guardar_campos' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'estilos' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'registrar_rutas' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'aviso_ambiente' ) );
		add_action( 'admin_notices', array( __CLASS__, 'aviso_ambiente_admin' ) );
		add_shortcode( 'registro_demo', array( __CLASS__, 'shortcode' ) );
	}

	/* ------------------------------------------------------------------
	 * Tipo de contenido
	 * ------------------------------------------------------------------ */

	public static function registrar_tipo(): void {
		register_post_type(
			self::TIPO,
			array(
				'labels'       => array(
					'name'          => __( 'Registros', 'registro-demo' ),
					'singular_name' => __( 'Registro', 'registro-demo' ),
					'add_new_item'  => __( 'Agregar registro', 'registro-demo' ),
					'edit_item'     => __( 'Editar registro', 'registro-demo' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'menu_icon'    => 'dashicons-id-alt',
				'supports'     => array( 'title' ),
				'show_in_rest' => false,
			)
		);
	}

	/**
	 * Campos del registro: clave de meta => etiqueta y tipo de control.
	 */
	public static function campos(): array {
		return array(
			'rd_folio'         => array( __( 'Folio', 'registro-demo' ), 'text' ),
			'rd_fecha_emision' => array( __( 'Fecha de emisión', 'registro-demo' ), 'date' ),
		);
	}

	public static function agregar_campos(): void {
		add_meta_box(
			'registro_demo_datos',
			__( 'Datos del registro', 'registro-demo' ),
			array( __CLASS__, 'pintar_campos' ),
			self::TIPO,
			'normal',
			'high'
		);
	}

	public static function pintar_campos( WP_Post $post ): void {
		wp_nonce_field( self::NONCE, self::NONCE );

		foreach ( self::campos() as $clave => list( $etiqueta, $tipo ) ) {
			$valor = (string) get_post_meta( $post->ID, $clave, true );
			printf(
				'<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" class="regular-text"></p>',
				esc_attr( $clave ),
				esc_html( $etiqueta ),
				esc_attr( $tipo ),
				esc_attr( $valor )
			);
		}
	}

	public static function guardar_campos( int $post_id ): void {
		if ( ! isset( $_POST[ self::NONCE ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE ] ) ), self::NONCE ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		foreach ( self::campos() as $clave => list( , $tipo ) ) {
			if ( ! isset( $_POST[ $clave ] ) ) {
				continue;
			}
			$valor = sanitize_text_field( wp_unslash( $_POST[ $clave ] ) );
			if ( 'rd_folio' === $clave ) {
				$valor = Registro_Demo_Formato::folio( $valor );
			}
			update_post_meta( $post_id, $clave, $valor );
		}
	}

	/* ------------------------------------------------------------------
	 * Sitio público
	 * ------------------------------------------------------------------ */

	public static function estilos(): void {
		wp_register_style( 'registro-demo', REGISTRO_DEMO_URL . 'assets/registro-demo.css', array(), REGISTRO_DEMO_VERSION );
	}

	public static function shortcode(): string {
		wp_enqueue_style( 'registro-demo' );

		$registros = get_posts(
			array(
				'post_type'      => self::TIPO,
				'post_status'    => 'publish',
				'posts_per_page' => 50,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		ob_start();
		?>
		<div class="registro-demo">
			<table class="registro-demo__tabla">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Folio', 'registro-demo' ); ?></th>
						<th><?php esc_html_e( 'Titular', 'registro-demo' ); ?></th>
						<th><?php esc_html_e( 'Emisión', 'registro-demo' ); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php if ( empty( $registros ) ) : ?>
					<tr><td colspan="3"><?php esc_html_e( 'No hay registros.', 'registro-demo' ); ?></td></tr>
				<?php endif; ?>
				<?php foreach ( $registros as $registro ) : ?>
					<tr>
						<td><?php echo esc_html( (string) get_post_meta( $registro->ID, 'rd_folio', true ) ); ?></td>
						<td><?php echo esc_html( get_the_title( $registro ) ); ?></td>
						<td><?php echo esc_html( Registro_Demo_Formato::fecha( (string) get_post_meta( $registro->ID, 'rd_fecha_emision', true ) ) ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<p class="registro-demo__version">Registro Demo v<?php echo esc_html( REGISTRO_DEMO_VERSION ); ?></p>
		</div>
		<?php
		return (string) ob_get_clean();
	}

	/* ------------------------------------------------------------------
	 * Endpoint de salud (usado por la validación posterior al despliegue)
	 * ------------------------------------------------------------------ */

	public static function registrar_rutas(): void {
		register_rest_route(
			self::NAMESPACE,
			'/salud',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => array( __CLASS__, 'salud' ),
			)
		);
	}

	public static function salud(): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'estado'   => post_type_exists( self::TIPO ) ? 'ok' : 'error',
				'plugin'   => 'registro-demo',
				'version'  => REGISTRO_DEMO_VERSION,
				'ambiente' => wp_get_environment_type(),
			)
		);
	}

	/* ------------------------------------------------------------------
	 * Aviso visible en ambientes que no son producción
	 * ------------------------------------------------------------------ */

	private static function texto_ambiente(): string {
		$ambiente = wp_get_environment_type();
		if ( 'production' === $ambiente ) {
			return '';
		}
		/* translators: 1: ambiente, 2: versión */
		return sprintf( __( 'AMBIENTE DE PRUEBAS (%1$s) · Registro Demo v%2$s', 'registro-demo' ), $ambiente, REGISTRO_DEMO_VERSION );
	}

	public static function aviso_ambiente(): void {
		$texto = self::texto_ambiente();
		if ( '' !== $texto ) {
			echo '<div class="registro-demo-ambiente" style="background:#b45309;color:#fff;text-align:center;font:600 14px/1.4 system-ui,sans-serif;padding:8px 12px;">' . esc_html( $texto ) . '</div>';
		}
	}

	public static function aviso_ambiente_admin(): void {
		$texto = self::texto_ambiente();
		if ( '' !== $texto ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html( $texto ) . '</strong></p></div>';
		}
	}
}
