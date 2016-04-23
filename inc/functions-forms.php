<?php
/**
 * Widget Form Fields.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Generate Widget Forms.
 */
function wte_form( $args = array() ) {

    $defaults = array(
        'type'          => 'text',
        'label'         => 'label',
        'id'            => 'id',
        'name'          => 'name',
        'value'         => '',
        'options'       => array(),
        'size'         	=> '',
        'taxonomy'      => '',
    );

    $opt = wp_parse_args( $args, $defaults );

    $type   = esc_attr( $opt['type'] );
    $label  = esc_attr( $opt['label'] );
    $id     = esc_attr( $opt['id'] );
    $name   = esc_attr( $opt['name'] );
    $size   = esc_attr( $opt['size'] );
    $tax   	= esc_attr( $opt['taxonomy'] );
    $value  = $opt['value'];
    $options= (array)$opt['options'];
    $select = esc_attr( __( '&mdash; Select &mdash;', 'wpshed' ) );

    $html = '<p>';
    if ( $type != 'checkbox' )
        $html .= "<label for='{$id}'>{$label}:</label>";

    switch ( $type ) {

        case 'text':
        	$style = ( $size != '' ) ? 'style="width:'. $size .';"' : '';
            $html .= "<input type='text' id='{$id}' name='{$name}' value='{$value}' class='widefat' {$style}/>";
            break;

        case 'number':
            $html .= " <input type='number' id='{$id}' name='{$name}' value='{$value}' class='widefat' style='width:50px;'/>";
            break;

        case 'textarea':
            $html .= "<textarea id='{$id}' name='{$name}' class='widefat' rows='6' cols='4'>{$value}</textarea>";
            break;

        case 'checkbox':
            $checked = checked( $value, 1, false );
            $html .= "<input type='checkbox' id='{$id}' name='{$name}' value='1' {$checked}/>";
            $html .= "<label for='{$id}'>{$label}</label>";
            break;

        case 'select':
            $html .= "<select id='{$id}' name='{$name}' class='widefat'>";
            // $html .= "<option value=''>{$select}</option>";
            foreach ( $options as $key => $val ) {
                $selected = selected( $key, $value, false );
                $html .= "<option value='{$key}' {$selected}>{$val}</option>";
            }
            $html .= "</select>";
            break;

        case 'radio':
            $html .= "<br />";
            foreach ( $options as $key => $val ) {
                $checked = checked( $value, $key, false );
                $html .= "<label>";
                $html .= "<input type='radio' id='{$id}' name='{$name}' value={$key} {$checked}/>";
                $html .= "{$val}</label><br />";
            }
            break;

        case 'page':
			$pages_args = array(
				'name' 				=> $name,
				'show_option_none' 	=> $select,
				'selected' 			=> $value,
				'echo' 				=> false,
			);
            $html .= wp_dropdown_pages( $pages_args );
            break;

        case 'user':
			$user_args = array(
				'name' 				=> $name,
				'show_option_none' 	=> $select,
				'selected' 			=> $value,
				'who' 				=> 'authors',
				'echo' 				=> false,
			);
            $html .= wp_dropdown_users( $user_args );
            break;

        case 'category':
			$categories_args = array(
				'name'            => $name,
				'selected'        => $value,
				'id'        	  => $id,
				'orderby'         => 'Name',
				'hierarchical'    => 1,
				'show_option_all' => __( 'All Categories', 'wte' ),
				// 'show_option_none'=> $select,
				'hide_empty'      => '0',
				'echo' 			  => false,
			);
			if ( $tax != '' ) $categories_args['taxonomy'] = $tax;
            $html .= wp_dropdown_categories( $categories_args );
            break;

        case 'color':
            $html .= "<br />";
            $html .= "<input type='text' id='{$id}' name='{$name}' value='{$value}' class='color-picker' />";
            break;

        case 'image':
            $img_style = ( $value != '' ) ? '' : 'style="display:none;"';
            $no_img_style = ( $value != '' ) ? 'style="display:none;"' : '';
            $no_img_selected = esc_attr( __( 'No image selected', 'wpshed' ) );
            $remove_img = __( 'Remove', 'wpshed' );
            $button_text = ( $value != '' ) ? __( 'Change Image', 'wpshed' ) : __( 'Select Image', 'wpshed' );
            $html .= "<div class='wpshed-media-container'>";
            $html .= "<div class='wpshed-media-inner'>";
            $html .= "<img id='{$id}-preview' src='{$value}' {$img_style}/>";
            $html .= "<span class='wpshed-no-image' id='{$id}-noimg' {$no_img_style}>{$no_img_selected}</span>";
            $html .= "</div>";
            $html .= "<input type='text' id='{$id}' name='{$name}' value='{$value}' class='wpshed-media-url' />";
            $html .= "<input type='button' value='{$remove_img}' class='button wpshed-media-remove' id='{$id}-remove' {$img_style}/>";
            $html .= "<input type='button' value='$button_text' class='button wpshed-media-upload' id='{$id}-button' />";
            $html .= "<br class='clear'>";
            $html .= "</div>";
            break;

    }

    $html .= '</p>';

    echo $html;

}
