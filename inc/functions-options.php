<?php
/**
 * Plugin Options.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Return option from the options table and cache result.
 */
function wte_get_option( $key, $default = null, $setting = null ) {

	// The default is set here, so it doesn't have to be repeated in the function arguments for wte_option() too.
	$setting = $setting ? $setting : apply_filters( 'wte_settings_field', 'wte' );

	// Allow a theme to short-circuit this function
	$pre = apply_filters( "wte_pre_get_option_{$key}", null, $setting );
	if ( null !== $pre ) {
		return $pre;
	}

	// Return settings field, slashes stripped, sanitized if string
	$opt = get_option( $setting );

	if ( ! isset( $opt[$key] ) )
		return $default;

	return is_array( $opt[$key] ) ? stripslashes_deep( $opt[$key] ) : stripslashes( wp_kses_decode_entities( $opt[$key] ) );
}


/**
 * Echo options from the options database.
 */
function wte_option( $key, $default = null, $setting = null ) {
	echo wte_get_option( $key, $default, $setting );
}


/**
 * Echo data from a post or page custom field.
 */
function wte_cf( $field, $output_pattern = '%s' ) {
	if ( $value = wte_get_cf( $field ) )
		printf( $output_pattern, $value );
}


/**
 * Return custom field post meta data.
 */
function wte_get_cf( $field, $post_id = null ) {

	// Use get_the_ID() if no $post_id is specified
	$post_id = ( null !== $post_id ? $post_id : get_the_ID() );

	if ( null === $post_id ) {
		return '';
	}

	$cf = get_post_meta( $post_id, $field, true );

	if ( ! $cf ) {
		return '';
	}

	// Return custom field, slashes stripped, sanitized if string
	return is_array( $cf ) ? stripslashes_deep( $cf ) : stripslashes( wp_kses_decode_entities( $cf ) );

}
