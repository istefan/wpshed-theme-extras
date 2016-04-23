<?php
/**
 * Widget - Call To Action.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_CTA_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_cta_widget_defaults', array(
			'title'         => 'This is why you should click the button!',
			'url'	     	=> '#',
			'btn_style'	    => '',
            'style'         => 'pattern',
			'text'	     	=> 'Learn More',
		) );

		$widget_ops = array(
			'classname'   => 'cta-wte',
			'description' => __( 'Displays a Call To Action section.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'cta-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'cta-wte', __( 'Call To Action - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

        printf( '<div class="cta-wte-style %s">',
            esc_attr( $instance['style'] )
        );

		echo $args['before_widget'];

            echo '<div class="wrap">';

			if ( ! empty( $instance['title'] ) )
                printf( '<h2>%s</h2>',
                    apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base )
                );

            if ( ! empty( $instance['url'] ) )
                echo do_shortcode( '[button 
                        url="'. esc_url( $instance['url'] ) .'" 
                        text="'. esc_attr( $instance['text'] ) .'" 
                        type="large '. esc_attr( $instance['btn_style'] ) .'"
                    ]' );

            echo '</div>';

		echo $args['after_widget'];

        echo '</div>';

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title']  = strip_tags( $new_instance['title'] );
        $new_instance['text']   = strip_tags( $new_instance['text'] );
        $new_instance['btn_style']  = strip_tags( $new_instance['btn_style'] );
        $new_instance['url']   	= esc_url( $new_instance['url'] );

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

        // Button Text
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Button Text', 'wte' ),
            'id'        => $this->get_field_id( 'text' ),
            'name'      => $this->get_field_name( 'text' ),
            'value'     => htmlspecialchars( $instance['text'] ),
        ) );

		// Button URL
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Button URL', 'wte' ),
            'id'        => $this->get_field_id( 'url' ),
            'name'      => $this->get_field_name( 'url' ),
            'value'     => esc_url( $instance['url'] ),
        ) );

        // Button Style
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Button Style', 'wte' ),
            'id'        => $this->get_field_id( 'btn_style' ),
            'name'      => $this->get_field_name( 'btn_style' ),
            'value'     => $instance['btn_style'],
            'options'   => array(
                            '' 		    => __( 'Default', 'wte' ),
                            'red'       => __( 'Red', 'wte' ),
                            'blue' 		=> __( 'Blue', 'wte' ),
                            'green' 	=> __( 'Green', 'wte' ),
                            'gold' 	    => __( 'Gold', 'wte' ),
                            'gray'      => __( 'Gray', 'wte' ),
                            'black'     => __( 'Black', 'wte' ),
                        ),
        ) );

        // Block Style
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Block Style', 'wte' ),
            'id'        => $this->get_field_id( 'style' ),
            'name'      => $this->get_field_name( 'style' ),
            'value'     => $instance['style'],
            'options'   => array(
                            'pattern'   => __( 'Default', 'wte' ),
                            'red'       => __( 'Red', 'wte' ),
                            'blue'      => __( 'Blue', 'wte' ),
                            'green'     => __( 'Green', 'wte' ),
                            'gold'      => __( 'Gold', 'wte' ),
                            'gray'      => __( 'Gray', 'wte' ),
                        ),
        ) );

	}

}

// Register widget
function wte_load_cta_widget() {
	if ( wte_get_option( 'cta_widget' ) == 1 )
		register_widget( 'WPshed_CTA_Widget' );
}
add_action( 'widgets_init', 'wte_load_cta_widget' );
