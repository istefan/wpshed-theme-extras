<?php
/**
 * Fade Up Functions.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */

/**
 * Add fade up control to widgets.
 */
if ( wte_get_option( 'fade_up' ) == 1 ) {
    add_action( 'in_widget_form', 'wte_in_widget_form', 5, 3 );
    add_filter( 'widget_update_callback', 'wte_in_widget_form_update', 5, 3 );
    add_filter( 'dynamic_sidebar_params', 'wte_dynamic_sidebar_params' );
}


/**
 * Register the form elements (The widget Control).
 */
function wte_in_widget_form( $t, $return, $instance ) {

    $instance = wp_parse_args( (array) $instance, array( 'fade_up' => '' ) );

    $value = isset( $instance['fade_up'] ) ? $instance['fade_up'] : 0;

    // Add fade-up effect.
    wte_form( array(
        'type'      => 'checkbox',
        'label'     => __( 'Add fade-up effect.', 'wte' ),
        'id'        => $t->get_field_id( 'fade_up' ),
        'name'      => $t->get_field_name( 'fade_up' ),
        'value'     => $value,
    ) );

    $retrun = null;
    
    return array( $t, $return, $instance );

}


/**
 * Save the Widget input data.
 */
function wte_in_widget_form_update( $instance, $new_instance, $old_instance ) {
    $instance['fade_up'] = isset( $new_instance['fade_up'] );
    return $instance;
}


/**
 * Display the value in widget output.
 */
function wte_dynamic_sidebar_params( $params ) {

    global $wp_registered_widgets;
    
    $widget_id  = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];
    
    if ( isset( $widget_opt[$widget_num]['fade_up'] ) && $widget_opt[$widget_num]['fade_up'] == 1 ) {
        $params[0]['before_widget'] = $params[0]['before_widget'] . '<div class="wte-fade-up">';
        $params[0]['after_widget']  = '</div>' . $params[0]['after_widget'];
    }

    return $params;
}
