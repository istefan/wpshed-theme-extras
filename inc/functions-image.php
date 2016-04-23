<?php
/**
 * Image Functions.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Pull an attachment ID from a post, if one exists.
 */
function wte_get_image_id( $index = 0, $post_id = null ) {

	$image_ids = array_keys(
		get_children(
			array(
				'post_parent'    => $post_id ? $post_id : get_the_ID(),
				'post_type'	     => 'attachment',
				'post_mime_type' => 'image',
				'orderby'        => 'menu_order',
				'order'	         => 'ASC',
			)
		)
	);

	if ( isset( $image_ids[ $index ] ) ) {
		return $image_ids[ $index ];
	}

	return false;

}


/**
 * Return an image pulled from the media gallery.
 */
function wte_get_image( $args = array() ) {

	$defaults = array(
		'post_id'  => null,
		'format'   => 'html',
		'size'     => 'full',
		'num'      => 0,
		'attr'     => '',
		'fallback' => 'first-attached',
		'context'  => '',
	);

	// A filter on the default parameters used by `wte_get_image()`.
	$defaults = apply_filters( 'wte_get_image_default_args', $defaults, $args );

	$args = wp_parse_args( $args, $defaults );

	// Allow child theme to short-circuit this function
	$pre = apply_filters( 'wte_pre_get_image', false, $args, get_post() );
	if ( false !== $pre )
		return $pre;

	// If post thumbnail (native WP) exists, use its id
	if ( has_post_thumbnail( $args['post_id'] ) && ( 0 === $args['num'] ) ) {
		$id = get_post_thumbnail_id( $args['post_id'] );
	}
	// Else if the first (default) image attachment is the fallback, use its id
	elseif ( 'first-attached' === $args['fallback'] ) {
		$id = wte_get_image_id( $args['num'], $args['post_id'] );
	}
	// Else if fallback id is supplied, use it
	elseif ( is_int( $args['fallback'] ) ) {
		$id = $args['fallback'];
	}

	// If we have an id, get the html and url
	if ( isset( $id ) ) {
		$html = wp_get_attachment_image( $id, $args['size'], false, $args['attr'] );
		list( $url ) = wp_get_attachment_image_src( $id, $args['size'], false, $args['attr'] );
	}
	// Else if fallback html and url exist, use them
	elseif ( is_array( $args['fallback'] ) ) {
		$id   = 0;
		$html = $args['fallback']['html'];
		$url  = $args['fallback']['url'];
	}
	// Else, return false (no image)
	else {
		return false;
	}

	// Source path, relative to the root
	$src = str_replace( home_url(), '', $url );

	// Determine output
	if ( 'html' === mb_strtolower( $args['format'] ) )
		$output = $html;
	elseif ( 'url' === mb_strtolower( $args['format'] ) )
		$output = $url;
	else
		$output = $src;

	// Return false if $url is blank
	if ( empty( $url ) ) $output = false;

	// Return data, filtered
	return apply_filters( 'wte_get_image', $output, $args, $id, $html, $url, $src );
}


/**
 * Echo an image pulled from the media gallery.
 */
function wte_image( $args = array() ) {

	$image = wte_get_image( $args );

	if ( $image )
		echo $image;
	else
		return false;

}


/**
 * Return registered image sizes.
 */
function wte_get_additional_image_sizes() {

	global $_wp_additional_image_sizes;

	if ( $_wp_additional_image_sizes )
		return $_wp_additional_image_sizes;

	return array();

}


/**
 * Return all registered image sizes arrays, including the standard sizes.
 */
function wte_get_image_sizes() {

	$builtin_sizes = array(
		'large'		=> array(
			'width'  => get_option( 'large_size_w' ),
			'height' => get_option( 'large_size_h' ),
		),
		'medium'	=> array(
			'width'  => get_option( 'medium_size_w' ),
			'height' => get_option( 'medium_size_h' ),
		),
		'thumbnail'	=> array(
			'width'  => get_option( 'thumbnail_size_w' ),
			'height' => get_option( 'thumbnail_size_h' ),
			'crop'   => get_option( 'thumbnail_crop' ),
		),
	);

	$additional_sizes = wte_get_additional_image_sizes();

	return array_merge( $builtin_sizes, $additional_sizes );

}


/**
 * Return registered image sizes for customizer.
 */
function wte_get_image_sizes_for_customizer() {

	$sizes = array();

	foreach ( (array) wte_get_image_sizes() as $name => $size ) {
		$sizes[ $name ] = $name . ' (' . absint( $size['width'] ) . ' &#x000D7; ' . absint( $size['height'] ) . ')';
	}

	return $sizes;

}
