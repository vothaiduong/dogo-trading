<?php
/**
 * Plain-text sitemap at /sitemap.txt (one URL per line — a format Google accepts).
 *
 * Dynamic: lists the homepage, every published page, the careers archive and
 * every published job posting — so new content appears without manual edits.
 * Also appends a Sitemap: line to the virtual robots.txt and keeps the private
 * /docs/ directory out of crawlers.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'dogo_sitemap_rewrite' );
function dogo_sitemap_rewrite() {
	add_rewrite_rule( '^sitemap\.txt$', 'index.php?dogo_sitemap=1', 'top' );
}

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'dogo_sitemap';
	return $vars;
} );

/**
 * Stop redirect_canonical from appending a trailing slash (/sitemap.txt → /sitemap.txt/).
 */
add_filter( 'redirect_canonical', function ( $redirect_url ) {
	if ( get_query_var( 'dogo_sitemap' ) ) {
		return false;
	}
	return $redirect_url;
} );

add_action( 'template_redirect', 'dogo_sitemap_render' );
function dogo_sitemap_render() {
	if ( ! get_query_var( 'dogo_sitemap' ) ) {
		return;
	}

	$urls = array( home_url( '/' ) );

	// Published pages, minus internal/system slugs.
	$exclude = array( 'home', 'sample-page' );
	foreach ( get_pages( array( 'post_status' => 'publish' ) ) as $page ) {
		if ( in_array( $page->post_name, $exclude, true ) ) {
			continue;
		}
		$urls[] = get_permalink( $page );
	}

	// Careers archive + every open position.
	$archive = get_post_type_archive_link( 'dogo_job' );
	if ( $archive ) {
		$urls[] = $archive;
	}
	$jobs = get_posts( array(
		'post_type'   => 'dogo_job',
		'post_status' => 'publish',
		'numberposts' => -1,
	) );
	foreach ( $jobs as $job ) {
		$urls[] = get_permalink( $job );
	}

	$urls = array_values( array_unique( array_filter( $urls ) ) );

	header( 'Content-Type: text/plain; charset=UTF-8' );
	header( 'Cache-Control: max-age=3600' );
	echo implode( "\n", $urls ) . "\n";
	exit;
}

/**
 * Advertise the sitemap in robots.txt and keep private docs uncrawled.
 */
add_filter( 'robots_txt', function ( $output ) {
	$output .= "Disallow: /docs/\n";
	$output .= "\nSitemap: " . home_url( '/sitemap.txt' ) . "\n";
	return $output;
}, 20 );
