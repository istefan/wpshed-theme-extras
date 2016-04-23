<?php
/**
 * Post Type - Testimonials.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


if ( wte_get_option( 'testimonial_cpt' ) == 1 ) :
/**
 * Register Post Type.
 */
function wte_register_post_type_testimonials() {

	$labels = array(
		'name'               	=> _x( 'Testimonials', 'post type general name', 'wte' ),
		'singular_name'      	=> _x( 'Testimonial', 'post type singular name', 'wte' ),
		'menu_name'          	=> _x( 'Testimonials', 'admin menu', 'wte' ),
		'name_admin_bar'     	=> _x( 'Testimonial', 'add new on admin bar', 'wte' ),
		'add_new'            	=> _x( 'Add New', 'testimonial', 'wte' ),
		'add_new_item'       	=> __( 'Add New Testimonial', 'wte' ),
		'edit_item'          	=> __( 'Edit Testimonial', 'wte' ),
		'new_item'           	=> __( 'New Testimonial', 'wte' ),
		'view_item'          	=> __( 'View Testimonial', 'wte' ),
		'all_items'          	=> __( 'All Testimonials', 'wte' ),
		'search_items'       	=> __( 'Search Testimonials', 'wte' ),
		'not_found'          	=> __( 'No Testimonials found', 'wte' ),
		'not_found_in_trash' 	=> __( 'No Testimonials found in Trash', 'wte' ),
		'parent_item_colon'  	=> '',
	);

	$args = array(
		'labels'             	=> $labels,
		'public'             	=> false,
		'publicly_queryable' 	=> false,
		'show_ui'            	=> true,
		'can_export'         	=> true,
		'show_in_nav_menus'  	=> false,
		'query_var'          	=> true,
		'has_archive'        	=> true,
		'rewrite'            	=> apply_filters( 'wte_testimonials_post_type_rewrite_args', array(
			'feeds'      	=> true,
			'slug'      	=> 'testimonials',
			'with_front'	=> false,
		) ),
		'capability_type'		=> 'post',
		'hierarchical'       	=> false,
		'menu_position'      	=> '58.995',
		'menu_icon'				=> 'dashicons-testimonial',
		'supports'           	=> array(
									'title',
									// 'editor',
									'thumbnail',
								),
	);

	register_post_type( 'testimonials', apply_filters( 'wte_testimonials_post_type_args', $args ) );

	$labels = array(
		'name'              	=> esc_html__( 'Testimonial Categories', 'wte' ),
		'singular_name'     	=> esc_html__( 'Testimonial Category', 'wte' ),
		'search_items'      	=> esc_html__( 'Search Testimonial Categories', 'wte' ),
		'all_items'         	=> esc_html__( 'All Testimonial Categories', 'wte' ),
		'parent_item'       	=> esc_html__( 'Parent Testimonial Category', 'wte' ),
		'parent_item_colon' 	=> esc_html__( 'Parent Testimonial Category:', 'wte' ),
		'edit_item'         	=> esc_html__( 'Edit Testimonial Category', 'wte' ),
		'update_item'       	=> esc_html__( 'Update Testimonial Category', 'wte' ),
		'add_new_item'      	=> esc_html__( 'Add New Testimonial Category', 'wte' ),
		'new_item_name'     	=> esc_html__( 'New Testimonial Category Name', 'wte' ),
		'menu_name'         	=> esc_html__( 'Categories', 'wte' ),
	);

	register_taxonomy( 'wte_testimonials_category', array( 'testimonials' ), array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true,
		'show_in_nav_menus'  	=> false,
	) );

}

add_action( 'init', 'wte_register_post_type_testimonials' );


/**
 * Register Meta box
 */
function wte_add_testimonials_meta_box() {
	add_meta_box( 
		'wte-testimonials', 
		__( 'Testimonial Info', 'wte' ), 
		'wte_testimonials_meta_box_output', 
		'testimonials', 
		'normal', 
		'high' 
	);
}
add_action( 'add_meta_boxes', 'wte_add_testimonials_meta_box' );


/**
 * Output Meta box
 */
function wte_testimonials_meta_box_output( $post ) {

	// create a nonce field
	wp_nonce_field( 'wte_testimonials_nonce_action', 'wte_testimonials_nonce' );

	$testimonial_text 		= get_post_meta( $post->ID, 'testimonial_text', true );
	$testimonial_author 	= get_post_meta( $post->ID, 'testimonial_author', true );
	$testimonial_link_url 	= get_post_meta( $post->ID, 'testimonial_link_url', true );

	?>

	<p>
	<label for="testimonial_text"><?php _e( 'Text', 'wte' ); ?>:</label>
	<textarea name="testimonial_text" class="widefat"><?php echo esc_attr( $testimonial_text ); ?></textarea>
    </p>

	<p>
	<label><?php _e( 'Author', 'wte' ); ?>:</label>
	<input type="text" name="testimonial_author" class="widefat" value="<?php echo esc_attr( $testimonial_author ); ?>" />
	</p>

	<p>
	<label><?php _e( 'Author URL', 'wte' ); ?>:</label>
	<input type="text" name="testimonial_link_url" class="widefat" value="<?php echo esc_attr( $testimonial_link_url ); ?>" />
	</p>

    <?php

}


/**
 * Save Custom Script Meta box values
 */
function wte_testimonials_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( ! isset( $_POST['wte_testimonials_nonce'] ) || 
		! wp_verify_nonce( $_POST['wte_testimonials_nonce'], 'wte_testimonials_nonce_action' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( ! current_user_can( 'edit_post', get_the_id() ) ) return;

	if( isset( $_POST['testimonial_text'] ) )
		update_post_meta( $post_id, 'testimonial_text', strip_tags( $_POST['testimonial_text'] ) );

	if ( isset( $_POST['testimonial_author'] ) )
		update_post_meta( $post_id, 'testimonial_author', strip_tags( $_POST['testimonial_author'] ) );

	if ( isset( $_POST['testimonial_link_url'] ) )
		update_post_meta( $post_id, 'testimonial_link_url', strip_tags( $_POST['testimonial_link_url'] ) );

}
add_action( 'save_post', 'wte_testimonials_meta_box_save' );

endif;
