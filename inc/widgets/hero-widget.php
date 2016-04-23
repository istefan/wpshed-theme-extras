<?php
/**
 * Widget - Hero.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Hero_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_hero_widget_defaults', array(
			'title'         => 'Hello Amazing!',
			'image'	     	=> '',
			'text'	     	=> 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.',
			'padding'	    => 'regular',
			'size'	    	=> 'large',
			'style'	    	=> 'none',
		) );

		$widget_ops = array(
			'classname'   => 'hero-wte',
			'description' => __( 'Displays a Hero section.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'hero-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'hero-wte', __( 'Hero - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$image = '';
		if ( ! empty( $instance['image'] ) ) {
			$image = 'style="background-image: url('. $instance['image'] .');"';
		}

		echo $args['before_widget'];

		printf( '<div class="hero-wrap padding-%s size-%s style-%s" %s>', 
			$instance['padding'], 
			$instance['size'],
			$instance['style'],
			$image
		);

			if ( ! empty( $instance['title'] ) )
				echo '<h2>' . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . '</h2>';

            echo wpautop( apply_filters( 'widget_text', $instance['text'] ) );


        echo '</div>';

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title']  = strip_tags( $new_instance['title'] );
        $new_instance['image']  = strip_tags( $new_instance['image'] );
        $new_instance['padding']= strip_tags( $new_instance['padding'] );
        $new_instance['size']   = strip_tags( $new_instance['size'] );
        $new_instance['style']  = strip_tags( $new_instance['style'] );
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

        // Text Size
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Text Size', 'wte' ),
            'id'        => $this->get_field_id( 'size' ),
            'name'      => $this->get_field_name( 'size' ),
            'value'     => $instance['size'],
            'options'   => array(
                            'regular' 	=> __( 'Regular', 'wte' ),
                            'large' 	=> __( 'Large', 'wte' ),
                            'x-large' 	=> __( 'Extra Large', 'wte' ),
                        ),
        ) );

        // Padding
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Padding', 'wte' ),
            'id'        => $this->get_field_id( 'padding' ),
            'name'      => $this->get_field_name( 'padding' ),
            'value'     => $instance['padding'],
            'options'   => array(
                            'regular' 	=> __( 'Regular', 'wte' ),
                            'large' 	=> __( 'Large', 'wte' ),
                            'x-large' 	=> __( 'Extra Large', 'wte' ),
                        ),
        ) );

        // Style
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Style', 'wte' ),
            'id'        => $this->get_field_id( 'style' ),
            'name'      => $this->get_field_name( 'style' ),
            'value'     => $instance['style'],
            'options'   => array(
                            'none' 	=> __( 'Default', 'wte' ),
                            'gray' 	=> __( 'Gray', 'wte' ),
                            'gold' 	=> __( 'Gold', 'wte' ),
                            'green'	=> __( 'Green', 'wte' ),
                            'blue' 	=> __( 'Blue', 'wte' ),
                            'red' 	=> __( 'Red', 'wte' ),
                        ),
        ) );

        // Background Image
        wte_form( array(
            'type'      => 'image',
            'label'     => __( 'Background Image', 'wte' ),
            'id'        => $this->get_field_id( 'image' ),
            'name'      => $this->get_field_name( 'image' ),
            'value'     => htmlspecialchars( $instance['image'] ),
        ) );

	}

}

// Register widget
function wte_load_hero_widget() {
	if ( wte_get_option( 'hero_widget' ) == 1 )
		register_widget( 'WPshed_Hero_Widget' );
}
add_action( 'widgets_init', 'wte_load_hero_widget' );
