<?php
/**
 * Post Type - Slides.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


if ( wte_get_option( 'slider_cpt' ) == 1 ) :
/**
 * Register Post Type.
 */
function wte_register_post_type_slides() {

	$labels = array(
		'name'               	=> _x( 'Slides', 'post type general name', 'wte' ),
		'singular_name'      	=> _x( 'Slide', 'post type singular name', 'wte' ),
		'menu_name'          	=> _x( 'Slides', 'admin menu', 'wte' ),
		'name_admin_bar'     	=> _x( 'Slide', 'add new on admin bar', 'wte' ),
		'add_new'            	=> _x( 'Add New', 'slide', 'wte' ),
		'add_new_item'       	=> __( 'Add New Slide', 'wte' ),
		'edit_item'          	=> __( 'Edit Slide', 'wte' ),
		'new_item'           	=> __( 'New Slide', 'wte' ),
		'view_item'          	=> __( 'View Slide', 'wte' ),
		'all_items'          	=> __( 'All Slides', 'wte' ),
		'search_items'       	=> __( 'Search Slides', 'wte' ),
		'not_found'          	=> __( 'No Slides found', 'wte' ),
		'not_found_in_trash' 	=> __( 'No Slides found in Trash', 'wte' ),
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
		'rewrite'            	=> apply_filters( 'wte_slides_post_type_rewrite_args', array(
			'feeds'      	=> true,
			'slug'      	=> 'slides',
			'with_front'	=> false,
		) ),
		'capability_type'		=> 'post',
		'hierarchical'       	=> false,
		'menu_position'      	=> '58.995',
		'menu_icon'				=> 'dashicons-slides',
		'supports'           	=> array(
									'title',
									// 'editor',
									'thumbnail',
								),
	);

	register_post_type( 'slides', apply_filters( 'wte_slides_post_type_args', $args ) );

	$labels = array(
		'name'              	=> esc_html__( 'Slide Categories', 'wte' ),
		'singular_name'     	=> esc_html__( 'Slide Category', 'wte' ),
		'search_items'      	=> esc_html__( 'Search Slide Categories', 'wte' ),
		'all_items'         	=> esc_html__( 'All Slide Categories', 'wte' ),
		'parent_item'       	=> esc_html__( 'Parent Slide Category', 'wte' ),
		'parent_item_colon' 	=> esc_html__( 'Parent Slide Category:', 'wte' ),
		'edit_item'         	=> esc_html__( 'Edit Slide Category', 'wte' ),
		'update_item'       	=> esc_html__( 'Update Slide Category', 'wte' ),
		'add_new_item'      	=> esc_html__( 'Add New Slide Category', 'wte' ),
		'new_item_name'     	=> esc_html__( 'New Slide Category Name', 'wte' ),
		'menu_name'         	=> esc_html__( 'Categories', 'wte' ),
	);

	register_taxonomy( 'wte_slides_category', array( 'slides' ), array(
		'hierarchical'      	=> true,
		'labels'            	=> $labels,
		'show_ui'           	=> true,
		'show_admin_column' 	=> true,
		'query_var'         	=> true,
		'show_in_nav_menus'  	=> false,
	) );

}

add_action( 'init', 'wte_register_post_type_slides' );


/**
 * Register Meta box
 */
function wte_add_slides_meta_box() {
	add_meta_box( 
		'wte-slides', 
		__( 'Slide URL and Caption', 'wte' ), 
		'wte_slides_meta_box_output', 
		'slides', 
		'normal', 
		'high' 
	);
}
add_action( 'add_meta_boxes', 'wte_add_slides_meta_box' );


/**
 * Output Meta box
 */
function wte_slides_meta_box_output( $post ) {

	// create a nonce field
	wp_nonce_field( 'wte_slides_nonce_action', 'wte_slides_nonce' );

	$slide_link_url = get_post_meta( $post->ID, 'slide_link_url', true );
	$slide_caption 	= get_post_meta( $post->ID, 'slide_caption', true );

	?>

	<p>
	<label><?php _e( 'URL', 'wte' ); ?>:</label>
	<input type="text" name="slide_link_url" class="widefat" value="<?php echo esc_attr( $slide_link_url ); ?>" />
	<span class="description"><?php echo _e( 'The URL this slide should link to.', 'booky' ); ?></span>
	</p>

	<p>
	<label for="slide_caption"><?php _e( 'Caption', 'wte' ); ?>:</label>
	<textarea name="slide_caption" class="widefat"><?php echo esc_attr( $slide_caption ); ?></textarea>
    </p>

    <?php

    printf( '<p>%s<br>%s<br>%s %s</p><p>%s <code>%s</code> %s %s</p>', 
    	__( 'Both fields are optional.', 'wte' ),
    	__( 'Upload a featured image to be displayed in the slider.', 'wte' ),
    	__( 'If the URL is set, the image will link to that specific URL.', 'wte' ),
    	__( 'The caption will be displayed on top of the image if is set.', 'wte' ),
    	__( 'If using multiple sliders, set a slider category and display it in your post using the following code:', 'wte' ),
    	'[slider cat="category-name"]',
    	__( 'where "category-name" must be the name of your category.', 'wte' ),
    	__( 'The above code will display only slides from that specific category.', 'wte' )
    );

}


/**
 * Save Custom Script Meta box values
 */
function wte_slides_meta_box_save( $post_id ) {
	// Stop the script when doing autosave
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	// Verify the nonce. If insn't there, stop the script
	if( ! isset( $_POST['wte_slides_nonce'] ) || 
		! wp_verify_nonce( $_POST['wte_slides_nonce'], 'wte_slides_nonce_action' ) ) return;

	// Stop the script if the user does not have edit permissions
	if( ! current_user_can( 'edit_post', get_the_id() ) ) return;

	if ( isset( $_POST['slide_link_url'] ) )
		update_post_meta( $post_id, 'slide_link_url', strip_tags( $_POST['slide_link_url'] ) );

	if( isset( $_POST['slide_caption'] ) )
		update_post_meta( $post_id, 'slide_caption', strip_tags( $_POST['slide_caption'] ) );

}
add_action( 'save_post', 'wte_slides_meta_box_save' );

endif;
