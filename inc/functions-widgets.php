<?php
/**
 * Functions - Widgets.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

/**
 * Get custom widgets.
 */
function wte_default_widgets() {

	// Get template slug
	$current_theme = strtolower( str_replace( '-', '_', get_option( 'template' ) ) );

	// Get custom widgets
	$custom_widgets = get_option( $current_theme . '_custom_widgets' );

	if ( $custom_widgets && ! empty( $custom_widgets ) ) :

		// Set custom widgets
		$widgets = $custom_widgets;

	else :

		// Set default custom widgets
		$widgets = array(
			apply_filters( $current_theme . '_default_sidebars', array(
				$current_theme . '_custom_sidebars_1' => array(
					'text_align'   	=> 'centered',
					'wrap'   		=> '',
					'class'   		=> 'custom-class',
					'padding'		=> '',
					'widgets'		=> array(
						$current_theme . '_custom_sidebars_1' => array(
							'name' 			=> esc_html__( 'Front Page 1', 'stef' ),
						),
					),
				),
				$current_theme . '_custom_sidebars_2' => array(
					'text_align'   	=> 'centered',
					'wrap'   		=> 'wrap',
					'padding'		=> 'true',
					'divider'		=> 'true',
					'widgets'		=> array(
						$current_theme . '_custom_sidebars_2_1' => array(
							'name' 			=> esc_html__( 'Front Page 2 - Left', 'stef' ),
							'before_title'  => '<h3>',
							'after_title'   => '</h3>',
							'grid'			=> 'one-third',
							'first'			=> 'true',
						),
						$current_theme . '_custom_sidebars_2_2' => array(
							'name' 			=> esc_html__( 'Front Page 2 - Middle', 'stef' ),
							'before_title'  => '<h3>',
							'after_title'   => '</h3>',
							'grid'			=> 'one-third',
						),
						$current_theme . '_custom_sidebars_2_3' => array(
							'name' 			=> esc_html__( 'Front Page 2 - Right', 'stef' ),
							'before_title'  => '<h3>',
							'after_title'   => '</h3>',
							'grid'			=> 'one-third',
						),
					),
				),
				$current_theme . '_custom_sidebars_3' => array(
					'text_align'   	=> 'centered',
					'wrap'   		=> 'small-wrap',
					'padding'		=> 'true',
					'divider'		=> 'true',
					'widgets'		=> array(
						$current_theme . '_custom_sidebars_3' => array(
							'name' 			=> esc_html__( 'Front Page 3', 'stef' ),
						),
					),
				),
				$current_theme . '_custom_sidebars_4' => array(
					'text_align'   	=> 'centered',
					'wrap'   		=> 'wrap',
					'padding'		=> 'true',
					'widgets'		=> array(
						$current_theme . '_custom_sidebars_4' => array(
							'name' 			=> esc_html__( 'Front Page 4', 'stef' ),
						),
					),
				),
			) )
		);

	endif;

	return $widgets;

}


/**
 * Register sidebars.
 */
function wte_widgets_init() {

	if ( current_theme_supports( 'wte-widgets' ) ) :

		$widgets = wte_default_widgets();

		foreach ( $widgets as $key => $sections) {
			foreach ( $sections as $section => $s_args ) {

				// Sanitize section ID
				$section = sanitize_title_with_dashes( trim( $section ) );

				// Sanitize default Widget name
				$name = ucwords( strtolower( str_replace( '_', ' ', $section ) ) );

				// Define the array of defaults 
				$section_defaults = array(
					'widgets'		=> array( $section => array( 'name' => $name ) )
				);

				// Parse incoming $s_args into an array and merge it with $defaults
				$s_args = wp_parse_args( $s_args, $section_defaults );

				foreach ( $s_args['widgets'] as $widget => $w_args ) {

					// Define the array of defaults 
					$widget_defaults = array(
						'name'   		=> ucwords( strtolower( str_replace( '_', ' ', $widget ) ) ),
						'id'   			=> sanitize_title_with_dashes( trim( $widget ) ),
						'description'   => '',
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h2>',
						'after_title'   => '</h2>',
					);

					// Parse incoming $w_args into an array and merge it with $defaults
					$w_args = wp_parse_args( $w_args, $widget_defaults );

					$widget_name = ( $w_args['name'] != '' ) ? $w_args['name'] : $w_args['id'];

					// Register sidebar
					register_sidebar( array(
						'name'          => ucwords( strtolower( str_replace( '_', ' ', $widget_name ) ) ),
						'id'            => sanitize_title_with_dashes( trim( $w_args['id'] ) ),
						'description'   => $w_args['description'],
						'before_widget' => $w_args['before_widget'],
						'after_widget'  => $w_args['after_widget'],
						'before_title'  => $w_args['before_title'],
						'after_title'   => $w_args['after_title'],
					) );

				}

			}

		}

	endif;
	
}
add_action( 'widgets_init', 'wte_widgets_init', 9999 );


/**
 * Output Widgets.
 */
function wte_widgets_output() {

	if ( current_theme_supports( 'wte-widgets' ) ) :

		$widgets = wte_default_widgets();

		foreach ( $widgets as $key => $sections) {
			foreach ( $sections as $section => $s_args ) {

				// Sanitize section ID
				$section = sanitize_title_with_dashes( trim( $section ) );

				// Sanitize default Widget name
				$name 	= ucwords( strtolower( str_replace( '_', ' ', $section ) ) );

				// Define the array of defaults 
				$section_defaults = array(
					'text_align'   	=> '',
					'wrap'   		=> 'wrap',
					'class'			=> '',
					'padding'   	=> 'true',
					'divider'		=> '',
					'widgets'		=> array( $section => array( 'name' => $name ) )
				);

				// Parse incoming $s_args into an array and merge it with $defaults
				$s_args = wp_parse_args( $s_args, $section_defaults );

				$padding = ( $s_args['padding'] ) ? 'wte-widget-section' : '';

				// Open section
				printf( '<div id="%1$s" class="%1$s %2$s %3$s %4$s widget-area wte-section">',
					$section,
					$s_args['class'],
					'text-' . $s_args['text_align'],
					$padding
				);

				// Open wrap
				echo ( $s_args['wrap'] ) ? '<div class="wte-'. $s_args['wrap'] .'">' : '';

				foreach ( $s_args['widgets'] as $widget => $w_args ) {

					// Define the array of defaults 
					$widget_defaults = array(
						'grid'   	=> '',
						'first'   	=> '',
					);

					// Parse incoming $w_args into an array and merge it with $defaults
					$w_args = wp_parse_args( $w_args, $widget_defaults );

					// First grid?
					$first = ( $w_args['first'] ) ? 'first' : '';

					// Open grid class
					echo ( $w_args['grid'] ) ? '<div class="'. $w_args['grid'] .' '. $first .'">' : '';

					// Output widget
					dynamic_sidebar( sanitize_title_with_dashes( trim( strtolower( $widget ) ) ) );
					
					// Close grid class
					echo ( $w_args['grid'] ) ? '</div>' : '';
				}

				// Close wrap
				echo ( $s_args['wrap'] ) ? '</div>' : '';

				// Close section
				echo '</div>';

				echo ( $s_args['divider'] ) ? do_shortcode( '[divider]' ) : '';

			}

		}

	endif;

}


/**
 * WTE Seidebars - Text Align.
 */
function wte_sidebars_text_align() {

	$values = apply_filters( 'wte_sidebars_text_align', array(
		'' 			=> __( 'Text Align', 'wte' ),
		'centered' 	=> __( 'Text - Center', 'wte' ),
		'left' 		=> __( 'Text - Left', 'wte' ),
		'right' 	=> __( 'Text - Right', 'wte' ),
	) );
	return $values;

}


/**
* WTE Seidebars - Wrap.
*/
function wte_sidebars_wrap() {

	$values = apply_filters( 'wte_sidebars_wrap', array(
		'wrap' 			=> __( 'Sidebars Wrapper', 'wte' ),
		'small-wrap' 	=> __( 'Small Wrapper', 'wte' ),
		'' 				=> __( 'No Wrapper', 'wte' ),
	) );
	return $values;

}


/**
* WTE Seidebars - Padding.
*/
function wte_sidebars_padding() {

	$values = apply_filters( 'wte_sidebars_padding', array(
		'true' 			=> __( 'Add Padding', 'wte' ),
		'' 				=> __( 'No Padding', 'wte' ),
	) );
	return $values;

}


/**
* WTE Seidebars - Divider.
*/
function wte_sidebars_divider() {

	$values = apply_filters( 'wte_sidebars_divider', array(
		'' 				=> __( 'No Divider', 'wte' ),
		'true' 			=> __( 'Add Divider', 'wte' ),
	) );
	return $values;

}

/**
* WTE Seidebars - Grid.
*/
function wte_sidebars_grid() {

	$values = apply_filters( 'wte_sidebars_grid', array(
		'full-width' 	=> __( 'full-width', 'wte' ),
		'one-fourth' 	=> __( 'one-fourth', 'wte' ),
		'one-half' 		=> __( 'one-half', 'wte' ),
		'one-sixth' 	=> __( 'one-sixth', 'wte' ),
		'one-third' 	=> __( 'one-third', 'wte' ),
		'two-fourths' 	=> __( 'two-fourths', 'wte' ),
		'two-sixths' 	=> __( 'two-sixths', 'wte' ),
		'two-thirds' 	=> __( 'two-thirds', 'wte' ),
		'three-fourths' => __( 'three-fourths', 'wte' ),
		'three-sixths' 	=> __( 'three-sixths', 'wte' ),
		'four-sixths' 	=> __( 'four-sixths', 'wte' ),
		'five-sixths' 	=> __( 'five-sixths', 'wte' ),
	) );
	return $values;

}

/**
* WTE Seidebars - Grid First.
*/
function wte_sidebars_grid_first() {

	$values = apply_filters( 'wte_sidebars_grid_first', array(
		'' 				=> __( 'regular column', 'wte' ),
		'true' 			=> __( 'first column', 'wte' ),
	) );
	return $values;

}


/**
* WTE Seidebars - Grid First.
*/
function wte_sidebars_heading() {

	$values = apply_filters( 'wte_sidebars_heading', array(
		'' 				=> __( 'Widgets Heading', 'wte' ),
		'<h1>' 			=> __( 'Heading - H1', 'wte' ),
		'<h2>' 			=> __( 'Heading - H2', 'wte' ),
		'<h3>' 			=> __( 'Heading - H3', 'wte' ),
		'<h4>' 			=> __( 'Heading - H4', 'wte' ),
	) );
	return $values;

}

