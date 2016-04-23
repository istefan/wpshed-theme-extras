<?php
/**
 * Custom Shortcodes.
 *
 * @package WPshed Theme Extras
 * @author  Stefan I.
 * @license GPL-2.0+
 * @link    https://wpshed.com/
 */


/**
 * Grid shortcode.
 */
function wte_grid_shortcode( $atts, $content = null ) {

    extract( shortcode_atts( array( 'size' => '' ), $atts ) );
    
    $output = sprintf( '<div class="%s">%s</div>', 
        $size,
        wte_parse_content( $content )
    );
	
    return $output;
}
add_shortcode( 'grid', 'wte_grid_shortcode' );


/**
 * Content Box shortcode.
 */
function wte_box_shortcode( $atts, $content = null ) {

    extract( shortcode_atts( array( 'color' => 'blue' ), $atts ) );

    $output = sprintf( '<div class="content-box cb-%s">%s</div>', 
        trim( $color ),
        wte_parse_content( $content )
    );

	return $output;
}
add_shortcode( 'box', 'wte_box_shortcode' );


/**
 * Button shortcode.
 */
function wte_button_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'target'    => '_self',
        'url'       => '#',
        'text'      => 'Click Here',
        'type'      => 'default',
        'icon'      => '',
    ), $atts ) );

    $icon = trim( str_replace( 'genericon-', '', $icon ) );

    $before = ( $icon != '' ) ? '<span class="inline-icon genericon genericon-'. $icon .'"></span> ' : '';
    
    $output = sprintf( '<a href="%s" class="wte-button %s" target="%s">%s%s</a>',
        esc_url( $url ),
        trim( $type ),
        trim( $target ),
        $before,
        esc_attr( $text )
    );

    return $output;
}
add_shortcode( 'button', 'wte_button_shortcode' );


/**
 * Icon shortcode.
 */
function wte_icon_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'icon'      => 'wordpress',
    ), $atts ) );

    $icon = trim( str_replace( 'genericon-', '', $icon ) );
    
    $output = sprintf( '<span class="inline-icon genericon genericon-%s"></span>',
        $icon
    );

    return $output;
}
add_shortcode( 'icon', 'wte_icon_shortcode' );


/**
 * Divider shortcode.
 */
function wte_divider_shortcode( $atts, $content = null ) {
    $output = '<div class="divider"><div class="divider-inside"></div></div>';
    return $output;
}
add_shortcode( 'divider', 'wte_divider_shortcode' );


/**
 * Featured Page shortcode.
 */
function wte_featured_page_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'page_id'         => '',
        'show_image'      => 0,
        'image_alignment' => '',
        'image_size'      => '',
        'show_title'      => 0,
        'show_content'    => 0,
        'content_limit'   => '',
        'more_text'       => '',
    ), $atts ) );
    
    $query = new WP_Query( array( 'page_id' => (int)$page_id ) );

    $output = '';

    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

        $output .= sprintf( '<article class="post-%s">', get_the_ID() );

        $image = wte_get_image( array(
            'format'  => 'html',
            'size'    => $image_size,
            'context' => 'featured-page-widget',
            'attr'    => 'entry-image-widget',
        ) );

        if ( $show_image && $image ) {
            $role = empty( $show_title ) ? '' : 'aria-hidden="true"';
            $output .= sprintf( '<a href="%s" class="%s" %s>%s</a>',
                get_permalink(),
                esc_attr( $image_alignment ),
                $role,
                $image
            );
        }

        if ( ! empty( $show_title ) ) {

            $title = get_the_title() ? get_the_title() : __( '(no title)', 'wte' );
            $heading = 'h4';

            $output .= sprintf( '<header class="featured-page-header"><%s class="featured-page-title"><a href="%s">%s</a></%s></header>', 
                $heading, 
                get_permalink(), 
                $title, 
                $heading 
            );

        }

        if ( ! empty( $show_content ) ) {

            $output .= '<div class="featured-page-content">';

            if ( empty( $content_limit ) ) {

                global $more;

                $orig_more = $more;
                $more = 0;

                $output .= wpautop( apply_filters( 'widget_text', get_the_content( esc_html( $more_text ) ) ) );

                $more = $orig_more;

            } else {
                $output .= get_wte_content_limit( (int) $content_limit, esc_html( $more_text ) );
            }
            $output .= '</div>' ;

        }

        $output .= '</article>';

    endwhile; endif;

    // Restore original query
    wp_reset_query();

    return $output;
}
add_shortcode( 'featured_page', 'wte_featured_page_shortcode' );


/**
 * Featured Posts shortcode.
 */
function wte_featured_posts_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'title'                 => '',
        'cat'                   => '',
        'posts'                 => 1,
        'offset'                => 0,
        'orderby'               => '',
        'order'                 => '',
        'exclude_sticky'        => 1,
        'show_image'            => 0,
        'image_alignment'       => '',
        'image_size'            => '',
        'show_title'            => 1,
        'show_content'          => 'excerpt',
        'content_limit'         => '',
        'more_text'             => __( 'Read More...', 'wte' ),
    ), $atts ) );
    
    $query_args = array(
        'post_type'           => 'post',
        'cat'                 => $cat,
        'showposts'           => $posts,
        'offset'              => $offset,
        'orderby'             => $orderby,
        'order'               => $order,
        'ignore_sticky_posts' => $exclude_sticky,
    );

    $query = new WP_Query( $query_args );

    $output = '';

    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();

        $output .= sprintf( '<article class="featured-post post-%s">', get_the_ID() );

        $image = wte_get_image( array(
            'format'  => 'html',
            'size'    => $image_size,
            'context' => 'featured-post-wte-widget',
            'attr'    => 'entry-image-widget',
        ) );

        if ( $show_image && $image ) {
            $role = empty( $show_title ) ? '' : 'aria-hidden="true"';
            $output .= sprintf( '<a href="%s" class="%s" %s>%s</a>',
                get_permalink(),
                esc_attr( $image_alignment ),
                $role,
                $image
            );
        }

        if ( $show_title )
            $output .= '<header class="featured-post-wte-header">';

            if ( ! empty( $show_title ) ) {

                $title = get_the_title() ? get_the_title() : __( '(no title)', 'wte' );
                $heading = 'h4';

                $output .= sprintf( '<%s class="featured-post-wte-title"><a href="%s">%s</a></%s>',
                    $heading,
                    get_permalink(),
                    $title,
                    $heading
                );

            }

        if ( $show_title )
            $output .= '</header>';

        if ( ! empty( $show_content ) ) {

            $output .= '<div class="featured-post-wte-content">';

            if ( 'excerpt' == $show_content ) {
                $output .= get_the_excerpt();
            }
            elseif ( 'content-limit' == $show_content ) {
                $output .= get_wte_content_limit( (int) $content_limit, esc_html( $more_text ) );
            }
            else {
                $output .= wpautop( apply_filters( 'widget_text', get_the_content( esc_html( $more_text ) ) ) );
            }

            $output .= '</div>';

        }

        $output .= '</article>';

    endwhile; endif;

    // Restore original query
    wp_reset_query();

    return $output;
}
add_shortcode( 'featured_posts', 'wte_featured_posts_shortcode' );


/**
 * Slider shortcode.
 */
function wte_slider_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'cat'                   => '',
        'posts_cat'             => '',
        'posts'                 => 3,
        'orderby'               => '',
        'order'                 => '',
        'show_posts'            => '',
    ), $atts ) );

    $query_args = array(
        'showposts'             => $posts,
        'orderby'               => $orderby,
        'order'                 => $order,
        'ignore_sticky_posts'   => 1,
    );

    if ( $show_posts == ''  )
        $query_args['post_type'] = 'slides';

    if ( $cat != '' && $cat != '0' && $show_posts == '' ) {

        if ( is_string( $cat ) ) {
            $category = sanitize_title( $cat );
        }

        if ( is_numeric( $cat ) ) {
            $slides_category = get_term_by( 'id', (int) $cat, 'wte_slides_category' );
            $category = $slides_category->slug;     
        }

        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'wte_slides_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );

    }

    if ( $show_posts != '' && $posts_cat != '' && $posts_cat != 0  )
        $query_args['cat'] = (int) $posts_cat;

    $query = new WP_Query( $query_args );
    
    $output = '';

    if( $query->have_posts() ) :
              
    $output .= '<div class="responsive-slider flexslider"><ul class="slides">';     

    while( $query->have_posts() ) : $query->the_post();

    $output .= sprintf( '<li><div id="slide-%s" class="slide">', get_the_ID() );

    if ( $show_posts != '' ) {
        $url        = get_permalink();
        $caption    = get_the_title();
    } else {
        $url        = get_post_meta( get_the_ID(), 'slide_link_url', true );
        $caption    = get_post_meta( get_the_ID(), 'slide_caption', true );
    }

        if ( has_post_thumbnail() ) :

            if ( $url )
                $output .= sprintf( '<a href="%s" title="%s">', $url, the_title_attribute ( array( 'echo' => 0 ) ) );

                $output .= get_the_post_thumbnail( get_the_ID(), 'slider-thumbnail', array( 'class' => 'slider-thumbnail' ) );

            if ( $url )
                $output .= '</a>';

            if ( $caption )
                $output .= sprintf( '<h3 class="flex-caption">%s</h3>', esc_attr( $caption ) );

        endif;

    $output .= '</div></li>';

    endwhile;

    $output .= '</ul></div>';

    endif;

    // Restore original query
    wp_reset_query();

    return $output;
}
add_shortcode( 'slider', 'wte_slider_shortcode' );


/**
 * Testimonial shortcode.
 */
function wte_testimonial_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'cat'                   => 0,
        'posts'                 => 1,
        'orderby'               => '',
        'order'                 => '',
        'color'                 => 'default',
    ), $atts ) );

    $query_args = array(
        'post_type'             => 'testimonials',
        'showposts'             => $posts,
        'orderby'               => $orderby,
        'order'                 => $order,
        'ignore_sticky_posts'   => 1,
    );

    if ( $cat != 0 ) {

        $slides_category = get_term_by( 'id', (int) $cat, 'wte_testimonials_category' );
        $category = $slides_category->slug;

        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'wte_testimonials_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );

    }

    $query = new WP_Query( $query_args );

    $output = '';

    if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();

    $testimonial_text       = get_post_meta( get_the_ID(), 'testimonial_text', true );
    $testimonial_author     = get_post_meta( get_the_ID(), 'testimonial_author', true );
    $testimonial_link_url   = get_post_meta( get_the_ID(), 'testimonial_link_url', true );

    $author = '';

    if ( $testimonial_link_url && $testimonial_author ) {
        $author = sprintf( ' <strong> - <a href="%s" target="_blank" rel="nofollow">%s</a></strong>', 
            esc_url( $testimonial_link_url ),
            esc_attr( $testimonial_author )
        );
    }

    if ( $testimonial_author && ! $testimonial_author  ) {
        $author = sprintf( ' <strong> - %s</strong>', 
            esc_attr( $testimonial_author )
        );
    }

    $testimonial = $testimonial_text . $author;

    $output .= sprintf( '<div class="testimonial q-%s">%s</div>', 
        $color,
        wte_parse_content( $testimonial )
    );

    if ( has_post_thumbnail() )
        $output .= get_the_post_thumbnail( get_the_ID(), 'testimonial-thumbnail', array( 'class' => 'testimonial-thumbnail' ) );

    endwhile;

    endif;

    // Restore original query
    wp_reset_query();

    return $output;

}
add_shortcode( 'testimonial', 'wte_testimonial_shortcode' );


/**
 * Portfolio shortcode.
 */
function wte_portfolio_shortcode( $atts, $content = null ) {

    extract( shortcode_atts(array(
        'posts'                 => 4,
        'orderby'               => '',
        'order'                 => '',
        'post_type'             => 'portfolio',
        'cols'                  => 4,
        'show_title'            => '',
        'heading'               => 'h4',
    ), $atts ) );

    $page = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    $query_args = array(
        'post_type'           => $post_type,
        'showposts'           => $posts,
        'orderby'             => $orderby,
        'order'               => $order,
        'paged'               => $page,
    );



    switch ( $cols ) {
        case '1':
            $widget_class = '';
            break;
        case '2':
            $widget_class = 'one-half';
            break;
        case '3':
            $widget_class = 'one-third';
            break;
        case '4':
            $widget_class = 'one-fourth';
            break;
    }

    $query = new WP_Query( $query_args );
    
    $output = '';

    $post_counter = 0;
    if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post(); 

    $post_counter++;
    $post_counter = ( $post_counter > $cols ) ? 1 : $post_counter;

    // Set 'first' class if needed
    $first = ( $post_counter == 1 ) ? 'first' : '';

    $output .= sprintf( '<article class="portfolio-post post-%s %s %s">', get_the_ID(), $first, $widget_class );

        if ( has_post_thumbnail() ) :

            if ( $show_title )
                $output .= '<header class="portfolio-header">';

                if ( ! empty( $show_title ) ) {

                    $title = get_the_title() ? get_the_title() : __( '(no title)', 'wte' );

                    $output .= sprintf( '<%s class="portfolio-title"><a href="%s">%s</a></%s>',
                        $heading,
                        get_permalink(),
                        $title,
                        $heading
                    );

                }

            if ( $show_title )
                $output .= '</header>';

            $output .= sprintf( '<a href="%s">', get_permalink() );
            $output .= get_the_post_thumbnail( get_the_ID(), 'portfolio-thumbnail', array( 'class' => 'portfolio-thumbnail' ) );
            $output .= '</a>';

        endif;


    $output .= '</article>';

    endwhile; 

    // Previous / next page navigation.
    the_posts_pagination( array(
        'prev_text'          => __( 'Previous page', 'stef' ),
        'next_text'          => __( 'Next page', 'stef' ),
        'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'stef' ) . ' </span>',
    ) );

    endif;

    $output .= '<br style="clear:both;">';

    // Restore original query
    wp_reset_query();

    return $output;
}
add_shortcode( 'portfolio', 'wte_portfolio_shortcode' );

