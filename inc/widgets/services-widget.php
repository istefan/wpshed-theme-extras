<?php
/**
 * Widget - Services.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Services_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_services_widget_defaults', array(
			'title'         => 'Our Cool Services',
			'image'	     	=> WTE_IMAGES . 'globe.png',
			'url'	     	=> '',
			'alignment'	    => 'none',
			'text'	     	=> 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.',
		) );

		$widget_ops = array(
			'classname'   => 'services-wte',
			'description' => __( 'Displays a Services section.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'services-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'services-wte', __( 'Services - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['alignment'] ) )
				echo '<span class="align' . esc_attr( $instance['alignment'] ) . '">';

            echo ( ! empty( $instance['url'] ) ) ? '<a href="' . $instance['url'] . '">' : '';

            echo ( ! empty( $instance['image'] ) ) ? '<img src="' . $instance['image'] . '" alt="" />' : '';

            echo ( ! empty( $instance['url'] ) ) ? '</a>' : '';

			if( ! empty( $instance['alignment'] ) )
				echo '</span>';

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

            echo wpautop( apply_filters( 'widget_text', $instance['text'] ) );


		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title']  = strip_tags( $new_instance['title'] );
        $new_instance['image']  = strip_tags( $new_instance['image'] );
        $new_instance['url']   	= esc_url( $new_instance['url'] );
        $new_instance['text']   = current_user_can( 'unfiltered_html' ) ? $new_instance['text'] : wte_formatting_kses( $new_instance['text'] );

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

        // Text
        wte_form( array(
            'type'      => 'textarea',
            'label'     => __( 'Text', 'wte' ),
            'id'        => $this->get_field_id( 'text' ),
            'name'      => $this->get_field_name( 'text' ),
            'value'     => htmlspecialchars( $instance['text'] ),
        ) );

        // Image
        wte_form( array(
            'type'      => 'image',
            'label'     => __( 'Image', 'wte' ),
            'id'        => $this->get_field_id( 'image' ),
            'name'      => $this->get_field_name( 'image' ),
            'value'     => htmlspecialchars( $instance['image'] ),
        ) );

		// Image URL
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Image URL', 'wte' ),
            'id'        => $this->get_field_id( 'url' ),
            'name'      => $this->get_field_name( 'url' ),
            'value'     => esc_url( $instance['url'] ),
        ) );

        // Image Alignment
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Image Alignment', 'wte' ),
            'id'        => $this->get_field_id( 'alignment' ),
            'name'      => $this->get_field_name( 'alignment' ),
            'value'     => $instance['alignment'],
            'options'   => array(
                            'none' 		=> __( 'None', 'wte' ),
                            'left' 		=> __( 'Left', 'wte' ),
                            'right' 	=> __( 'Right', 'wte' ),
                            'center' 	=> __( 'Center', 'wte' ),
                        ),
        ) );

	}

}

// Register widget
function wte_load_services_widget() {
	if ( wte_get_option( 'services_widget' ) == 1 )
		register_widget( 'WPshed_Services_Widget' );
}
add_action( 'widgets_init', 'wte_load_services_widget' );
