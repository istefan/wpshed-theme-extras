<?php
/**
 * Widget - User Profile.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_User_Profile_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_user_profile_widget_defaults', array(
			'title'          => '',
			'alignment'	     => 'left',
			'user'           => '',
			'size'           => '85',
			'bio_text'       => '',
			'page'           => '',
			'page_link_text' => __( 'Read More', 'wte' ) . '&#x02026;',
		) );

		$widget_ops = array(
			'classname'   => 'user-profile-wte',
			'description' => __( 'Displays user profile block', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'user-profile-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'user-profile-wte', __( 'User Profile - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];

			$text = '';

			if ( ! empty( $instance['alignment'] ) )
				$text .= '<span class="align' . esc_attr( $instance['alignment'] ) . '">';

			$text .= get_avatar( $instance['user'], $instance['size'] );

			if( ! empty( $instance['alignment'] ) )
				$text .= '</span>';

			if ( $instance['bio_text'] != '' )
				$text .= $instance['bio_text']; // We run KSES on update
			else
				$text .= get_the_author_meta( 'description', $instance['user'] );

			$text .= $instance['page'] ? sprintf( ' <a class="pagelink" href="%s">%s</a>', 
						get_page_link( $instance['page'] ), 
						$instance['page_link_text'] 
					) : '';

			// Echo $text
			echo wpautop( apply_filters( 'widget_text', $text ) );

		echo $args['after_widget'];

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

		$new_instance['title']          = strip_tags( $new_instance['title'] );
		$new_instance['bio_text']       = current_user_can( 'unfiltered_html' ) ? $new_instance['bio_text'] : wte_formatting_kses( $new_instance['bio_text'] );
		$new_instance['page_link_text'] = strip_tags( $new_instance['page_link_text'] );

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

		// Select a user
        wte_form( array(
            'type'      => 'user',
            'label'     => __( 'Select a user', 'wte' ),
            'id'        => $this->get_field_id( 'user' ),
            'name'      => $this->get_field_name( 'user' ),
            'value'     => esc_attr( $instance['user'] ),
        ) );

        $sizes = array( 
			'45' 	=> __( 'Small - 45px', 'wte' ), 
			'65' 	=> __( 'Medium - 65px', 'wte' ), 
			'85' 	=> __( 'Large - 85px', 'wte' ), 
			'125' 	=> __( 'Extra Large - 125px', 'wte' ), 
        );
        $sizes = apply_filters( 'wte_gravatar_sizes', $sizes );

        // Gravatar Size
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Gravatar Size', 'wte' ),
            'id'        => $this->get_field_id( 'size' ),
            'name'      => $this->get_field_name( 'size' ),
            'value'     => $instance['size'],
            'options'   => $sizes,
        ) );

        // Gravatar Alignment
        wte_form( array(
            'type'      => 'select',
            'label'     => __( 'Gravatar Alignment', 'wte' ),
            'id'        => $this->get_field_id( 'alignment' ),
            'name'      => $this->get_field_name( 'alignment' ),
            'value'     => $instance['alignment'],
            'options'   => array(
                            'left' 		=> __( 'Left', 'wte' ),
                            'right' 	=> __( 'Right', 'wte' ),
                        ),
        ) );

        // Author Description
        wte_form( array(
            'type'      => 'textarea',
            'label'     => __( 'Author Description', 'wte' ),
            'id'        => $this->get_field_id( 'bio_text' ),
            'name'      => $this->get_field_name( 'bio_text' ),
            'value'     => htmlspecialchars( $instance['bio_text'] ),
        ) );

		// About Me Page
        wte_form( array(
            'type'      => 'page',
            'label'     => __( 'Choose your extended "About Me" page.', 'wte' ),
            'id'        => $this->get_field_id( 'page' ),
            'name'      => $this->get_field_name( 'page' ),
            'value'     => esc_attr( $instance['page'] ),
        ) );

		// Extended page link text
        wte_form( array(
            'type'      => 'text',
            'label'     => __( '"About Me" page link text', 'wte' ),
            'id'        => $this->get_field_id( 'page_link_text' ),
            'name'      => $this->get_field_name( 'page_link_text' ),
            'value'     => esc_attr( $instance['page_link_text'] ),
        ) );

	}

}

// Register widget
function wte_load_user_profile_widget() {
	if ( wte_get_option( 'user_profile_widget' ) == 1 )
		register_widget( 'WPshed_User_Profile_Widget' );
}
add_action( 'widgets_init', 'wte_load_user_profile_widget' );

