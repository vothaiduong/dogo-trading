<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'dogo_setup' );
function dogo_setup() {
	load_theme_textdomain( 'dogo-corporation', DOGO_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'dogo-corporation' ),
		'footer'  => __( 'Footer Menu', 'dogo-corporation' ),
		'social'  => __( 'Social Links', 'dogo-corporation' ),
	) );

	add_image_size( 'dogo-hero',    1920, 960, true );
	add_image_size( 'dogo-service', 1024, 766, true );
	add_image_size( 'dogo-card',    640,  480, true );
}

add_action( 'widgets_init', 'dogo_widgets' );
function dogo_widgets() {
	register_sidebar( array(
		'name'          => __( 'Footer Widgets', 'dogo-corporation' ),
		'id'            => 'footer-widgets',
		'description'   => __( 'Widgets shown in the footer columns.', 'dogo-corporation' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget__title">',
		'after_title'   => '</h4>',
	) );
}

add_filter( 'body_class', 'dogo_body_classes' );
function dogo_body_classes( $classes ) {
	$classes[] = 'dogo-theme';
	if ( is_front_page() ) {
		$classes[] = 'has-hero';
	}
	return $classes;
}

/**
 * Disable WordPress emoji scripts for cleaner front-end.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/**
 * Make sure the Company and Privacy pages exist so /company/ and /privacy/
 * resolve to their dedicated templates. Runs once per theme version, so it's
 * cheap on every request and self-heals if pages get deleted.
 */
add_action( 'init', 'dogo_ensure_static_pages' );
function dogo_ensure_static_pages() {
	$marker = 'dogo_static_pages_' . DOGO_THEME_VERSION;
	if ( get_option( $marker ) ) {
		return;
	}
	$pages = array(
		'company' => __( 'Company profile', 'dogo-corporation' ),
		'privacy' => __( 'Privacy Policy', 'dogo-corporation' ),
		'life'    => __( 'Life at Dogo', 'dogo-corporation' ),
	);
	foreach ( $pages as $slug => $title ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		wp_insert_post( array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '', // template renders the content
		) );
	}
	update_option( $marker, time(), false );
}
