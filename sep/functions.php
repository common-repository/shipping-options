<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages product functions
 *
 * Here all plugin functions are defined and managed.
 *
 * @version        1.0.0
 * @package        shipping-options/functions
 * @author        Norbert Dreszer
 */

/**
 * Returns active post types except al_product related
 *
 * @return type
 */
function get_shipping_active_post_types() {
	$settings   = get_shipping_options_settings();
	$post_types = array_filter( $settings['enabled'], 'ic_filter_post_types_array' );

	return $post_types;
}

/**
 * Returns post types where shipping shows up automatically
 *
 * @return type
 */
function get_shipping_show_active_post_types() {
	$settings   = get_shipping_options_settings();
	$post_types = array_filter( $settings['show'], 'ic_filter_post_types_array' );

	return $post_types;
}

if ( ! function_exists( 'ic_filter_post_types_array' ) ) {

	/**
	 * Deletes all product post types from array
	 *
	 * @param type $string
	 *
	 * @return type
	 */
	function ic_filter_post_types_array( $string ) {
		return strpos( $string, 'al_product' ) === false;
	}

}

if ( ! function_exists( 'get_external_single_names' ) ) {

	/**
	 * Defines single name for shipping if catalog is missing
	 * @return string
	 */
	function get_external_single_names() {
		if ( function_exists( 'get_single_names' ) ) {
			$single_names = get_single_names();
		} else {
			$single_names = array( 'product_price'    => __( 'Price', 'shipping-options' ) . ':',
			                       'product_features' => __( 'Features', 'shipping-options' ),
			                       'product_shipping' => __( 'Shipping', 'shipping-options' ) . ':'
			);
		}

		return $single_names;
	}

}

add_shortcode( 'shipping_options', 'ic_shipping_options_shortcode' );

/**
 * Defines shipping field table shortcode
 *
 * @param type $atts
 *
 * @return type
 */
function ic_shipping_options_shortcode( $atts ) {
	$args         = shortcode_atts( array(
		'id' => get_the_ID(),
	), $atts );
	$single_names = get_external_single_names();

	return get_shipping_options_table( $args['id'], $single_names );
}

/**
 * Shows shipping field
 *
 * @param type $id
 */
function ic_shipping_options( $id = null ) {
	$id           = empty( $id ) ? get_the_ID() : $id;
	$single_names = get_external_single_names();
	echo get_shipping_options_table( $id, $single_names );
}

add_filter( 'the_content', 'show_auto_shipping_options' );

/**
 * Shows shipping on certain post types
 *
 * @param type $content
 *
 * @return type
 */
function show_auto_shipping_options( $content ) {
	if ( is_ic_admin() ) {
		return $content;
	}
	$post_type               = get_post_type();
	$shipping_show_post_type = get_shipping_show_active_post_types();
	if ( in_array( $post_type, $shipping_show_post_type ) && ! is_ic_catalog_page() ) {
		ob_start();
		ic_show_template_file( 'product-page/product-shipping.php', AL_SHIPPING_BASE_PATH );
		$content .= ob_get_clean();
		//$single_names = get_external_single_names();
		//$content .= '<style>.shipping-table {width: auto;}</style>';
		//$content .= get_shipping_options_table( get_the_ID(), $single_names );
	}

	return $content;
}
