<?php

add_filter( 'stm_ew_locate_template', function ( $located, $templates ) {

	$plugin_path = MOTORS_ELEMENTOR_CUSTOMS_PATH;
	$locat       = false;

	foreach ( (array) $templates as $template ) {
		if ( substr( $template, - 4 ) !== '.php' ) {
			$template .= '.php';
		}
		$locat = $plugin_path . '/templates/' . $template;

		if ( file_exists( $locat ) ) {

			$located = $locat;

			break;
		}
	}

	return $located;
}, 10, 2 );
function stm_customs_enqueue_scripts() {
	wp_enqueue_style( 'stm-style-customs', MOTORS_ELEMENTOR_CUSTOMS_URL . '/assets/css/style.css', array(), time() );
}

function stm_customs_enqueue_on_plugins_loaded() {
	add_action( 'wp_enqueue_scripts', 'stm_customs_enqueue_scripts', 999 );
}

add_action( 'plugins_loaded', 'stm_customs_enqueue_on_plugins_loaded' );

function modify_image_size() {
    add_image_size('stm-img-255-135', 300, 175, true);
}

add_action('plugins_loaded', 'modify_image_size');






