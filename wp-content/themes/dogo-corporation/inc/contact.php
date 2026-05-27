<?php
/**
 * Contact form handler.
 *
 * Routes through wp_mail() — when WP Mail SMTP plugin is configured with
 * Mailgun, every wp_mail() call is delivered via Mailgun's API.
 *
 * Endpoints:
 *   POST /wp-admin/admin-post.php?action=dogo_contact   (no-JS fallback, redirects)
 *   POST /wp-admin/admin-ajax.php?action=dogo_contact   (AJAX, returns JSON)
 *
 * Filterable settings:
 *   add_filter('dogo_contact_to', fn() => 'sales@example.com');
 *   add_filter('dogo_contact_from', fn() => 'noreply@example.com');
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

const DOGO_CONTACT_NONCE     = 'dogo_contact';
const DOGO_CONTACT_THROTTLE  = 30; // seconds between submissions per IP
const DOGO_TURNSTILE_VERIFY  = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

/**
 * Sitekey (public, safe to expose). Set DOGO_TURNSTILE_SITEKEY constant
 * in wp-config.php to override. Default = Cloudflare's "always pass" test key
 * so the integration works out of the box during dev.
 */
function dogo_turnstile_sitekey() {
	if ( defined( 'DOGO_TURNSTILE_SITEKEY' ) && DOGO_TURNSTILE_SITEKEY ) {
		return DOGO_TURNSTILE_SITEKEY;
	}
	return apply_filters( 'dogo_turnstile_sitekey', '1x00000000000000000000AA' );
}

/**
 * Secret (private, server-side only). Set DOGO_TURNSTILE_SECRET constant.
 */
function dogo_turnstile_secret() {
	if ( defined( 'DOGO_TURNSTILE_SECRET' ) && DOGO_TURNSTILE_SECRET ) {
		return DOGO_TURNSTILE_SECRET;
	}
	return apply_filters( 'dogo_turnstile_secret', '1x0000000000000000000000000000000AA' );
}

/**
 * Verify a Turnstile token against Cloudflare. Returns true on pass.
 * If no secret is configured, fails closed (rejects).
 */
function dogo_turnstile_verify( $token, $remote_ip = null ) {
	$secret = dogo_turnstile_secret();
	if ( ! $secret || ! $token ) {
		return false;
	}
	$res = wp_remote_post( DOGO_TURNSTILE_VERIFY, array(
		'timeout' => 8,
		'body'    => array(
			'secret'   => $secret,
			'response' => $token,
			'remoteip' => $remote_ip,
		),
	) );
	if ( is_wp_error( $res ) ) {
		error_log( '[dogo_contact] turnstile verify wp_error: ' . $res->get_error_message() );
		return false;
	}
	$body = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $body['success'] ) ) {
		error_log( '[dogo_contact] turnstile failed: ' . wp_json_encode( $body['error-codes'] ?? array() ) );
		return false;
	}
	return true;
}

add_action( 'admin_post_dogo_contact',        'dogo_contact_handle' );
add_action( 'admin_post_nopriv_dogo_contact', 'dogo_contact_handle' );
add_action( 'wp_ajax_dogo_contact',           'dogo_contact_handle' );
add_action( 'wp_ajax_nopriv_dogo_contact',    'dogo_contact_handle' );

/**
 * Read + validate POST. Returns array with status + message.
 */
function dogo_contact_handle() {
	$is_ajax = wp_doing_ajax();

	$respond = function ( $ok, $message, $code = 200 ) use ( $is_ajax ) {
		if ( $is_ajax ) {
			wp_send_json( array( 'ok' => $ok, 'message' => $message ), $code );
		}
		// Non-JS fallback: redirect back to the contact section with a query flag
		$ref  = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		$flag = $ok ? 'sent' : 'error';
		wp_safe_redirect( add_query_arg( 'contact', $flag, untrailingslashit( $ref ) ) . '#contact' );
		exit;
	};

	// 1. Nonce
	if ( ! isset( $_POST['_dogo_nonce'] ) || ! wp_verify_nonce( $_POST['_dogo_nonce'], DOGO_CONTACT_NONCE ) ) {
		$respond( false, __( 'Security check failed. Please reload and try again.', 'dogo-corporation' ), 403 );
	}

	// 2. Honeypot — bots fill the hidden "website" field. Silently accept so they don't retry.
	if ( ! empty( $_POST['website'] ) ) {
		$respond( true, __( 'Thank you. We\'ll be in touch shortly.', 'dogo-corporation' ) );
	}

	// 3. Rate-limit per IP (transient, 30s window)
	$ip   = dogo_contact_client_ip();
	$key  = 'dogo_contact_throttle_' . md5( $ip );
	if ( get_transient( $key ) ) {
		$respond( false, __( 'You\'ve just sent a message — please wait a moment before sending another.', 'dogo-corporation' ), 429 );
	}

	// 3b. Cloudflare Turnstile (CAPTCHA)
	$token = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : '';
	if ( ! dogo_turnstile_verify( $token, $ip ) ) {
		$respond( false, __( 'Captcha check failed. Please reload the page and try again.', 'dogo-corporation' ), 403 );
	}

	// 4. Sanitize + validate
	$name    = isset( $_POST['name'] )    ? sanitize_text_field( wp_unslash( $_POST['name'] ) )    : '';
	$company = isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '';
	$email   = isset( $_POST['email'] )   ? sanitize_email( wp_unslash( $_POST['email'] ) )        : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( $name === '' || mb_strlen( $name ) > 200 ) {
		$respond( false, __( 'Please provide your name.', 'dogo-corporation' ), 422 );
	}
	if ( ! is_email( $email ) ) {
		$respond( false, __( 'Please provide a valid email address.', 'dogo-corporation' ), 422 );
	}
	if ( $message === '' || mb_strlen( $message ) < 10 ) {
		$respond( false, __( 'Please tell us a bit more in your message (at least 10 characters).', 'dogo-corporation' ), 422 );
	}
	if ( mb_strlen( $message ) > 8000 ) {
		$respond( false, __( 'Your message is too long. Please trim it under 8,000 characters.', 'dogo-corporation' ), 422 );
	}

	// 5. Compose + send
	$to      = apply_filters( 'dogo_contact_to', 'info@dogo-trading.com' );
	$from    = apply_filters( 'dogo_contact_from', 'noreply@' . wp_parse_url( home_url(), PHP_URL_HOST ) );
	$subject = sprintf( '[Dogo Contact] %s', $name );

	$lang_code = function_exists( 'dogo_current_lang' ) ? dogo_current_lang() : 'en';
	$body  = "New contact form submission on " . home_url() . "\n";
	$body .= str_repeat( '-', 60 ) . "\n";
	$body .= "Name     : {$name}\n";
	$body .= "Email    : {$email}\n";
	if ( $company !== '' ) {
		$body .= "Company  : {$company}\n";
	}
	$body .= "Language : {$lang_code}\n";
	$body .= "IP       : {$ip}\n";
	$body .= "Submitted: " . current_time( 'Y-m-d H:i:s' ) . " (" . wp_timezone_string() . ")\n";
	$body .= str_repeat( '-', 60 ) . "\n\n";
	$body .= "Message:\n\n{$message}\n";

	$headers = array(
		'From: Dogo Corporation <' . $from . '>',
		'Reply-To: ' . $name . ' <' . $email . '>',
		'Content-Type: text/plain; charset=UTF-8',
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		// Capture last PHPMailer error if available
		global $phpmailer;
		$err = $phpmailer && $phpmailer->ErrorInfo ? $phpmailer->ErrorInfo : 'wp_mail returned false';
		error_log( '[dogo_contact] send failed: ' . $err );
		$respond( false, __( 'Sorry, the message could not be sent right now. Please email us directly at info@dogo-trading.com.', 'dogo-corporation' ), 502 );
	}

	// 6. Throttle, log, respond
	set_transient( $key, 1, DOGO_CONTACT_THROTTLE );
	do_action( 'dogo_contact_sent', compact( 'name', 'company', 'email', 'message', 'ip', 'lang_code' ) );

	$respond( true, __( 'Thank you. We\'ll be in touch within one business day.', 'dogo-corporation' ) );
}

/**
 * Best-effort client IP (handles common proxy headers but always sanitizes).
 */
function dogo_contact_client_ip() {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ) as $key ) {
		if ( ! empty( $_SERVER[ $key ] ) ) {
			$candidate = explode( ',', $_SERVER[ $key ] )[0];
			$candidate = trim( $candidate );
			if ( filter_var( $candidate, FILTER_VALIDATE_IP ) ) {
				return $candidate;
			}
		}
	}
	return '0.0.0.0';
}

/**
 * Localize the JS script with endpoint + nonce.
 */
add_action( 'wp_enqueue_scripts', 'dogo_contact_localize', 20 );
function dogo_contact_localize() {
	wp_localize_script( 'dogo-main', 'DogoContact', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( DOGO_CONTACT_NONCE ),
		'i18n'    => array(
			'sending' => __( 'Sending…', 'dogo-corporation' ),
			'submit'  => __( 'Send message', 'dogo-corporation' ),
		),
	) );
}
