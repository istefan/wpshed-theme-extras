<?php
/**
 * Widget - Price Table.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

class WPshed_Price_Table_Widget extends WP_Widget {

	// Holds widget settings defaults, populated in constructor.
	protected $defaults;

	// Constructor. Set the default widget options and create widget.
	function __construct() {

		$this->defaults = apply_filters( 'wte_price_table_widget_defaults', array(
			'title'         => 'Professional - $99',
			'item_1'        => 'List Item #1',
			'item_2'        => 'List Item #2',
			'item_3'        => 'List Item #3',
			'item_4'        => 'List Item #4',
			'item_5'        => 'List Item #5',
			'item_6'        => '',
			'item_7'        => '',
			'item_8'        => '',
			'button'	    => 'Purchase',
			'url'	     	=> '#',
			'featured'	    => 0,
		) );

		$widget_ops = array(
			'classname'   => 'price-table-wte',
			'description' => __( 'Displays a Price Table.', 'wte' ),
		);

		$control_ops = array(
			'id_base' => 'price-table-wte',
			'width'   => 200,
			'height'  => 250,
		);

		parent::__construct( 'price-table-wte', __( 'Price Table - WPshed', 'wte' ), $widget_ops, $control_ops );

	}

	// Echo the widget content.
	function widget( $args, $instance ) {

		// Merge with defaults
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo ( $instance['featured'] == 1 ) ? '<div class="featured-price-table">' : '';
		echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) )
				echo '<h4>' . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . '</h4>';

			echo '<ul>';
				for ( $i = 1; $i < 9; $i++ ) :
					echo ( ! empty( $instance['item_' . $i] ) ) ? '<li>' . $instance['item_' . $i] . '</li>' : '';
				endfor;
			echo '</ul>';

			echo do_shortcode( '[button text="'. $instance['button'] .'" url="'. $instance['url'] .'" type="large" ]' );

		echo $args['after_widget'];
		echo ( $instance['featured'] == 1 ) ? '</div>' : '';

	}

	// Update a particular instance.
	function update( $new_instance, $old_instance ) {

        $new_instance['title']  	= strip_tags( $new_instance['title'] );
        $new_instance['button']  	= strip_tags( $new_instance['button'] );
        $new_instance['url']   		= esc_url( $new_instance['url'] );

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

        for ( $i = 1; $i < 9; $i++ ) {

			// Items
	        wte_form( array(
	            'type'      => 'text',
	            'label'     => __( 'Item', 'wte' ) . ' ' . $i,
	            'id'        => $this->get_field_id( 'item_' . $i ),
	            'name'      => $this->get_field_name( 'item_' . $i ),
	            'value'     => esc_attr( $instance['item_' . $i] ),
	        ) );

        }

		// Button Text
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Button Text', 'wte' ),
            'id'        => $this->get_field_id( 'button' ),
            'name'      => $this->get_field_name( 'button' ),
            'value'     => esc_attr( $instance['button'] ),
        ) );

		// Button URL
        wte_form( array(
            'type'      => 'text',
            'label'     => __( 'Button URL', 'wte' ),
            'id'        => $this->get_field_id( 'url' ),
            'name'      => $this->get_field_name( 'url' ),
            'value'     => esc_attr( $instance['url'] ),
        ) );

        // Featured Price Table
        wte_form( array(
            'type'      => 'checkbox',
            'label'     => __( 'Featured Price Table', 'wte' ),
            'id'        => $this->get_field_id( 'featured' ),
            'name'      => $this->get_field_name( 'featured' ),
            'value'     => $instance['featured'],
        ) );

	}

}

// Register widget
function wte_load_price_table_widget() {
	if ( wte_get_option( 'price_table_widget' ) == 1 )
		register_widget( 'WPshed_Price_Table_Widget' );
}
add_action( 'widgets_init', 'wte_load_price_table_widget' );
