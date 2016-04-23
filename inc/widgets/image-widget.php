<?php
/**
 * Widget - Image.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Image_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_image_widget_defaults', array(
			'title'         => '',
			'image'	     	=> '',
			'url'	     	=> '',
		) );

		$widget_ops = array(
			'classname'   => 'image-wte',
			'description' => __( 'Displays a Image.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'image-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'image-wte', __( 'Image - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

            echo ( ! empty( $instance['url'] ) ) ? '<a href="' . $instance['url'] . '">' : '';

            echo ( ! empty( $instance['image'] ) ) ? '<img src="' . $instance['image'] . '" alt="" />' : '';

            echo ( ! empty( $instance['url'] ) ) ? '</a>' : '';

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title']  = strip_tags( $new_instance['title'] );
        $new_instance['image']  = strip_tags( $new_instance['image'] );
        $new_instance['url']   	= strip_tags( $new_instance['url'] );

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

        // Image
        wte_form( array(
            'type'      => 'image',
            'label'     => __( 'Image', 'wte' ),
            'id'        => $this->get_field_id( 'image' ),
            'name'      => $this->get_field_name( 'image' ),
            'value'     => htmlspecialchars( $instance['image'] ),
        ) );

		// URL
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'URL', 'wte' ),
            'id'        => $this->get_field_id( 'url' ),
            'name'      => $this->get_field_name( 'url' ),
            'value'     => esc_url( $instance['url'] ),
        ) );

	}

}

// Register widget
function wte_load_image_widget() {
	if ( wte_get_option( 'image_widget' ) == 1 )
		register_widget( 'WPshed_Image_Widget' );
}
add_action( 'widgets_init', 'wte_load_image_widget' );
