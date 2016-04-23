<?php
/*
 * Plugin Name: WPshed Theme Extras
 * Plugin URI: http://wpshed.com
 * Description: WPshed Theme Extras add powerful features to your WordPress Theme. It is designed to work flawlessly with WPshed Themes, but all featured can be used in any other theme as well (custom post types, widgets and short-codes).
 * Version: 1.1.0
 * Author: Stefan I.
 * Author URI: http://wpshed.com
 * License: GPLv2 or later
 */


/**
 * Load textdomain
 */
function wte_load_textdomain() {
	load_plugin_textdomain( 'wte', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'wte_load_textdomain' );


/**
 * Set default options on plugin activation.
 */
function wte_activate() {
	
	$defaults = array(
		'portfolio_cpt' 		=> 0,
		'portfolio_cat' 		=> 0,
		'portfolio_tag' 		=> 0,
		'slug'        			=> 'portfolio',
		'testimonial_cpt'		=> 0,
		'slider_cpt'        	=> 0,
		'portfolio_widget'  	=> 1,
		'testimonial_widget'	=> 1,
		'slider_widget'     	=> 1,
		'feat_post_widget'  	=> 1,
		'feat_page_widget'  	=> 1,
		'user_profile_widget'	=> 1,
		'services_widget'       => 1,
		'price_table_widget'    => 1,
		'hero_widget'        	=> 1,
		'image_widget'        	=> 1,
		'social_widget'        	=> 1,
		'cta_widget'        	=> 1,
		'fade_up '        		=> 0,
		'fade_in '        		=> 0,
		'local_scroll'        	=> 0,
		'portfolio_thumb_w'     => 1280,
		'portfolio_thumb_h'     => 800,
		'testimonial_thumb_w'   => 96,
		'testimonial_thumb_h'   => 96,
		'slider_thumb_w'       	=> 1920,
		'slider_thumb_h'       	=> 800,
	);

	$options = get_option( 'wte' );

    if ( is_array( $options ) ) {
        foreach ( $defaults as $key => $value ) {

        	// Update new options
		    if ( ! isset( $options[$key] ) ) {
		        $options[$key] = $value;
		        update_option( 'wte', $options );
		    }

        }
    } else {

    	// Set default options
        add_option( 'wte', $defaults );
    }

}
register_activation_hook( __FILE__, 'wte_activate' );


/**
 * Constants.
 */
define( 'WTE_PLUGIN_DIR', 	trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WTE_PLUGIN_URI', 	trailingslashit( plugins_url( '', __FILE__ ) ) );

define( 'WTE_INC', 			trailingslashit( WTE_PLUGIN_DIR . 'inc' ) );
define( 'WTE_WIDGETS', 		trailingslashit( WTE_INC . 'widgets' ) );

define( 'WTE_JS', 			trailingslashit( WTE_PLUGIN_URI . 'js' ) );
define( 'WTE_CSS', 			trailingslashit( WTE_PLUGIN_URI . 'css' ) );
define( 'WTE_IMAGES', 		trailingslashit( WTE_PLUGIN_URI . 'images' ) );


/**
 * Do shortcodes in widgets.
 */
add_filter( 'widget_text', 'do_shortcode' );


/**
 * Functions.
 */
require WTE_INC . 'functions-options.php';
require WTE_INC . 'functions-formatting.php';
require WTE_INC . 'functions-image.php';
require WTE_INC . 'functions-forms.php';
require WTE_INC . 'functions-fade-up.php';
require WTE_INC . 'functions-shortcodes.php';
require WTE_INC . 'functions-scripts.php';
require WTE_INC . 'functions-widgets.php';

if ( is_admin() ) {
	require WTE_INC . 'functions-admin.php';
	require WTE_INC . 'functions-admin-settings.php';
	require WTE_INC . 'functions-admin-widgets.php';
	require WTE_INC . 'functions-admin-themes.php';
	require WTE_INC . 'functions-admin-help.php';
}


/**
 * Custom Post Types.
 */
require WTE_INC . 'post-type-portfolio.php';
require WTE_INC . 'post-type-testimonials.php';
require WTE_INC . 'post-type-slides.php';


/**
 * Widgets.
 */
require WTE_WIDGETS . 'call-to-action-widget.php';
require WTE_WIDGETS . 'featured-page-widget.php';
require WTE_WIDGETS . 'featured-post-widget.php';
require WTE_WIDGETS . 'hero-widget.php';
require WTE_WIDGETS . 'image-widget.php';
require WTE_WIDGETS . 'portfolio-widget.php';
require WTE_WIDGETS . 'price-table-widget.php';
require WTE_WIDGETS . 'services-widget.php';
require WTE_WIDGETS . 'slider-widget.php';
require WTE_WIDGETS . 'social-widget.php';
require WTE_WIDGETS . 'testimonial-widget.php';
require WTE_WIDGETS . 'user-profile-widget.php';


/**
 * Registers support for various WordPress features.
 */
function wte_theme_setup() {

	// Enable support for Post Thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'portfolio-thumbnail', 
		wte_get_option( 'portfolio_thumb_w', '1280' ), 
		wte_get_option( 'portfolio_thumb_h', '800' ), 
		true 
	);
	add_image_size( 'testimonial-thumbnail',
		wte_get_option( 'testimonial_thumb_w', '96' ),
		wte_get_option( 'testimonial_thumb_h', '96' ),
		true 
	);
	add_image_size( 'slider-thumbnail', 
		wte_get_option( 'slider_thumb_w', '1920' ), 
		wte_get_option( 'slider_thumb_h', '800' ), 
		true 
	);

}
add_action( 'after_setup_theme', 'wte_theme_setup' );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function wte_body_classes( $classes ) {
	
	// Adds a `fadein` class to the body.
	if ( wte_get_option( 'fade_in' ) == 1 ) {
		$classes[] = 'fadein';
	}

	return $classes;
}
add_filter( 'body_class', 'wte_body_classes' );
