<?php
/**
 * Widget - Portfolio.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Portfolio_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_portfolio_widget_defaults', array(
			'title'          		=> '',
	        'posts'                 => 4,
	        'orderby'               => '',
	        'order'                 => '',
	        'post_type'             => 'portfolio',
	        'cols'                  => 4,
	        'show_title'            => '',
	        'heading'               => 'h4',
		) );

		$widget_ops = array(
			'classname'   => 'portfolio-wte',
			'description' => __( 'Displays Projects by Category.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'portfolio-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'portfolio-wte', __( 'Portfolio - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			echo do_shortcode( '[portfolio 
					posts="'. $instance['posts'] .'" 
					orderby="'. $instance['orderby'] .'" 
					order="'. $instance['order'] .'" 
					cols="'. $instance['cols'] .'"
					show_title="'. $instance['show_title'] .'"
					heading="'. $instance['heading'] .'"
				]' );

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title']          = strip_tags( $new_instance['title'] );
		$new_instance['posts'] 			= (int) $new_instance['posts'];
		$new_instance['cols'] 			= (int) $new_instance['cols'];
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

		// Number of posts
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Number of posts', 'wte' ),
            'id'        => $this->get_field_id( 'posts' ),
            'name'      => $this->get_field_name( 'posts' ),
            'value'     => esc_attr( $instance['posts'] ),
        ) );

		// Number of columns
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Number of columns', 'wte' ),
            'id'        => $this->get_field_id( 'cols' ),
            'name'      => $this->get_field_name( 'cols' ),
            'value'     => esc_attr( $instance['cols'] ),
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
function wte_load_portfolio_widget() {
	if ( wte_get_option( 'portfolio_widget' ) == 1 && wte_get_option( 'portfolio_cpt' ) == 1 )
		register_widget( 'WPshed_Portfolio_Widget' );
}
add_action( 'widgets_init', 'wte_load_portfolio_widget' );
