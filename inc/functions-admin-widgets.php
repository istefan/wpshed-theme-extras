<?php
/**
 * Admin - Sidebars Page.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

/**
 * Admin Notice.
 */
function wte_sidebars_update_admin_notice() {

    $notice_dismiss = sprintf( '<button class="notice-dismiss" type="button"><span class="screen-reader-text">%1$s</span></button>',
        __( 'Dismiss this notice.', 'wte' )
    );

    if ( isset( $_GET['page'] ) && $_GET['page'] == 'wte'  ) {

        // Settings saved message
        if ( isset( $_POST['sidebars-updated'] ) && $_POST['sidebars-updated'] == 'true' ) {
            printf( '<div class="updated notice is-dismissible"><p><strong>%1$s</strong></p>%2$s</div>',
                __( 'Sidebars Saved!', 'wte' ),
                $notice_dismiss
            );
        }

    }

}
add_action( 'admin_notices', 'wte_sidebars_update_admin_notice' );


/**
 * Update Sidebars.
 */
 function wte_uptade_sidebars() {

 	$current_theme = strtolower( str_replace( '-', '_', get_option( 'template' ) ) );

 	if ( isset( $_GET['page'] ) && $_GET['page'] == 'wte' ) {

		if( ! isset( $_POST['wte_sidebars_nonce'] ) || ! wp_verify_nonce( $_POST['wte_sidebars_nonce'], 'wte_sidebars' ) ) 
			return;

		if( isset( $_POST['wte-section'] ) ) {

			$sections = array();

			foreach( $_POST['wte-section'] as $k => $v ) {

				$sections[] = array(
					sanitize_title_with_dashes( trim( $v ) ) => array(
						'text_align'   	=> $_POST['text_align'][$k],
						'wrap'   		=> $_POST['wrap'][$k],
						'padding'   	=> $_POST['padding'][$k],
						'class'			=> esc_attr( $_POST['class'][$k] ),
						'divider'		=> $_POST['divider'][$k],
						'widgets'		=> $_POST['sidebar'][$v],
					)
				);
			}

			update_option( $current_theme . '_custom_widgets', $sections );

		}

		// echo "<textarea class='widefat' rows='50'>";
		// print_r( $sections );

 	}

 }
add_action( 'admin_head', 'wte_uptade_sidebars' );


add_action( 'wte_widgets_tab', 'wte_widgets_tab' );
/**
 * Settings page HTML.
 */
function wte_widgets_tab() {

	$page_url 		= esc_url( admin_url( 'themes.php?page=wte&tab=widgets' ) );
	$customize_url 	= "customize.php?autofocus%5Bpanel%5D=widgets&return={$page_url}";
	?>

    <h3><?php _e( 'Create custom Sections with Sidebars', 'wte' ); ?></h3>

    <p class="hide-if-js">
    	<?php _e( 'You must have JavaScript enabled in order to use the widget area builder.', 'wte' ); ?>
    </p>

    <div class="wte-sidebars-frame wp-clearfix hide-if-no-js">

    <form method="post" action="">
  	<?php wp_nonce_field( 'wte_sidebars', 'wte_sidebars_nonce' ); ?>

  	<input type="hidden" name="sidebars-updated" value="true">

    	<div class="wte-sidebars-header wp-clearfix">
			<div class="wte-sidebars-controls">
				<a class="button button-secondary add-section" href="#" title="<?php _e( 'Add Section', 'wte' ); ?>">
					<?php _e( 'Add Section', 'wte' ); ?>
				</a>
				<a href="<?php echo $customize_url; ?>" target="_blank">
					<span class="dashicons dashicons-external"></span>
					<?php _e( 'Manage Widgets', 'wte' ); ?>
				</a>
			</div>

			<div class="wte-sidebars-publishing-action">
				<?php submit_button( __( 'Save Sidebars', 'wte' ), 'primary save-layout', false, false ); ?>
			</div>
    	</div><!-- .wte-sidebars-header -->

		<div class="wte-sidebars-post-body wp-clearfix">

		<?php $widgets = wte_default_widgets(); ?>

		<?php if( ! empty( $widgets ) ) : ?>

			<?php foreach( $widgets as $key => $sections ) : ?>
				<?php 

				foreach( $sections as $section => $args ) : 

				// Sanitize section ID
				$section = sanitize_title_with_dashes( trim( $section ) );

				// Sanitize default Widget name
				$name 	= ucwords( strtolower( str_replace( '_', ' ', $section ) ) );

				// Define the array of defaults 
				$section_defaults = array(
					'text_align'   	=> '',
					'wrap'   		=> 'wrap',
					'padding'   	=> 'true',
					'class'			=> '',
					'divider'		=> '',
					'widgets'		=> array( $section => array( 'name' => $name ) )
				);

				// Parse incoming $args into an array and merge it with $defaults
				$args = wp_parse_args( $args, $section_defaults );

				// get widget names
				foreach ( $args['widgets'] as $widget => $w ) {
					$widget_names[] = $w['name'];
				}

				// Create the section title
				$section_title = sprintf( '%s <span class="in-widget-title">: %s</span>',
					__( 'Custom Sidebars', 'wte' ),
					implode( ', ', $widget_names )
				);

				?>

				<div class="widget" data-name="<?php echo esc_attr( $name ); ?>">

					<div class="widget-top">
						<div class="widget-title-action">
							<a class="widget-action" href="#"></a>
						</div><!-- .widget-title-action -->
						<div class="widget-title">
							<h3 class="section-title">
								<?php echo $section_title; ?>
							</h3>
						</div><!-- .widget-title -->
					</div><!-- .widget-top -->
					<div class="widget-inside widgets-holder wp-clearfix">

						<div class="manage-menus wp-clearfix">

							<input type="hidden" name="wte-section[]" value="<?php echo esc_attr( $section ); ?>" class="wte-section">
						
							<select name="text_align[]">
								<?php foreach ( wte_sidebars_text_align() as $key => $value ) {
									printf( '<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										selected( esc_attr( $args['text_align'] ), esc_attr( $key ), false ),
										esc_attr( $value )
									);
								} ?>
							</select>

							<select name="wrap[]">
								<?php foreach ( wte_sidebars_wrap() as $key => $value ) {
									printf( '<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										selected( esc_attr( $args['wrap'] ), esc_attr( $key ), false ),
										esc_attr( $value )
									);
								} ?>
							</select>

							<select name="padding[]">
								<?php foreach ( wte_sidebars_padding() as $key => $value ) {
									printf( '<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										selected( esc_attr( $args['padding'] ), esc_attr( $key ), false ),
										esc_attr( $value )
									);
								} ?>
							</select>

							<select name="divider[]">
								<?php foreach ( wte_sidebars_divider() as $key => $value ) {
									printf( '<option value="%s" %s>%s</option>',
										esc_attr( $key ),
										selected( esc_attr( $args['divider'] ), esc_attr( $key ), false ),
										esc_attr( $value )
									);
								} ?>
							</select>
				
							<?php _e( 'Custom Class', 'wte' ); ?>:
							<input type="text" value="<?php echo esc_attr( $args['class'] ); ?>" name="class[]">

							<a href="#" class="alignright remove-section" title="<?php _e( 'Delete Section', 'wte' ); ?>">
								<span class="dashicons dashicons-no"></span>
							</a>

						</div>

    					<?php 

    					$widget_names = array(); // empty $widget_names

    					foreach ( $args['widgets'] as $widget => $w ) : 

						// Define the array of defaults 
						$widget_defaults = array(
							'name'   		=> ucwords( strtolower( str_replace( '_', ' ', $widget ) ) ),
							'id'   			=> sanitize_title_with_dashes( trim( $widget ) ),
							'description'   => '',
							'grid'   		=> false,
							'first'   		=> false,
							'before_title'  => '<h2>',
							'after_title'   => '</h2>',
						);

						// Parse incoming $w into an array and merge it with $defaults
						$w = wp_parse_args( $w, $widget_defaults );

						$first = ( $w['first'] ) ? 'first' : '';
						$grid = ( $w['grid'] ) ? $w['grid'] : 'no-grid';

    					?>
    					<div class="widget-container <?php echo $grid; ?> <?php echo $first; ?>">

	    					<div class="widget-top">
	    						<div class="widget-title">
	    							<h3 class="sidebar-title"><?php _e( 'Custom Sidebar', 'wte' ); ?></h3>
	    						</div><!-- .widget-title -->
	    					</div><!-- .widget-top -->

	    					<div class="widget-inside wp-clearfix" style="display: block;">

								<?php _e( 'Sidebar Name', 'wte' ); ?>:<br>
								<input type="text" name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][name]" value="<?php echo esc_attr( $w['name'] ); ?>" class="widget-input sidebar-title-input sidebar-s-name" >
								<input type="hidden" name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][id]" value="<?php echo esc_attr( $w['id'] ); ?>" class="sidebar-s-id">

								<select name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][grid]" class="widget-grid widget-input widget-select sidebar-s-grid">
									<?php foreach ( wte_sidebars_grid() as $key => $value ) {
										printf( '<option value="%s" %s>%s</option>',
											esc_attr( $key ),
											selected( esc_attr( $w['grid'] ), esc_attr( $key ), false ),
											esc_attr( $value )
										);
									} ?>
								</select>

								<select name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][first]" class="widget-first widget-input widget-select sidebar-s-first">
									<?php foreach ( wte_sidebars_grid_first() as $key => $value ) {
										printf( '<option value="%s" %s>%s</option>',
											esc_attr( $key ),
											selected( esc_attr( $w['first'] ), esc_attr( $key ), false ),
											esc_attr( $value )
										);
									} ?>
								</select>

								<select name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][before_title]" class="widget-heading widget-select widget-input h-select sidebar-s-before_title">
									<?php foreach ( wte_sidebars_heading() as $key => $value ) {
										printf( '<option value="%s" %s>%s</option>',
											esc_attr( $key ),
											selected( esc_attr( $w['before_title'] ), esc_attr( $key ), false ),
											esc_attr( $value )
										);
									} ?>
								</select>

								<input type="hidden" name="sidebar[<?php echo $section; ?>][<?php echo $w['id']; ?>][after_title]" value="<?php echo str_replace( '<', '</', $w['before_title'] ); ?>" class="h-title sidebar-s-after_title">
								
								<br><br>

								<a href="#" class="button secondary add-sidebar">
									<?php _e( 'Add Sidebar', 'wte' ); ?>
								</a>
								<a href="#" class="alignright remove-sidebar" title="<?php _e( 'Delete Sidebar', 'wte' ); ?>">
									<span class="dashicons dashicons-no"></span>
								</a>

							</div><!-- .widget-inside -->

						</div><!-- .widget-container -->

    					<?php endforeach; ?>

					</div><!-- .widget-inside -->

				</div><!-- .widget -->

				<?php endforeach; ?>
			<?php endforeach; ?>

		<?php endif; ?>

    	</div><!-- .wte-sidebars-post-body -->

    	<div class="wte-sidebars-footer wp-clearfix">
			<div class="wte-sidebars-controls">
				<a class="button button-secondary add-section" href="#" title="<?php _e( 'Add Section', 'wte' ); ?>">
					<?php _e( 'Add Section', 'wte' ); ?>
				</a>
				<a href="<?php echo $customize_url; ?>" target="_blank">
					<span class="dashicons dashicons-external"></span>
					<?php _e( 'Manage Widgets', 'wte' ); ?>
				</a>
			</div>

			<div class="wte-sidebars-publishing-action">
				<?php submit_button( __( 'Save Sidebars', 'wte' ), 'primary save-layout', false, false ); ?>
			</div>
    	</div><!-- .wte-sidebars-footer -->

    </form>
    </div><!-- .wte-sidebars-frame -->

    <div class="default-section">
		<div class="widget" >

			<div class="widget-top">
				<div class="widget-title-action">
					<a class="widget-action" href="#"></a>
				</div><!-- .widget-title-action -->
				<div class="widget-title">
					<h3 class="section-title">Section</h3>
				</div><!-- .widget-title -->
			</div><!-- .widget-top -->

			<div class="widget-inside widgets-holder wp-clearfix">

				<div class="manage-menus wp-clearfix">

					<input type="hidden" name="wte-section[]" value="<?php echo esc_attr( $section ); ?>" class="wte-section">
				
						<select name="text_align[]">
							<?php foreach ( wte_sidebars_text_align() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<select name="wrap[]">
							<?php foreach ( wte_sidebars_wrap() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<select name="padding[]">
							<?php foreach ( wte_sidebars_padding() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<select name="divider[]">
							<?php foreach ( wte_sidebars_divider() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>
		
					<?php _e( 'Custom Class', 'wte' ); ?>:
					<input type="text" value="" name="class[]">

					<a href="#" class="alignright remove-section" title="<?php _e( 'Delete Section', 'wte' ); ?>">
						<span class="dashicons dashicons-no"></span>
					</a>

				</div>

				<div class="widget-container full-width">

					<div class="widget-top">
						<div class="widget-title">
							<h3 class="sidebar-title"><?php _e( 'Custom Sidebar', 'wte' ); ?></h3>
						</div><!-- .widget-title -->
					</div><!-- .widget-top -->

					<div class="widget-inside wp-clearfix" style="display: block;">

						<?php _e( 'Sidebar Name', 'wte' ); ?>:<br>
						<input type="text" name="" value="" class="widget-input sidebar-title-input sidebar-s-name" >
						<input type="hidden" name="" value="" class="sidebar-s-id">

						<select name="sidebar[grid]" class="widget-grid widget-input widget-select sidebar-s-grid">
							<?php foreach ( wte_sidebars_grid() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<select name="sidebar][first]" class="widget-first widget-input widget-select sidebar-s-first">
							<?php foreach ( wte_sidebars_grid_first() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<select name="sidebar[before_title]" class="widget-heading widget-select widget-input h-select sidebar-s-before_title">
							<?php foreach ( wte_sidebars_heading() as $key => $value ) {
								printf( '<option value="%s">%s</option>',
									esc_attr( $key ),
									esc_attr( $value )
								);
							} ?>
						</select>

						<input type="hidden" name="" value="" class="h-title sidebar-s-after_title">
						
						<br><br>

						<a href="#" class="button secondary add-sidebar">
							<?php _e( 'Add Sidebar', 'wte' ); ?>
						</a>
						<a href="#" class="alignright remove-sidebar" title="<?php _e( 'Delete Sidebar', 'wte' ); ?>">
							<span class="dashicons dashicons-no"></span>
						</a>

					</div><!-- .widget-inside -->

				</div><!-- .widget-container -->

			</div><!-- .widget-inside -->

		</div><!-- .widget -->
    </div><!-- .default-section -->

	<?php

}
