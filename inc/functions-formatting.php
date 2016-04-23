<?php
/**
 * Formattig Functions.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Fix linebreak in shortcode content.
 */
function wte_parse_content( $content ) {
    /* Parse nested shortcodes and add formatting. */
    $content = trim( wpautop( do_shortcode( $content ) ) );
    /* Remove '</p>' from the start of the string. */
    if ( substr( $content, 0, 4 ) == '</p>' )
        $content = substr( $content, 4 );
    /* Remove '<p>' from the end of the string. */
    if ( substr( $content, -3, 3 ) == '<p>' )
        $content = substr( $content, 0, -3 );
    /* Remove any instances of '<p></p>'. */
    $content = str_replace( array( '<p></p>' ), '', $content );
    return $content;
}


/**
 * Return a phrase shortened in length to a maximum number of characters.
 */
function wte_truncate_phrase( $text, $max_characters ) {

    $text = trim( $text );

    if ( mb_strlen( $text ) > $max_characters ) {
        // Truncate $text to $max_characters + 1
        $text = mb_substr( $text, 0, $max_characters + 1 );

        // Truncate to the last space in the truncated string
        $text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
    }

    return $text;
}


/**
 * Return content stripped down and limited content.
 */
function get_wte_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

    $content = get_the_content( '', $stripteaser );

    // Strip tags and shortcodes so the content truncation count is done correctly
    $content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_wte_content_limit_allowedtags', '<script>,<style>' ) );

    // Remove inline styles / scripts
    $content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

    // Truncate $content to $max_char
    $content = wte_truncate_phrase( $content, $max_characters );

    // More link?
    if ( $more_link_text ) {
        $link   = apply_filters( 'get_the_content_more_link', sprintf( '&#x02026; <a href="%s" class="more-link">%s</a>', get_permalink(), $more_link_text ), $more_link_text );
        $output = sprintf( '<p>%s %s</p>', $content, $link );
    } else {
        $output = sprintf( '<p>%s</p>', $content );
        $link = '';
    }

    return apply_filters( 'get_wte_content_limit', $output, $content, $link, $max_characters );

}


/**
 * Echo the limited content.
 */
function wte_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {
    $content = get_wte_content_limit( $max_characters, $more_link_text, $stripteaser );
    echo apply_filters( 'wte_content_limit', $content );
}


/**
 * Sanitize multiple HTML classes in one pass.
 */
function wte_sanitize_html_classes( $classes, $return_format = 'input' ) {

    if ( 'input' === $return_format ) {
        $return_format = is_array( $classes ) ? 'array' : 'string';
    }

    $classes = is_array( $classes ) ? $classes : explode( ' ', $classes );

    $sanitized_classes = array_map( 'sanitize_html_class', $classes );

    if ( 'array' === $return_format )
        return $sanitized_classes;
    else
        return implode( ' ', $sanitized_classes );

}


/**
 * Return an array of allowed tags for output formatting.
 */
function wte_formatting_allowedtags() {

    return apply_filters(
        'wte_formatting_allowedtags',
        array(
            'a'          => array( 'href' => array(), 'title' => array(), ),
            'b'          => array(),
            'blockquote' => array(),
            'br'         => array(),
            'div'        => array( 'align' => array(), 'class' => array(), 'style' => array(), ),
            'em'         => array(),
            'i'          => array(),
            'p'          => array( 'align' => array(), 'class' => array(), 'style' => array(), ),
            'span'       => array( 'align' => array(), 'class' => array(), 'style' => array(), ),
            'strong'     => array(),

            // <img src="" class="" alt="" title="" width="" height="" />
            //'img'        => array( 'src' => array(), 'class' => array(), 'alt' => array(), 'width' => array(), 'height' => array(), 'style' => array() ),
        )
    );

}


/**
 * Wrapper for `wp_kses()` that can be used as a filter function.
 */
function wte_formatting_kses( $string ) {
    return wp_kses( $string, wte_formatting_allowedtags() );
}


/**
 * Calculate the time difference - a replacement for `human_time_diff()` until it is improved.
 */
function wte_human_time_diff( $older_date, $newer_date = false ) {

    // If no newer date is given, assume now
    $newer_date = $newer_date ? $newer_date : time();

    // Difference in seconds
    $since = absint( $newer_date - $older_date );

    if ( ! $since )
        return '0 ' . _x( 'seconds', 'time difference', 'wte' );

    // Hold units of time in seconds, and their pluralised strings (not translated yet)
    $units = array(
        array( 31536000, _nx_noop( '%s year', '%s years', 'time difference', 'wte' ) ),  // 60 * 60 * 24 * 365
        array( 2592000, _nx_noop( '%s month', '%s months', 'time difference', 'wte' ) ), // 60 * 60 * 24 * 30
        array( 604800, _nx_noop( '%s week', '%s weeks', 'time difference', 'wte' ) ),    // 60 * 60 * 24 * 7
        array( 86400, _nx_noop( '%s day', '%s days', 'time difference', 'wte' ) ),       // 60 * 60 * 24
        array( 3600, _nx_noop( '%s hour', '%s hours', 'time difference', 'wte' ) ),      // 60 * 60
        array( 60, _nx_noop( '%s minute', '%s minutes', 'time difference', 'wte' ) ),
        array( 1, _nx_noop( '%s second', '%s seconds', 'time difference', 'wte' ) ),
    );

    // Step one: the first unit
    for ( $i = 0, $j = count( $units ); $i < $j; $i++ ) {
        $seconds = $units[$i][0];

        // Finding the biggest chunk (if the chunk fits, break)
        if ( ( $count = floor( $since / $seconds ) ) != 0 )
            break;
    }

    // Translate unit string, and add to the output
    $output = sprintf( translate_nooped_plural( $units[$i][1], $count, 'wte' ), $count );

    // Note the next unit
    $ii = $i + 1;

    // Step two: the second unit
    if ( $ii < $j ) {
        $seconds2 = $units[$ii][0];

        // Check if this second unit has a value > 0
        if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) !== 0 )
            // Add translated separator string, and translated unit string
            $output .= sprintf( ' %s ' . translate_nooped_plural( $units[$ii][1], $count2, 'wte' ), _x( 'and', 'separator in time difference', 'wte' ), $count2 );
    }

    return $output;

}


/**
 * Mark up content with code tags.
 */
function wte_code( $content ) {
    return '<code>' . esc_html( $content ) . '</code>';
}
