<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages separate shipping settings
 *
 * Here shipping settings are defined and managed.
 *
 * @version		1.0.0
 * @package		shipping-options/sep
 * @author 		Norbert Dreszer
 */
add_action( 'admin_menu', 'register_shipping_options_settings_menu' );

/**
 * Adds shipping field submenu to WordPress Settings menu
 */
function register_shipping_options_settings_menu() {
	add_options_page( __( 'Shipping Options', 'shipping-options' ), __( 'Shipping Options', 'shipping-options' ), 'manage_options', 'ic_shipping', 'shipping_options_settings' );
}

add_action( 'admin_init', 'register_shipping_options_settings', 20 );

/**
 * Registers shipping field settings
 */
function register_shipping_options_settings() {
	register_setting( 'ic_shipping_options', 'shipping_options_settings' );
	if ( !defined( 'AL_BASE_PATH' ) ) {
		register_setting( 'product_shipping', 'product_shipping_options_number' );
		register_setting( 'product_shipping', 'display_shipping' );
		register_setting( 'product_shipping', 'product_shipping_cost' );
		register_setting( 'product_shipping', 'product_shipping_label' );
	}
}

/**
 * Sets default shipping field settings
 *
 * @return type
 */
function default_shipping_options_settings() {
	return array( 'enabled' => array( 'al_product' ), 'show' => array( '' ) );
}

/**
 * Returns shipping field settings
 *
 * @return type
 */
function get_shipping_options_settings() {
	$settings = wp_parse_args( get_option( 'shipping_options_settings' ), default_shipping_options_settings() );
	return $settings;
}

/**
 * Shows shipping field settings fields
 *
 */
function shipping_options_settings() {
	$post_types					 = get_post_types( array( 'publicly_queryable' => true ), 'objects' );
	unset( $post_types[ 'attachment' ] );
	echo '<h2>' . __( 'Settings', 'shipping-options' ) . ' - impleCode Shipping Options</h2>';
	echo '<h3>' . __( 'General Shipping Settings', 'shipping-options' ) . '</h3>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'ic_shipping_options' );
	$shipping_options_settings	 = get_shipping_options_settings();
	echo '<h4>' . __( 'Enable Shipping for', 'shipping-options' ) . ':</h4>';
	$checked					 = in_array( 'page', $shipping_options_settings[ 'enabled' ] ) ? 'checked' : '';
	echo '<input ' . $checked . ' type="checkbox" name="shipping_options_settings[enabled][]" value="page"> ' . __( 'Pages', 'shipping-options' ) . '<br>';
	foreach ( $post_types as $type_key => $type_obj ) {
		if ( strpos( $type_key, 'al_product' ) !== 0 ) {
			$checked = in_array( $type_key, $shipping_options_settings[ 'enabled' ] ) ? 'checked' : '';
			echo '<input ' . $checked . ' type="checkbox" name="shipping_options_settings[enabled][]" value="' . $type_key . '"> ' . $type_obj->labels->name . '<br>';
		}
	}
	echo '<h4>' . __( 'Show Shipping Automatically on', 'shipping-options' ) . ':</h4>';
	$checked = in_array( 'page', $shipping_options_settings[ 'show' ] ) ? 'checked' : '';
	echo '<input ' . $checked . ' type="checkbox" name="shipping_options_settings[show][]" value="page"> ' . __( 'Pages', 'shipping-options' ) . '<br>';
	foreach ( $post_types as $type_key => $type_obj ) {
		if ( strpos( $type_key, 'al_product' ) !== 0 ) {
			$checked = in_array( $type_key, $shipping_options_settings[ 'show' ] ) ? 'checked' : '';
			echo '<input ' . $checked . ' type="checkbox" name="shipping_options_settings[show][]" value="' . $type_key . '"> ' . $type_obj->labels->name . '<br>';
		}
	}
	echo '<div class="al-box" style="margin-top: 10px;">' . __( 'You can also display shipping with', 'shipping-options' ) . ': <ol><li>' . sprintf( __( '%s shortcode placed in content.', 'shipping-options' ), '<code>' . esc_html( '[shipping_options]' ) . '</code>' ) . '</li><li>' . sprintf( __( '%s code placed in template file.', 'shipping-options' ), '<code>' . esc_html( '<?php ic_shipping_options() ?>' ) . '</code>' ) . '</li></ol></div>';
	echo '<p class="submit"><input type="submit" class="button-primary" value="' . __( 'Save changes', 'shipping-options' ) . '"/></p>';
	echo '</form>';
	if ( !defined( 'AL_BASE_PATH' ) ) {
		echo '<style>.al-box {max-width: 350px;padding: 10px;border: 1px solid;}.plugin-logo {
		position: absolute;
		right: 0px;
		bottom: 25px;
		z-index: 9999;
	} .product-settings-table {width: auto;} .al-box.info {margin: 10px 0;} #admin-number-field {max-width: 60px}</style>';
		$info = __( 'The table below controls the shipping default values.', 'shipping-options' );
		shipping_settings_fields( $info );
	}
	echo '<div class="plugin-logo"><a href="https://implecode.com/#cam=shipping-options-settings&key=logo-link"><img class="en" src="' . AL_SHIPPING_BASE_URL . '/img/implecode.png' . '" width="282px" alt="impleCode" /></a></div>';
}
