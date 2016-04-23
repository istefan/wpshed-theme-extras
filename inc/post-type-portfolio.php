<?php
/**
 * Post Type - Project.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


if ( wte_get_option( 'portfolio_cpt' ) == 1 ) :
/**
 * Register Post Type.
 */
function wte_register_post_type_portfolio() {

	$slug = ( wte_get_option( 'slug' ) ) ? sanitize_title( wte_get_option( 'slug' ) ) : 'portfolio';

	$labels = array(
		'name'               	=> _x( 'Projects', 'post type general name', 'wte' ),
		'singular_name'      	=> _x( 'Project', 'post type singular name', 'wte' ),
		'menu_name'          	=> _x( 'Portfolio', 'admin menu', 'wte' ),
		'name_admin_bar'     	=> _x( 'Project', 'add new on admin bar', 'wte' ),
		'add_new'            	=> _x( 'Add New', 'portfolio', 'wte' ),
		'add_new_item'       	=> __( 'Add New Project', 'wte' ),
		'edit_item'          	=> __( 'Edit Project', 'wte' ),
		'new_item'           	=> __( 'New Project', 'wte' ),
		'view_item'          	=> __( 'View Projects', 'wte' ),
		'all_items'          	=> __( 'All Projects', 'wte' ),
		'search_items'       	=> __( 'Search Projects', 'wte' ),
		'not_found'          	=> __( 'No Projects found', 'wte' ),
		'not_found_in_trash' 	=> __( 'No Projects found in Trash', 'wte' ),
		'parent_item_colon'  	=> '',
	);

	$args = array(
		'labels'             	=> $labels,
		'public'             	=> true,
		'publicly_queryable' 	=> true,
		'show_ui'            	=> true,
		'can_export'         	=> true,
		'show_in_nav_menus'  	=> true,
		'query_var'          	=> true,
		'has_archive'        	=> true,
		'rewrite'            	=> apply_filters( 'wte_portfolio_post_type_rewrite_args', array(
			'feeds'      	=> true,
			'slug'      	=> $slug,
			'with_front'	=> false,
		) ),
		'capability_type'		=> 'post',
		'hierarchical'       	=> false,
		'menu_position'      	=> '58.995',
		'menu_icon'				=> 'dashicons-portfolio',
		'supports'           	=> array(
									'title',
									'editor',
									'thumbnail',
									// 'excerpt',
								),
	);

	register_post_type( 'portfolio', apply_filters( 'wte_portfolio_post_type_args', $args ) );

	if ( wte_get_option( 'portfolio_cat' ) == 1 ) {

		$labels = array(
			'name'              	=> esc_html__( 'Project Categories', 'wte' ),
			'singular_name'     	=> esc_html__( 'Project Category', 'wte' ),
			'search_items'      	=> esc_html__( 'Search Project Categories', 'wte' ),
			'all_items'         	=> esc_html__( 'All Project Categories', 'wte' ),
			'parent_item'       	=> esc_html__( 'Parent Project Category', 'wte' ),
			'parent_item_colon' 	=> esc_html__( 'Parent Project Category:', 'wte' ),
			'edit_item'         	=> esc_html__( 'Edit Project Category', 'wte' ),
			'update_item'       	=> esc_html__( 'Update Project Category', 'wte' ),
			'add_new_item'      	=> esc_html__( 'Add New Project Category', 'wte' ),
			'new_item_name'     	=> esc_html__( 'New Project Category Name', 'wte' ),
			'menu_name'         	=> esc_html__( 'Categories', 'wte' ),
		);

		register_taxonomy( 'wte_portfolio_category', array( 'portfolio' ), array(
			'hierarchical'      	=> true,
			'labels'            	=> $labels,
			'show_ui'           	=> true,
			'show_admin_column' 	=> true,
			'query_var'         	=> true,
		) );

	}

	if ( wte_get_option( 'portfolio_tag' ) == 1 ) {

		$labels = array(
			'name'              	=> esc_html__( 'Project Tags', 'wte' ),
			'singular_name'     	=> esc_html__( 'Project Tag', 'wte' ),
			'search_items'      	=> esc_html__( 'Search Project Tags', 'wte' ),
			'all_items'        		=> esc_html__( 'All Project Tags', 'wte' ),
			'parent_item'       	=> esc_html__( 'Parent Project Tag', 'wte' ),
			'parent_item_colon' 	=> esc_html__( 'Parent Project Tag:', 'wte' ),
			'edit_item'         	=> esc_html__( 'Edit Project Tag', 'wte' ),
			'update_item'       	=> esc_html__( 'Update Project Tag', 'wte' ),
			'add_new_item'      	=> esc_html__( 'Add New Project Tag', 'wte' ),
			'new_item_name'     	=> esc_html__( 'New Project Tag Name', 'wte' ),
			'menu_name'         	=> esc_html__( 'Tags', 'wte' ),
		);

		register_taxonomy( 'wte_portfolio_tag', array( 'portfolio' ), array(
			'hierarchical'      	=> false,
			'labels'            	=> $labels,
			'show_ui'           	=> true,
			'show_admin_column' 	=> true,
			'query_var'         	=> true,
			'show_in_nav_menus'  	=> false,
		) );

	}

}

add_action( 'init', 'wte_register_post_type_portfolio' );

endif;
