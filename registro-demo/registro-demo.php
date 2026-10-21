<?php
/**
 * Plugin Name:       Registro Demo
 * Description:       Plugin de demostración para el ejercicio de gestión de cambios. Registra constancias y las muestra con el shortcode [registro_demo].
 * Version:           1.3.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Mentor (demostración)
 * License:           GPL-2.0-or-later
 * Text Domain:       registro-demo
 */

defined( 'ABSPATH' ) || exit;

define( 'REGISTRO_DEMO_VERSION', '1.3.0' );
define( 'REGISTRO_DEMO_DIR', plugin_dir_path( __FILE__ ) );
define( 'REGISTRO_DEMO_URL', plugin_dir_url( __FILE__ ) );

require_once REGISTRO_DEMO_DIR . 'includes/class-formato.php';
require_once REGISTRO_DEMO_DIR . 'includes/class-plugin.php';

Registro_Demo_Plugin::iniciar();
