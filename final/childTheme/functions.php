<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'theshop-bootstrap','theshop-wc-css' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

// END ENQUEUE PARENT ACTION

/**
 * Footer credits
 */
/*
function theshop_footer_credits() {
	echo '<a href="' . esc_url( __( 'http://wordpress.org/', 'theshop' ) ) . '">';
		printf( __( 'Proudly powered by %s', 'theshop' ), 'WordPress' );
	echo '</a>';
	echo '<span class="sep"> | </span>';
	printf( __( 'Theme: %2$s by %1$s.', 'theshop' ), 'aThemes', '<a href="http://athemes.com/theme/theshop" rel="nofollow">TheShop</a>' );
}
add_action( 'theshop_footer', 'theshop_footer_credits' );
*/