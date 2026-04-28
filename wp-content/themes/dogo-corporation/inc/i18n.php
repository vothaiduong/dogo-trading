<?php
/**
 * Lightweight UI-language switcher.
 *
 * Default site locale = ja (set via wp option WPLANG / Settings → General → Site Language).
 * Visitors switch UI strings via the header toggle. Choice persists via cookie.
 *
 * This is intentionally NOT a full multilingual content system — it only swaps the
 * theme's UI strings (loaded from /languages/*.mo). For per-page translations you
 * can later activate Polylang (already installed) and assign translations in admin.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const DOGO_LANG_COOKIE = 'dogo_lang';
const DOGO_LANG_TTL    = 31536000; // 1 year

/**
 * Available languages metadata.
 */
function dogo_languages() {
	return array(
		'ja' => array( 'label' => '日本語',     'short' => 'JA', 'locale' => 'ja' ),
		'en' => array( 'label' => 'English',    'short' => 'EN', 'locale' => 'en_US' ),
		'vi' => array( 'label' => 'Tiếng Việt', 'short' => 'VI', 'locale' => 'vi' ),
	);
}

/**
 * Resolve the current language code (ja|en|vi).
 * Priority: ?lang= → cookie → site default (ja).
 */
function dogo_current_lang() {
	$map = dogo_languages();
	if ( isset( $_GET['lang'] ) ) {
		$lang = sanitize_key( wp_unslash( $_GET['lang'] ) );
		if ( isset( $map[ $lang ] ) ) {
			return $lang;
		}
	}
	if ( isset( $_COOKIE[ DOGO_LANG_COOKIE ] ) ) {
		$lang = sanitize_key( wp_unslash( $_COOKIE[ DOGO_LANG_COOKIE ] ) );
		if ( isset( $map[ $lang ] ) ) {
			return $lang;
		}
	}
	return 'ja';
}

/**
 * Override WP locale based on visitor preference.
 * Polylang's own locale filter no longer competes — Polylang is deactivated by default.
 */
add_filter( 'locale', 'dogo_lang_filter', 5 );
function dogo_lang_filter( $locale ) {
	$lang = dogo_current_lang();
	$map  = dogo_languages();
	return $map[ $lang ]['locale'] ?? $locale;
}

/**
 * If the visitor explicitly chose a language via ?lang=, save it to a cookie
 * and strip the param so the URL stays clean on the next click.
 */
add_action( 'init', 'dogo_persist_lang_cookie' );
function dogo_persist_lang_cookie() {
	if ( ! isset( $_GET['lang'] ) || headers_sent() ) {
		return;
	}
	$lang = sanitize_key( wp_unslash( $_GET['lang'] ) );
	$map  = dogo_languages();
	if ( ! isset( $map[ $lang ] ) ) {
		return;
	}
	setcookie(
		DOGO_LANG_COOKIE,
		$lang,
		array(
			'expires'  => time() + DOGO_LANG_TTL,
			'path'     => '/',
			'samesite' => 'Lax',
			'secure'   => is_ssl(),
			'httponly' => false, // readable by JS so client-side toggles can stay in sync
		)
	);
	// Mirror to PHP cookie array so this same request also picks up the new lang.
	$_COOKIE[ DOGO_LANG_COOKIE ] = $lang;

	// Clean URL: redirect once to the same page without ?lang= so it doesn't pin to URL.
	$clean = remove_query_arg( 'lang' );
	if ( $clean !== ( $_SERVER['REQUEST_URI'] ?? '' ) ) {
		wp_safe_redirect( $clean, 302 );
		exit;
	}
}

/**
 * Render the language switcher (header / footer).
 */
function dogo_language_switcher() {
	$current = dogo_current_lang();
	$base    = remove_query_arg( 'lang', $_SERVER['REQUEST_URI'] ?? '/' );
	echo '<div class="lang-switcher" role="group" aria-label="' . esc_attr__( 'Language', 'dogo-corporation' ) . '">';
	foreach ( dogo_languages() as $code => $meta ) {
		$active = ( $current === $code );
		$url    = add_query_arg( 'lang', $code, $base );
		printf(
			'<a class="lang-switcher__item%1$s" href="%2$s" hreflang="%3$s" title="%4$s"%5$s>%6$s</a>',
			$active ? ' is-active' : '',
			esc_url( $url ),
			esc_attr( $code ),
			esc_attr( $meta['label'] ),
			$active ? ' aria-current="true"' : '',
			esc_html( $meta['short'] )
		);
	}
	echo '</div>';
}
