<?php
/**
 * Widget - Social Icons.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Social_Icons_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_social_icons_widget_defaults', array(
			'title'         			=> '',
			'new_window'             	=> 1,
			'size'                   	=> 46,
			'border_radius'          	=> 23,
			'border_width'           	=> 0,
			'border_color'           	=> '#ffffff',
			'border_color_hover'     	=> '#ffffff',
			'icon_color'             	=> '#ffffff',
			'icon_color_hover'       	=> '#ffffff',
			'background_color'       	=> '#aeb0b5',
			'background_color_hover' 	=> '#5b616b',
			'alignment'              	=> 'alignleft',
			'wordpress'					=> '',
			'facebook'					=> '',
			'twitter'					=> '',
			'googleplus'				=> '',
			'youtube'					=> '',
			'github'					=> '',
			'dribbble'					=> '',
			'linkedin'					=> '',
			'pinterest'					=> '',
			'flickr'					=> '',
			'vimeo'						=> '',
			'tumblr'					=> '',
			'instagram'					=> '',
			'codepen'					=> '',
		) );

		$widget_ops = array(
			'classname'   => 'social-icons-wte',
			'description' => __( 'Displays Social Media Icons.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'social-icons-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'social-icons-wte', __( 'Social Icons - WPshed', 'wte' ), $widget_ops, $control_ops );

		// Load CSS in <head> 
		add_action( 'wp_head', array( $this, 'css' ) );

	}

	// Custom CSS.
	function css() {

		// Pull widget settings, merge with defaults
		$all_instances = $this->get_settings();
		if ( ! isset( $this->number ) || ! isset( $all_instances[$this->number] ) ) {
			return;
		}

		$instance = wp_parse_args( $all_instances[$this->number], $this->defaults );

		$font_size = round( (int) $instance['size'] / 2 );
		$icon_padding = round ( (int) $font_size / 2 );

		// The CSS to output
		$css = '
		.social-icons-wte ul li a,
		.social-icons-wte ul li a:hover {
			background-color: ' . $instance['background_color'] . ' !important;
			border-radius: ' . $instance['border_radius'] . 'px;
			color: ' . $instance['icon_color'] . ' !important;
			border: ' . $instance['border_width'] . 'px ' . $instance['border_color'] . ' solid !important;
			font-size: ' . $font_size . 'px;
			padding: ' . $icon_padding . 'px;
		}

		.social-icons-wte ul li a:hover {
			background-color: ' . $instance['background_color_hover'] . ' !important;
			border-color: ' . $instance['border_color_hover'] . ' !important;
			color: ' . $instance['icon_color_hover'] . ' !important;
		}';

		// Minify a bit
		$css = str_replace( "\t", '', $css );
		$css = str_replace( array( "\n", "\r" ), ' ', $css );

		// Echo the CSS
		echo '<style type="text/css" media="screen">' . $css . '</style>';

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			$new_window = $instance['new_window'] ? 'target="_blank"' : '';

			printf( '<ul class="%s">', $instance['alignment'] );

			foreach ( wte_social_icons() as $key => $value ) {

				if ( $instance[$key] != '' )
					printf( '<li><a href="%s" %s><span class="genericon genericon-%s"></span></a></li>',
						$instance[$key],
						$new_window,
						$key
					);		

			}

			echo '</ul>';

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title'] = strip_tags( $new_instance['title'] );

		foreach ( $new_instance as $key => $value ) {

			// Border radius and Icon size must not be empty, must be a digit
			if ( ( 'border_radius' == $key || 'size' == $key ) && ( '' == $value || ! ctype_digit( $value ) ) ) {
				$new_instance[$key] = 0;
			}

			if ( ( 'border_width' == $key || 'size' == $key ) && ( '' == $value || ! ctype_digit( $value ) ) ) {
				$new_instance[$key] = 0;
			}

			// Validate hex code colors
			elseif ( strpos( $key, '_color' ) && 0 == preg_match( '/^#(([a-fA-F0-9]{3}$)|([a-fA-F0-9]{6}$))/', $value ) ) {
				$new_instance[$key] = $old_instance[$key];
			}

		}

		foreach ( wte_social_icons() as $key => $value) {
			$new_instance[$key] = esc_url( $new_instance[$key] );
		}

		return $new_instance;

	}

	// Echo the settings update form.
	function form( $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		// Title
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Title', 'wte' ),
            'id'        => $this->get_field_id( 'title' ),
            'name'      => $this->get_field_name( 'title' ),
            'value'     => esc_attr( $instance['title'] ),
        ) );

        // Open links in new window
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Open links in new window', 'wte' ),
            'id'        => $this->get_field_id( 'new_window' ),
            'name'      => $this->get_field_name( 'new_window' ),
            'value'     => $instance['new_window'],
        ) );

		// Icon Size
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Icon Size (px)', 'wte' ),
            'id'        => $this->get_field_id( 'size' ),
            'name'      => $this->get_field_name( 'size' ),
            'value'     => esc_attr( $instance['size'] ),
        ) );

		// Icon Border Radius
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Icon Border Radius (px)', 'wte' ),
            'id'        => $this->get_field_id( 'border_radius' ),
            'name'      => $this->get_field_name( 'border_radius' ),
            'value'     => esc_attr( $instance['border_radius'] ),
        ) );

		// Border Width
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Border Width (px)', 'wte' ),
            'id'        => $this->get_field_id( 'border_width' ),
            'name'      => $this->get_field_name( 'border_width' ),
            'value'     => esc_attr( $instance['border_width'] ),
        ) );

        // Alignment
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Alignment', 'wte' ),
            'id'        => $this->get_field_id( 'alignment' ),
            'name'      => $this->get_field_name( 'alignment' ),
            'value'     => $instance['alignment'],
            'options'   => array(
                            'alignleft' 	=> __( 'Align Left', 'wte' ),
                            'aligncenter' 	=> __( 'Align Center', 'wte' ),
                            'alignright' 	=> __( 'Align Right', 'wte' ),
                        ),
        ) );

        echo '<hr class="div" />';

		// Icon Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Icon Color', 'wte' ),
            'id'        => $this->get_field_id( 'icon_color' ),
            'name'      => $this->get_field_name( 'icon_color' ),
            'value'     => esc_attr( $instance['icon_color'] ),
        ) );

		// Icon Hover Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Icon Hover Color', 'wte' ),
            'id'        => $this->get_field_id( 'icon_color_hover' ),
            'name'      => $this->get_field_name( 'icon_color_hover' ),
            'value'     => esc_attr( $instance['icon_color_hover'] ),
        ) );

		// Background Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Background Color', 'wte' ),
            'id'        => $this->get_field_id( 'background_color' ),
            'name'      => $this->get_field_name( 'background_color' ),
            'value'     => esc_attr( $instance['background_color'] ),
        ) );

		// Background Hover Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Background Hover Color', 'wte' ),
            'id'        => $this->get_field_id( 'background_color_hover' ),
            'name'      => $this->get_field_name( 'background_color_hover' ),
            'value'     => esc_attr( $instance['background_color_hover'] ),
        ) );

		// Border Hover Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Border Hover Color', 'wte' ),
            'id'        => $this->get_field_id( 'border_color' ),
            'name'      => $this->get_field_name( 'border_color' ),
            'value'     => esc_attr( $instance['border_color'] ),
        ) );

		// Border Color
        wte_form( array(
            'type'      => 'color',
            'label'     => __( 'Border Color', 'wte' ),
            'id'        => $this->get_field_id( 'border_color_hover' ),
            'name'      => $this->get_field_name( 'border_color_hover' ),
            'value'     => esc_attr( $instance['border_color_hover'] ),
        ) );

        echo '<hr class="div" />';

        foreach ( wte_social_icons() as $key => $value ) {

			// URLs
	        wte_form( array(
	            'type'      => 'text',
	            'label'     => $value . ' ' . __( 'URL', 'wte' ),
	            'id'        => $this->get_field_id( $key ),
	            'name'      => $this->get_field_name( $key ),
	            'value'     => esc_attr( $instance[$key] ),
	        ) );

        }

	}

}

// Social Icons array
function wte_social_icons() {

	$social_icons = array(
		'wordpress'		=> 'WordPress',
		'facebook'		=> 'Facebook',
		'twitter'		=> 'Twitter',
		'googleplus'	=> 'Google Plus',
		'youtube'		=> 'YouTube',
		'github'		=> 'Github',
		'dribbble'		=> 'Dribble',
		'linkedin'		=> 'Linkedin',
		'pinterest'		=> 'Pinterest',
		'flickr'		=> 'Flickr',
		'vimeo'			=> 'Vimeo',
		'tumblr'		=> 'Tumblr',
		'instagram'		=> 'Instagram',
		'codepen'		=> 'Codepen',
	);

	return $social_icons;

}

// Register widget
function wte_load_social_icons_widget() {
	if ( wte_get_option( 'social_widget' ) == 1 )
		register_widget( 'WPshed_Social_Icons_Widget' );
}
add_action( 'widgets_init', 'wte_load_social_icons_widget' );
