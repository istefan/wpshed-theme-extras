<?php
/**
 * Widget - Featured Page.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Featured_Page_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_featured_page_widget_defaults', array(
			'title'           => '',
			'page_id'         => '',
			'show_image'      => 0,
			'image_alignment' => '',
			'image_size'      => '',
			'show_title'      => 0,
			'show_content'    => 0,
			'content_limit'   => '',
			'more_text'       => '',
		) );

		$widget_ops = array(
			'classname'   => 'featured-page-wte',
			'description' => __( 'Displays featured page.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'featured-page-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'featured-page-wte', __( 'Featured Page - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		// Set up the author bio
		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			echo do_shortcode( '[featured_page 
					page_id="'. $instance['page_id'] .'" 
					show_image="'. $instance['show_image'] .'" 
					image_alignment="'. $instance['image_alignment'] .'" 
					image_size="'. $instance['image_size'] .'" 
					show_title="'. $instance['show_title'] .'" 
					show_content="'. $instance['show_content'] .'" 
					content_limit="'. $instance['content_limit'] .'" 
					more_text="'. $instance['more_text'] .'" 
				]' );

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title']     = strip_tags( $new_instance['title'] );
		$new_instance['more_text'] = strip_tags( $new_instance['more_text'] );
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

		// Page
        wte_form( array(
            'type'      => 'page',
            'label'     => __( 'Page', 'wte' ),
            'id'        => $this->get_field_id( 'page_id' ),
            'name'      => $this->get_field_name( 'page_id' ),
            'value'     => esc_attr( $instance['page_id'] ),
        ) );

        echo '<hr class="div" />';

        // Show Featured Image
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Show Featured Image', 'wte' ),
            'id'        => $this->get_field_id( 'show_image' ),
            'name'      => $this->get_field_name( 'show_image' ),
            'value'     => $instance['show_image'],
        ) );

        // Image Size
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Image Size', 'wte' ),
            'id'        => $this->get_field_id( 'image_size' ),
            'name'      => $this->get_field_name( 'image_size' ),
            'value'     => $instance['image_size'],
            'options'   => wte_get_image_sizes_for_customizer(),
        ) );

        // Image Alignment
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Image Alignment', 'wte' ),
            'id'        => $this->get_field_id( 'image_alignment' ),
            'name'      => $this->get_field_name( 'image_alignment' ),
            'value'     => $instance['image_alignment'],
            'options'   => array(
                            'alignnone' 	=> __( 'None', 'wte' ),
                            'alignleft' 	=> __( 'Left', 'wte' ),
                            'alignright' 	=> __( 'Right', 'wte' ),
                            'aligncenter'	=> __( 'Center', 'wte' ),
                        ),
        ) );

        echo '<hr class="div" />';

        // Show Page Title
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Show Page Title', 'wte' ),
            'id'        => $this->get_field_id( 'show_title' ),
            'name'      => $this->get_field_name( 'show_title' ),
            'value'     => $instance['show_title'],
        ) );

        // Show Page Content
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Show Page Content', 'wte' ),
            'id'        => $this->get_field_id( 'show_content' ),
            'name'      => $this->get_field_name( 'show_content' ),
            'value'     => $instance['show_content'],
        ) );

		// Content Character Limit
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Content Character Limit', 'wte' ),
            'id'        => $this->get_field_id( 'content_limit' ),
            'name'      => $this->get_field_name( 'content_limit' ),
            'value'     => esc_attr( $instance['content_limit'] ),
        ) );

		// More Text
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'More Text', 'wte' ),
            'id'        => $this->get_field_id( 'more_text' ),
            'name'      => $this->get_field_name( 'more_text' ),
            'value'     => esc_attr( $instance['more_text'] ),
        ) );

	}

}

// Register widget
function wte_load_featured_page_widget() {
	if ( wte_get_option( 'feat_page_widget' ) == 1 )
		register_widget( 'WPshed_Featured_Page_Widget' );
}
add_action( 'widgets_init', 'wte_load_featured_page_widget' );
