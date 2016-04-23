<?php
/**
 * Admin Page.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

/**
 * Add menu page.
 */
function wte_add_dashboard_page() {

    // Add a new dashboard menu:
    $menu = add_theme_page(
        __( 'Theme Extras', 'wte' ),
        __( 'Theme Extras', 'wte' ),
        'manage_options',
        'wte',
        'wte_dashboard_page'
    );

    add_action( 'admin_print_styles-' . $menu, 'wte_admin_css' );
    add_action( 'admin_print_scripts-' . $menu, 'wte_admin_js' );

}
add_action( 'admin_menu', 'wte_add_dashboard_page' );


/**
 * Register Admin Styles.
 */
function wte_admin_css() {
	wp_enqueue_style( 'wte-css', WTE_CSS . 'admin.css', array(), null );
	wp_enqueue_style( 'thickbox' );
}


/**
 * Register Admin Scripts.
 */
function wte_admin_js() {
	wp_enqueue_script( 'wte-js', WTE_JS . 'admin.js', array( 'jquery' ), '1.0.3', true );
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-sortable' );

	$theme_slug 	= get_option( 'template' );
	$theme 			= wp_get_theme( $theme_slug );
	$theme_name 	= $theme->get( 'Name' );

	wp_localize_script( 'wte-js', 'wte_vars', array(
			'delete_section' 	=> __( 'Are you sure you want to delete this Section?', 'wte' ),
			'min_sections' 		=> __( 'Sorry, you need at least one Section', 'wte' ),
			'delete_sidebar' 	=> __( 'Are you sure you want to delete this Sidebar?', 'wte' ),
			'min_sidebars' 		=> __( 'Sorry, you need at least one Sidebar', 'wte' ),
			'theme_slug' 		=> $theme_slug,
			'theme_name' 		=> $theme_name,
			'custom_sidebars' 	=> __( 'Custom Sidebars', 'wte' ),
		)
	);
}


/**
 * Settings page HTML.
 */
function wte_dashboard_page() {
	?>

	<div class="wrap">

	<h1><?php _e( 'WPshed Theme Extras', 'wte' ); ?></h1>

    <?php $tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : 'settings'; ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=wte&amp;tab=settings" class="nav-tab <?php echo $tab == 'settings' ? 'nav-tab-active' : ''; ?>">
        	<?php _e( 'Settings', 'wte' ); ?>
        </a>
        <?php if ( current_theme_supports( 'wte-widgets' ) ) : ?>
		<a href="?page=wte&amp;tab=widgets" class="nav-tab <?php echo $tab == 'widgets' ? 'nav-tab-active' : ''; ?>">
        	<?php _e( 'Sidebars', 'wte' ); ?>
        </a>
        <?php endif; ?>
<!-- 		<a href="?page=wte&amp;tab=themes" class="nav-tab <?php echo $tab == 'themes' ? 'nav-tab-active' : ''; ?>">
        	<?php _e( 'Themes', 'wte' ); ?>
        </a>
		<a href="?page=wte&amp;tab=help" class="nav-tab <?php echo $tab == 'help' ? 'nav-tab-active' : ''; ?>">
        	<?php _e( 'Tutorials', 'wte' ); ?>
        </a> -->
    </h2>

    <?php

    if ( $tab == 'help' ) : // help tab
    
    	do_action( 'wte_help_tab' );

    elseif ( $tab == 'widgets' ) : // widgets tab

    	do_action( 'wte_widgets_tab' );

    elseif ( $tab == 'themes' ) : // themes tab

    	do_action( 'wte_themes_tab' );

    else: // the firs and default tab

    	do_action( 'wte_settings_tab' );

    endif; ?>

	</div><!-- .wrap -->

<?php

}
