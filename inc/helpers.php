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

if ( ! function_exists( 'stm_get_hoverable_thumbs_child' ) ) {
	function stm_get_hoverable_thumbs_child( $returned_value, $listing_id, $thumb_size = 'thumbnail' ) {
		//$thumb_size = 'full';

		if ($thumb_size == 'stm-img-255-135' )  $thumb_size = 'stm-img-255-175';
		$ids   = array_unique( (array) get_post_meta( $listing_id, 'gallery', true ) );
		$count = 0;

		// push featured image id
		if ( has_post_thumbnail( $listing_id ) && ! in_array( get_post_thumbnail_id( $listing_id ), $ids, true ) ) {
			array_unshift( $ids, get_post_thumbnail_id( $listing_id ) );
		}

		$returned_value = array(
			'gallery'   => array(),
			'remaining' => 0,
		);

		$ids = array_filter( $ids );

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $attachment_id ) {
				// only first five images!
				if ( $count >= 5 ) {
					continue;
				}

				$img = wp_get_attachment_image_url( $attachment_id, $thumb_size );

				if ( ! empty( $img ) ) {
					if ( has_image_size( $thumb_size . '-x-2' ) ) {
						$imgs   = array();
						$imgs[] = $img;
						$imgs[] = wp_get_attachment_image_url( $attachment_id, $thumb_size . '-x-2' );
						$img    = $imgs;
					}

					array_push( $returned_value['gallery'], $img );
					$count ++;
				}
			}
		}

		// get remaining count of gallery images
		$remaining                   = count( $ids ) - count( $returned_value['gallery'] );
		$returned_value['remaining'] = ( 0 <= $remaining ) ? $remaining : 0;

		return $returned_value;
	}

	add_filter( 'stm_get_hoverable_thumbs', 'stm_get_hoverable_thumbs_child', 30, 3 );
}

add_image_size( 'stm-img-255-175', 255, 175, true );