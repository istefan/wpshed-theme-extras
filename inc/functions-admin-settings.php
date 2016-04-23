<?php
/**
 * Admin - Settings Page.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

/**
 * Init plugin options to white list our options.
 */
function wte_options_init() {
    register_setting( 'wte', 'wte' );
}
add_action( 'admin_init', 'wte_options_init' );


/**
 * Admin Notice.
 */
function wte_settings_update_admin_notice() {

    $notice_dismiss = sprintf( '<button class="notice-dismiss" type="button"><span class="screen-reader-text">%1$s</span></button>',
        __( 'Dismiss this notice.', 'wte' )
    );

    if ( isset( $_GET['page'] ) && $_GET['page'] == 'wte'  ) {

        // Settings saved message
        if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
            printf( '<div class="updated notice is-dismissible"><p><strong>%1$s</strong></p>%2$s</div>',
                __( 'Settings Saved!', 'wte' ),
                $notice_dismiss
            );
        }

    }

}
add_action( 'admin_notices', 'wte_settings_update_admin_notice' );


add_action( 'wte_settings_tab', 'wte_settings_tab' );
/**
 * Settings page HTML.
 */
function wte_settings_tab() {
	?>

    <form method="post" action="options.php">
    <?php settings_fields( 'wte' ); ?>

	<table class="form-table">

	<tr valign="top"><th scope="row"><?php _e( 'Custom Post Types', 'wte' ); ?></th>
	    <td>
	    	<fieldset>

	    	<label>
	        <input name="wte[portfolio_cpt]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'portfolio_cpt' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Portfolio', 'wte' ), 
		        	__( 'Custom Post Type', 'wte' ) 
	        	); 
	        ?>
	        <?php _e( 'slug', 'wte' ); ?>:
	        <input type="text" name="wte[slug]" value="<?php echo esc_attr( wte_get_option( 'slug', 'portfolio' ) );?>" class="regular-text" style="width:120px;" />
	        </label>
	        <br>
	        <label class="description">
	        <input name="wte[portfolio_cat]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'portfolio_cat' ) ); ?> />
	        <?php _e( 'Enable Portfolio Categories', 'wte' ); ?>
	        </label>
	        <label class="description">
	        <input name="wte[portfolio_tag]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'portfolio_tag' ) ); ?> />
	        <?php _e( 'Enable Portfolio Tags', 'wte' ); ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[testimonial_cpt]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'testimonial_cpt' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Testimonials', 'wte' ), 
		        	__( 'Custom Post Type', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[slider_cpt]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'slider_cpt' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Slides', 'wte' ), 
		        	__( 'Custom Post Type', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>
	        <?php printf( '<span class="description">%s <a href="%s">%s</a> %s<span>',
	        	__( 'Once you enable a custom post type, please go to', 'wte' ),
	        	esc_url( admin_url( 'options-permalink.php' ) ),
	        	__( 'Settings - Permalinks', 'wte' ),
	        	__( 'and update the permalink structure.', 'wte' )
	        ); ?>

	        </fieldset>
	    </td>
	</tr>

	</table>

	<table class="form-table">

	<tr valign="top"><th scope="row"><?php _e( 'Custom Widgets', 'wte' ); ?></th>
	    <td>
	    	<fieldset>
	    	<?php if ( wte_get_option( 'portfolio_cpt' ) == 1 ) : ?>
	    	<label>
	        <input name="wte[portfolio_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'portfolio_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Portfolio', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>
	    	<?php else : ?>
	    	<input type="hidden" name="wte[portfolio_widget]" value="<?php echo wte_get_option( 'portfolio_widget' ); ?>" />
	    	<?php endif; ?>

	    	<?php if ( wte_get_option( 'testimonial_cpt' ) == 1 ) : ?>
	    	<label>
	        <input name="wte[testimonial_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'testimonial_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Testimonials', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>
	    	<?php else : ?>
	    	<input type="hidden" name="wte[testimonial_widget]" value="<?php echo wte_get_option( 'testimonial_widget' ); ?>" />
	    	<?php endif; ?>

	        <?php if ( wte_get_option( 'slider_cpt' ) == 1 ) : ?>
	    	<label>
	        <input name="wte[slider_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'slider_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Slides', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>
	    	<?php else : ?>
	    	<input type="hidden" name="wte[slider_widget]" value="<?php echo wte_get_option( 'slider_widget' ); ?>" />
	    	<?php endif; ?>

	    	<label>
	        <input name="wte[feat_post_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'feat_post_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Featured Posts', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[feat_page_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'feat_page_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Featured Page', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[user_profile_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'user_profile_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Author', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[services_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'services_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Services', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[price_table_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'price_table_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Price Table', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[hero_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'hero_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Hero', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[image_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'image_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Image', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[social_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'social_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Social Icons', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[cta_widget]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'cta_widget' ) ); ?> />
	        <?php 
	        	printf( '%s <code>%s</code> %s.', 
		        	__( 'Enable', 'wte' ), 
		        	__( 'Call To Action', 'wte' ), 
		        	__( 'Widget', 'wte' ) 
	        	); 
	        ?>
	        </label>
	        <br>

	        </fieldset>
	    </td>
	</tr>

	</table>

	<?php submit_button(); ?>

	<table class="form-table">

	<tr valign="top"><th scope="row"><?php _e( 'Extras', 'wte' ); ?></th>
	    <td>
	    	<fieldset>
	    	
	    	<label>
	        <input name="wte[fade_up]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'fade_up' ) ); ?> />
	        <?php _e( 'Enable Fade-Up effect for widgets.', 'wte' ); ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[fade_in]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'fade_in' ) ); ?> />
	        <?php _e( 'Enable Fade-In effect on page load.', 'wte' ); ?>
	        </label>
	        <br>

	    	<label>
	        <input name="wte[local_scroll]" type="checkbox" value="1" <?php checked( '1', wte_get_option( 'local_scroll' ) ); ?> />
	        <?php _e( 'Enable Local Scroll script.', 'wte' ); ?>
	        </label><br>
	        <span class="description"><?php _e( 'This will enable a smooth navigation to a page ID in the document.', 'wte' ); ?></span>
	        <br>

	        </fieldset>
	    </td>
	</tr>

	</table>

	<table class="form-table">

	<tr valign="top"><th scope="row"><?php _e( 'Portfolio Thumbnail', 'wte' ); ?></th>
	    <td>
	    	<label><?php _e( 'Width', 'wte' ); ?>:</label>
	        <input type="number" name="wte[portfolio_thumb_w]" value="<?php wte_option( 'portfolio_thumb_w', '1280' ); ?>" class="small-text" />
	        <label><?php _e( 'Height', 'wte' ); ?>:</label>
	        <input type="number" name="wte[portfolio_thumb_h]" value="<?php wte_option( 'portfolio_thumb_h', '800' ); ?>" class="small-text" />px
	    </td>
	</tr>

	<tr valign="top"><th scope="row"><?php _e( 'Testimonials Thumbnail', 'wte' ); ?></th>
	    <td>
	    	<label><?php _e( 'Width', 'wte' ); ?>:</label>
	        <input type="number" name="wte[testimonial_thumb_w]" value="<?php wte_option( 'testimonial_thumb_w', '96' ); ?>" class="small-text" />
	        <label><?php _e( 'Height', 'wte' ); ?>:</label>
	        <input type="number" name="wte[testimonial_thumb_h]" value="<?php wte_option( 'testimonial_thumb_h', '96' ); ?>" class="small-text" />px
	    </td>
	</tr>

	<tr valign="top"><th scope="row"><?php _e( 'Slides Thumbnail', 'wte' ); ?></th>
	    <td>
	    	<label><?php _e( 'Width', 'wte' ); ?>:</label>
	        <input type="number" name="wte[slider_thumb_w]" value="<?php wte_option( 'slider_thumb_w', '1920' ); ?>" class="small-text" />
	        <label><?php _e( 'Height', 'wte' ); ?>:</label>
	        <input type="number" name="wte[slider_thumb_h]" value="<?php wte_option( 'slider_thumb_h', '800' ); ?>" class="small-text" />px
	    </td>
	</tr>

	</table>

	<?php submit_button(); ?>

	</form>

	<?php

}
