<?php
/**
 * Plugin Name: Dogo Hardening
 * Description: Security hardening for Dogo Corporation — disables XML-RPC, blocks user enumeration, hides WP version, restricts REST user endpoints, throttles failed logins.
 * Author: Dogo Corporation
 * Version: 1.0.0
 *
 * Place in wp-content/mu-plugins/ — loads automatically, cannot be deactivated from the dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------------------------------------------------------------------------
 * 1. Kill XML-RPC (a top brute-force / pingback-amplification vector)
 * ------------------------------------------------------------------------- */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );
add_filter( 'pings_open', '__return_false' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
add_filter( 'wp_headers', function ( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
} );

/* ---------------------------------------------------------------------------
 * 2. Hide WordPress version (don't advertise exploitable version)
 * ------------------------------------------------------------------------- */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/* ---------------------------------------------------------------------------
 * 3. Block user enumeration via ?author=N and /author/<name>/
 *    Runs very early (init, priority 1) — BEFORE WordPress' canonical redirect
 *    would otherwise rewrite ?author=1 to /author/admin/ and leak the username.
 * ------------------------------------------------------------------------- */
add_action( 'init', function () {
	if ( is_admin() || is_user_logged_in() ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	if ( preg_match( '#[?&]author=\d#i', $uri ) || preg_match( '#/author/#i', $uri ) ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}, 1 );

// Belt-and-suspenders: strip the author query var if it slips through.
add_filter( 'request', function ( $vars ) {
	if ( ! is_user_logged_in() && isset( $vars['author'] ) ) {
		unset( $vars['author'] );
	}
	return $vars;
} );

/* ---------------------------------------------------------------------------
 * 4. Restrict REST API user endpoints (stops /wp-json/wp/v2/users enumeration)
 * ------------------------------------------------------------------------- */
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints; // logged-in editors still need it
	}
	foreach ( array( '/wp/v2/users', '/wp/v2/users/(?P<id>[\d]+)' ) as $route ) {
		if ( isset( $endpoints[ $route ] ) ) {
			unset( $endpoints[ $route ] );
		}
	}
	return $endpoints;
} );

/* ---------------------------------------------------------------------------
 * 5. Generic login error (don't reveal whether username or password was wrong)
 * ------------------------------------------------------------------------- */
add_filter( 'login_errors', function () {
	return 'Login failed. Please check your credentials and try again.';
} );

/* ---------------------------------------------------------------------------
 * 6. Throttle failed logins per IP (lenient: 8 fails → 15 min lockout)
 * ------------------------------------------------------------------------- */
add_action( 'wp_login_failed', function () {
	$ip  = dogo_hardening_ip();
	$key = 'dogo_login_fail_' . md5( $ip );
	$n   = (int) get_transient( $key );
	set_transient( $key, $n + 1, 15 * MINUTE_IN_SECONDS );
} );
add_filter( 'authenticate', function ( $user ) {
	$ip  = dogo_hardening_ip();
	$key = 'dogo_login_fail_' . md5( $ip );
	if ( (int) get_transient( $key ) >= 8 ) {
		return new WP_Error( 'too_many_attempts', 'Too many failed attempts. Please try again in 15 minutes.' );
	}
	return $user;
}, 30 );
add_action( 'wp_login', function () {
	delete_transient( 'dogo_login_fail_' . md5( dogo_hardening_ip() ) );
} );

function dogo_hardening_ip() {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ) as $k ) {
		if ( ! empty( $_SERVER[ $k ] ) ) {
			$ip = trim( explode( ',', $_SERVER[ $k ] )[0] );
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}
	}
	return '0.0.0.0';
}

/* ---------------------------------------------------------------------------
 * 7. Misc: disable file editor, remove login page language switcher noise
 * ------------------------------------------------------------------------- */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
