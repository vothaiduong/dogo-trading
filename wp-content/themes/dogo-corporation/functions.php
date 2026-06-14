<?php
/**
 * Dogo Corporation theme bootstrap.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DOGO_THEME_VERSION', '2.4.0' );
define( 'DOGO_THEME_DIR', get_template_directory() );
define( 'DOGO_THEME_URI', get_template_directory_uri() );

require_once DOGO_THEME_DIR . '/inc/theme-setup.php';
require_once DOGO_THEME_DIR . '/inc/enqueue.php';
require_once DOGO_THEME_DIR . '/inc/template-tags.php';
require_once DOGO_THEME_DIR . '/inc/i18n.php';
require_once DOGO_THEME_DIR . '/inc/post-types.php';
require_once DOGO_THEME_DIR . '/inc/contact.php';
require_once DOGO_THEME_DIR . '/inc/seo.php';
