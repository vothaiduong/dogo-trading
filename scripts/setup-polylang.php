<?php
/**
 * One-shot Polylang setup for the dogo-corporation theme.
 * Idempotent — safe to re-run.
 *
 *   docker cp scripts/setup-polylang.php dogo-wp:/tmp/
 *   docker exec -u www-data dogo-wp wp --path=/var/www/html eval-file /tmp/setup-polylang.php
 *
 * Or, on a freshly-provisioned VPS:
 *   sudo -u www-data wp --path=/var/www/your-site eval-file /tmp/setup-polylang.php
 */

if ( ! defined( 'ABSPATH' ) ) { exit( 1 ); }
if ( ! function_exists( 'PLL' ) ) {
    fwrite( STDERR, "Polylang is not active. `wp plugin install polylang --activate` first.\n" );
    exit( 1 );
}

$model  = PLL()->model;
$to_add = [
    [ 'name' => '日本語',     'slug' => 'ja', 'locale' => 'ja',    'rtl' => 0, 'term_group' => 0,  'flag' => 'jp' ],
    [ 'name' => 'English',    'slug' => 'en', 'locale' => 'en_US', 'rtl' => 0, 'term_group' => 10, 'flag' => 'us' ],
    [ 'name' => 'Tiếng Việt', 'slug' => 'vi', 'locale' => 'vi',    'rtl' => 0, 'term_group' => 20, 'flag' => 'vn' ],
];
foreach ( $to_add as $lang ) {
    if ( $model->get_language( $lang['slug'] ) ) {
        echo "  exists: {$lang['slug']}\n";
        continue;
    }
    $r = $model->add_language( $lang );
    echo "  add {$lang['slug']}: " . ( is_wp_error( $r ) ? $r->get_error_message() : 'OK' ) . "\n";
}

$opts = get_option( 'polylang', [] );
$opts['default_lang']        = 'ja';
$opts['force_lang']          = $opts['force_lang']          ?? 1;
$opts['hide_default']        = $opts['hide_default']        ?? 1;
$opts['rewrite']             = $opts['rewrite']             ?? 1;
$opts['redirect_lang']       = $opts['redirect_lang']       ?? 0;
$opts['media_support']       = $opts['media_support']       ?? 0;
$opts['sync']                = $opts['sync']                ?? [];
$opts['post_types']          = $opts['post_types']          ?? [];
$opts['taxonomies']          = $opts['taxonomies']          ?? [];
update_option( 'polylang', $opts );
echo "default_lang -> ja\n";

echo "\nFinal state:\n";
foreach ( $model->get_languages_list() as $l ) {
    $is_default = ( $l->slug === $opts['default_lang'] ) ? '  (default)' : '';
    echo "  {$l->slug} · {$l->name} · {$l->locale}{$is_default}\n";
}
