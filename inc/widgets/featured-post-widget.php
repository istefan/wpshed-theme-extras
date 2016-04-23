<?php
/**
 * Widget - Featured Post.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Featured_Post_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_featured_post_widget_defaults', array(
			'title'                   	=> '',
			'cat'               		=> '',
			'posts'               		=> 1,
			'offset'            		=> 0,
			'orderby'                 	=> '',
			'order'                   	=> '',
			'exclude_sticky'          	=> 1,
			'show_image'              	=> 0,
			'image_alignment'         	=> '',
			'image_size'              	=> '',
			'show_title'              	=> 1,
			'show_content'            	=> 'excerpt',
			'content_limit'           	=> '',
			'more_text'               	=> __( 'Read More', 'wte' ) . '&#x02026;',
		) );

		$widget_ops = array(
			'classname'   => 'featured-post-wte',
			'description' => __( 'Displays featured posts.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'featured-post-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'featured-post-wte', __( 'Featured Posts - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			echo do_shortcode( '[featured_posts 
					cat="'. $instance['cat'] .'" 
					posts="'. $instance['posts'] .'" 
					offset="'. $instance['offset'] .'" 
					orderby="'. $instance['orderby'] .'" 
					order="'. $instance['order'] .'" 
					exclude_sticky="'. $instance['exclude_sticky'] .'" 
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

		$new_instance['exclude_sticky'] = ( ! empty( $new_instance['exclude_sticky'] ) ) ? (int) $new_instance['exclude_sticky'] : '';
		$new_instance['show_image'] = ( ! empty( $new_instance['show_image'] ) ) ? (int) $new_instance['show_image'] : '';
		$new_instance['show_title'] = ( ! empty( $new_instance['show_title'] ) ) ? (int) $new_instance['show_title'] : '';
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

		// Category
        wte_form( array(
            'type'      => 'category',
            'label'     => __( 'Category', 'wte' ),
            'id'        => $this->get_field_id( 'cat' ),
            'name'      => $this->get_field_name( 'cat' ),
            'value'     => esc_attr( $instance['cat'] ),
        ) );

		// Number of Posts
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Number of Posts', 'wte' ),
            'id'        => $this->get_field_id( 'posts' ),
            'name'      => $this->get_field_name( 'posts' ),
            'value'     => esc_attr( $instance['posts'] ),
        ) );

		// Number of Posts to Offset
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Number of Posts to Offset', 'wte' ),
            'id'        => $this->get_field_id( 'offset' ),
            'name'      => $this->get_field_name( 'offset' ),
            'value'     => esc_attr( $instance['offset'] ),
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

        // Exclude Sticky Posts
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Exclude Sticky Posts', 'wte' ),
            'id'        => $this->get_field_id( 'exclude_sticky' ),
            'name'      => $this->get_field_name( 'exclude_sticky' ),
            'value'     => $instance['exclude_sticky'],
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

        // Show Post Title
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Show Post Title', 'wte' ),
            'id'        => $this->get_field_id( 'show_title' ),
            'name'      => $this->get_field_name( 'show_title' ),
            'value'     => $instance['show_title'],
        ) );

        // Content Type
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Content Type', 'wte' ),
            'id'        => $this->get_field_id( 'show_content' ),
            'name'      => $this->get_field_name( 'show_content' ),
            'value'     => $instance['show_content'],
            'options'   => array(
                            'content' 		=> __( 'Show Content', 'wte' ),
                            'excerpt' 		=> __( 'Show Excerpt', 'wte' ),
                            'content-limit' => __( 'Show Content Limit', 'wte' ),
                            '' 				=> __( 'No Content', 'wte' ),
                        ),
        ) );

		// Limit content to
        wte_form( array(
            'type'      => 'number',
            'label'     => __( 'Limit content to (characters)', 'wte' ),
            'id'        => $this->get_field_id( 'content_limit' ),
            'name'      => $this->get_field_name( 'content_limit' ),
            'value'     => esc_attr( $instance['content_limit'] ),
        ) );

		// More Text
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'More Text (if applicable)', 'wte' ),
            'id'        => $this->get_field_id( 'more_text' ),
            'name'      => $this->get_field_name( 'more_text' ),
            'value'     => esc_attr( $instance['more_text'] ),
        ) );

	}

}

// Register widget
function wte_load_featured_posts_widget() {
	if ( wte_get_option( 'feat_post_widget' ) == 1 )
		register_widget( 'WPshed_Featured_Post_Widget' );
}
add_action( 'widgets_init', 'wte_load_featured_posts_widget' );

