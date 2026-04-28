<?php
/**
 * Enqueue scripts and styles.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'dogo_enqueue_assets' );
function dogo_enqueue_assets() {
	// Three typographic voices, mapped to free Google equivalents:
	//   CursorGothic (display/UI)   -> Inter (geometric sans)
	//   jjannon (editorial body)    -> Newsreader (warm serif)
	//   berkeleyMono (code/labels)  -> JetBrains Mono
	wp_enqueue_style(
		'dogo-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=JetBrains+Mono:wght@400;500&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'dogo-main',
		DOGO_THEME_URI . '/assets/css/main.css',
		array( 'dogo-fonts' ),
		DOGO_THEME_VERSION
	);

	wp_enqueue_script(
		'dogo-main',
		DOGO_THEME_URI . '/assets/js/main.js',
		array(),
		DOGO_THEME_VERSION,
		true
	);

	wp_localize_script( 'dogo-main', 'DogoData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'dogo_nonce' ),
		'home'    => home_url( '/' ),
	) );
}

add_action( 'wp_head', 'dogo_preconnect', 1 );
function dogo_preconnect() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	echo '<meta name="theme-color" content="#0f0e0b">' . "\n";
}

/**
 * Use the brand logomark as the favicon. WP only auto-renders a favicon
 * when Site Icon is set in customizer; we wire it ourselves so the brand
 * shows up out of the box.
 */
add_action( 'wp_head', 'dogo_favicon', 2 );
function dogo_favicon() {
	if ( has_site_icon() ) {
		return; // respect admin-set site icon
	}
	$dark  = DOGO_THEME_URI . '/assets/images/logo-dark.png';
	$light = DOGO_THEME_URI . '/assets/images/logo-light.png';
	echo '<link rel="icon" type="image/png" href="' . esc_url( $dark ) . '">' . "\n";
	echo '<link rel="icon" type="image/png" href="' . esc_url( $light ) . '" media="(prefers-color-scheme: dark)">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $dark ) . '">' . "\n";
	echo '<link rel="shortcut icon" href="' . esc_url( $dark ) . '">' . "\n";
}
