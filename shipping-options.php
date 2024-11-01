<?php

/**
 * Plugin Name: Shipping Options
 * Description: Adds product shipping options for any post type. Full Product Catalog Simple integration.
 * Version: 1.1.9
 * Author: impleCode
 * Author URI: https://implecode.com/#cam=in-plugin-urls&key=author-url
 * Text Domain: shipping-options
 * Domain Path: /lang/

  Copyright: 2023 impleCode.
  License: GNU General Public License v3.0
  License URI: http://www.gnu.org/licenses/gpl-3.0.html */
if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'AL_SHIPPING_BASE_PATH', dirname( __FILE__ ) );
define( 'AL_SHIPPING_BASE_URL', plugins_url( '/', __FILE__ ) );

if ( !(is_admin() && isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'activate' && isset( $_GET[ 'plugin' ] ) && ($_GET[ 'plugin' ] == 'ecommerce-product-catalog/ecommerce-product-catalog.php' || $_GET[ 'plugin' ] == 'post-type-x/post-type-x.php') ) ) {
	add_action( 'post_type_x_addons', 'start_shipping_options', 5 );
	add_action( 'after_setup_theme', 'start_shipping_options', 16 );
}

function start_shipping_options() {
	if ( !defined( 'AL_BASE_PATH' ) || !function_exists( 'is_ic_shipping_enabled' ) ) {
		if ( !defined( 'AL_BASE_PATH' ) ) {
			define( 'AL_BASE_PATH', dirname( __FILE__ ) );
		}
		if ( !defined( 'AL_BASE_TEMPLATES_PATH' ) ) {
			define( 'AL_BASE_TEMPLATES_PATH', dirname( __FILE__ ) );
		}
		require_once(AL_SHIPPING_BASE_PATH . '/modules/index.php' );
	}
	require_once(AL_SHIPPING_BASE_PATH . '/sep/index.php' );
	remove_action( 'after_setup_theme', 'start_shipping_options', 16 );
}
