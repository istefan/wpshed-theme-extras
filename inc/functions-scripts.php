<?php
/**
 * Scripts.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Enqueue scripts and styles.
 */
function wte_front_end_scripts() {

	// Register scripts & styles
    wp_register_script( 'wte-flex-slider',  WTE_JS . 'flex-slider.js', array( 'jquery' ), '2.6.0', true );
    wp_register_script( 'wte-scroll-to',    WTE_JS . 'scroll-to.js', array( 'jquery' ), '1.4.5', true );
    wp_register_script( 'wte-local-scroll', WTE_JS . 'local-scroll.js', array( 'jquery' ), '1.2.8b', true );
    wp_register_script( 'wte-fade-up',      WTE_JS . 'fade-up.js', array( 'jquery' ), '1.0.0', true );

    wp_register_style( 'wte-genericons',    WTE_CSS . 'genericons/genericons.css', array(), '3.4.1' );
    wp_register_style( 'wte-style',         WTE_CSS . 'style.css', array(), '1.0.0' );

    // Add Genericons.
    wp_enqueue_style( 'wte-genericons' );

    // Load custom styles.
    wp_enqueue_style( 'wte-style' );

    // Flex Slider JS.
	if ( wte_get_option( 'slider_cpt' ) == 1 ) {
		wp_enqueue_script( 'wte-flex-slider' );
    }

    // Local Scroll JS.
    if ( wte_get_option( 'local_scroll' ) == 1 ) {
        wp_enqueue_script( 'wte-scroll-to' );
        wp_enqueue_script( 'wte-local-scroll' );
    }

    // Fade-up effect JS.
    if ( wte_get_option( 'fade_up' ) == 1 ) {
        wp_enqueue_script( 'wte-fade-up' );
    }

}
add_action( 'wp_enqueue_scripts', 'wte_front_end_scripts', 9998 );


/**
 * Prints footer scripts.
 */
function wte_footer_scripts() {

    if ( wte_get_option( 'slider_cpt' ) == 1 ) {
        echo "<script type='text/javascript'>";
        echo '( function( $ ) {';
        echo '$( ".flexslider" ).flexslider( { animation: "fade" } );';
        echo '} )( jQuery );';
        echo '</script>' . "\n";
    }

    if ( wte_get_option( 'local_scroll' ) == 1 ) {
        echo "<script type='text/javascript'>";
        echo '( function( $ ) {';
        echo '$.localScroll( { duration: 750 } );';
        echo '} )( jQuery );';
        echo '</script>' . "\n";
    }

}
add_action( 'wp_footer', 'wte_footer_scripts', 9999 );


/**
 * Enqueue admin scripts.
 */
function wte_admin_scripts() {

    global $pagenow, $wp_customize;

    if ( 'widgets.php' === $pagenow || isset( $wp_customize ) ) {

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );

        wp_enqueue_script( 'wte-color-picker', WTE_JS . 'color-picker.js', array( 'jquery' ) );
        wp_enqueue_script( 'wte-image-upload', WTE_JS . 'upload.js', array( 'jquery' ) );
        wp_enqueue_style( 'wte-image-upload', WTE_CSS  . 'upload.css' );

    }

}
add_action( 'admin_enqueue_scripts', 'wte_admin_scripts' );

