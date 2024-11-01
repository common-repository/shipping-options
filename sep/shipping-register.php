<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages product post type
 *
 * Here all product fields are defined.
 *
 * @version        1.1.1
 * @package        shipping-options/includes
 * @author        Norbert Dreszer
 */
add_action( 'add_meta_boxes', 'add_shipping_options_metaboxes' );

/**
 * Hook shipping meta box to selected post types
 *
 */
function add_shipping_options_metaboxes() {
	$post_types = get_shipping_active_post_types();
	foreach ( $post_types as $post_type ) {
		add_action( 'add_meta_boxes_' . $post_type, 'add_shipping_options_metabox' );
	}
}

/**
 * Add shipping meta box
 *
 * @param type $post
 */
function add_shipping_options_metabox( $post ) {
	add_meta_box( 'al_product_shipping', __( 'Shipping', 'shipping-options' ), 'al_product_shipping', $post->post_type, 'side', 'default' );
}

add_action( 'post_updated', 'ic_save_shipping_meta', 1, 2 );

/**
 * Save shipping meta field
 *
 * @param type $post_id
 * @param type $post
 * @return type
 */
function ic_save_shipping_meta( $post_id, $post ) {
	$post_types = get_shipping_active_post_types();
	if ( in_array( $post->post_type, $post_types ) ) {
		$shippingmeta_noncename = isset( $_POST[ 'shippingmeta_noncename' ] ) ? $_POST[ 'shippingmeta_noncename' ] : '';
		if ( !empty( $shippingmeta_noncename ) && !wp_verify_nonce( $shippingmeta_noncename, AL_BASE_PATH . 'shipping_meta' ) ) {
			return $post->ID;
		}
		if ( !isset( $_POST[ 'action' ] ) ) {
			return $post->ID;
		} else if ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] != 'editpost' ) {
			return $post->ID;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post->ID;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $post->ID;
		}
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}
		$count = get_shipping_options_number();
		for ( $i = 1; $i <= $count; $i++ ) {
			$shipping_meta[ '_shipping' . $i ]		 = isset( $_POST[ '_shipping' . $i ] ) ? $_POST[ '_shipping' . $i ] : '';
			$shipping_meta[ '_shipping-label' . $i ] = !empty( $_POST[ '_shipping-label' . $i ] ) ? $_POST[ '_shipping-label' . $i ] : '';
		}
		foreach ( $shipping_meta as $key => $value ) {
			$current_value = get_post_meta( $post->ID, $key, true );
			if ( isset( $value ) && !isset( $current_value ) ) {
				add_post_meta( $post->ID, $key, $value, true );
			} else if ( isset( $value ) && $value != $current_value ) {
				update_post_meta( $post->ID, $key, $value );
			} else if ( !isset( $value ) && $current_value ) {
				delete_post_meta( $post->ID, $key );
			}
		}
	}
}
