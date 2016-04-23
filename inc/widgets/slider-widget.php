<?php
/**
 * Widget - Slider.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Slider_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_slider_widget_defaults', array(
			'title'          		=> '',
			'posts'	     			=> 3,
			'cat'            		=> 0,
	        'orderby'               => '',
	        'order'                 => '',
	        'show_posts'			=> '',
	        'posts_cat'				=> 0,
		) );

		$widget_ops = array(
			'classname'   => 'slider-wte',
			'description' => __( 'Displays Slides by Category.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'slider-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'slider-wte', __( 'Slider - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			echo do_shortcode( '[slider posts="'. $instance['posts'] .'" orderby="'. $instance['orderby'] .'" order="'. $instance['order'] .'" cat="'. $instance['cat'] .'" show_posts="'. $instance['show_posts'] .'" posts_cat="'. $instance['posts_cat'] .'" ]' );

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title']          = strip_tags( $new_instance['title'] );
		$new_instance['cat'] 			= strip_tags( $new_instance['cat'] );
		$new_instance['posts'] 			= (int) $new_instance['posts'];
		$new_instance['orderby']        = strip_tags( $new_instance['orderby'] );
		$new_instance['order']          = strip_tags( $new_instance['order'] );

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

		// Number of slides
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Number of slides', 'wte' ),
            'id'        => $this->get_field_id( 'posts' ),
            'name'      => $this->get_field_name( 'posts' ),
            'value'     => esc_attr( $instance['posts'] ),
        ) );

		// Slides Category
        wte_form( array(
            'type'      => 'category',
            'label'     => __( 'Slides Category', 'wte' ),
            'id'        => $this->get_field_id( 'cat' ),
            'name'      => $this->get_field_name( 'cat' ),
            'value'     => esc_attr( $instance['cat'] ),
            'taxonomy'  => 'wte_slides_category',
        ) );

        // Show slides from posts (featured images)
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Show slides from posts (featured images)', 'wte' ),
            'id'        => $this->get_field_id( 'show_posts' ),
            'name'      => $this->get_field_name( 'show_posts' ),
            'value'     => $instance['show_posts'],
        ) );

		// Posts Category
        wte_form( array(
            'type'      => 'category',
            'label'     => __( 'Posts Category', 'wte' ),
            'id'        => $this->get_field_id( 'posts_cat' ),
            'name'      => $this->get_field_name( 'posts_cat' ),
            'value'     => esc_attr( $instance['posts_cat'] ),
        ) );

        // Order By
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Order By', 'wte' ),
            'id'        => $this->get_field_id( 'orderby' ),
            'name'      => $this->get_field_name( 'orderby' ),
            'value'     => $instance['orderby'],
            'options'   => array(
                            'date' 			=> __( 'Date', 'wte' ),
                            'title' 		=> __( 'Title', 'wte' ),
                            'comment_count' => __( 'Comment Count', 'wte' ),
                            'rand'			=> __( 'Random', 'wte' ),
                        ),
        ) );

        // Sort Order
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Sort Order', 'wte' ),
            'id'        => $this->get_field_id( 'order' ),
            'name'      => $this->get_field_name( 'order' ),
            'value'     => $instance['order'],
            'options'   => array(
                            'DESC' 	=> __( 'Descending (3, 2, 1)', 'wte' ),
                            'ASC'	=> __( 'Ascending (1, 2, 3)', 'wte' ),
                        ),
        ) );

	}

}

// Register widget
function wte_load_slider_widget() {
	if ( wte_get_option( 'slider_widget' ) == 1 && wte_get_option( 'slider_cpt' ) == 1 )
		register_widget( 'WPshed_Slider_Widget' );
}
add_action( 'widgets_init', 'wte_load_slider_widget' );
