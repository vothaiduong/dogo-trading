<?php
/**
 * SEO meta — title, description, Open Graph, Twitter, hreflang, canonical.
 *
 * Lightweight, no plugin needed. Page-aware descriptions per language.
 *
 * @package DogoCorporation
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Site-wide tagline used as OG site_description fallback.
 */
function dogo_seo_site_tagline() {
	return __( 'Cross-border eCommerce for authentic Japanese products. Collectibles, beauty, kitchenware and Japan purchase agency.', 'dogo-corporation' );
}

/**
 * Per-page meta description. Returns localised string ~150 chars.
 */
function dogo_seo_description() {
	if ( is_front_page() ) {
		return __( 'Dogo Corporation — the global gateway to authentic Japanese products. Trusted by 1M+ customers across 180+ countries since 2020. Collectibles, beauty, kitchenware and Japan purchase agency.', 'dogo-corporation' );
	}
	if ( is_page( 'company' ) ) {
		return __( 'Dogo Corporation (株式会社 DOGO) — corporate profile, founded 2020, headquartered in Fukuoka. Cross-border eCommerce specialist serving 180+ countries.', 'dogo-corporation' );
	}
	if ( is_page( 'privacy' ) ) {
		return __( 'Dogo Corporation Privacy Policy — what personal information we collect, how we use it, and the rights you have as a customer.', 'dogo-corporation' );
	}
	if ( is_page( 'life' ) ) {
		return __( 'A look behind the curtain at Dogo Corporation — parties, team building, food, workshops and the everyday joy of our multilingual Fukuoka team.', 'dogo-corporation' );
	}
	if ( is_post_type_archive( 'dogo_job' ) ) {
		return __( 'Open roles at Dogo Corporation in Fukuoka and remote. Build the future of cross-border eCommerce with a multilingual team.', 'dogo-corporation' );
	}
	if ( is_singular( 'dogo_job' ) ) {
		$job = function_exists( 'dogo_job' ) ? dogo_job() : null;
		if ( $job ) {
			return sprintf(
				/* translators: 1: job title, 2: location, 3: employment type */
				__( '%1$s at Dogo Corporation — %2$s, %3$s. Apply now to join our cross-border eCommerce team.', 'dogo-corporation' ),
				dogo_jl( $job['title'] ),
				dogo_jl( $job['location'] ),
				dogo_jl( $job['type'] )
			);
		}
	}
	if ( is_singular() ) {
		$excerpt = wp_strip_all_tags( get_the_excerpt() );
		if ( $excerpt ) {
			return wp_trim_words( $excerpt, 30, '…' );
		}
	}
	return dogo_seo_site_tagline();
}

/**
 * Per-page document title — overrides default WP composition for cleaner SEO.
 */
add_filter( 'pre_get_document_title', 'dogo_seo_document_title' );
function dogo_seo_document_title( $title ) {
	$site = get_bloginfo( 'name' );
	$sep  = '·';

	if ( is_front_page() ) {
		return sprintf(
			/* translators: 1: site name, 2: tagline phrase */
			__( '%1$s %2$s Authentic Japanese products, worldwide.', 'dogo-corporation' ),
			$site,
			$sep
		);
	}
	if ( is_page( 'company' ) ) {
		return sprintf( '%1$s %2$s %3$s', __( 'Company profile', 'dogo-corporation' ), $sep, $site );
	}
	if ( is_page( 'privacy' ) ) {
		return sprintf( '%1$s %2$s %3$s', __( 'Privacy Policy', 'dogo-corporation' ), $sep, $site );
	}
	if ( is_page( 'life' ) ) {
		return sprintf( '%1$s %2$s %3$s', __( 'Life at Dogo', 'dogo-corporation' ), $sep, $site );
	}
	if ( is_post_type_archive( 'dogo_job' ) ) {
		return sprintf( '%1$s %2$s %3$s', __( 'Careers', 'dogo-corporation' ), $sep, $site );
	}
	if ( is_singular( 'dogo_job' ) ) {
		$job = function_exists( 'dogo_job' ) ? dogo_job() : null;
		if ( $job ) {
			return sprintf( '%1$s %2$s %3$s %4$s %5$s',
				dogo_jl( $job['title'] ),
				$sep,
				__( 'Careers', 'dogo-corporation' ),
				$sep,
				$site
			);
		}
	}
	if ( is_404() ) {
		return sprintf( '%1$s %2$s %3$s', __( 'Page not found', 'dogo-corporation' ), $sep, $site );
	}
	if ( is_search() ) {
		return sprintf(
			/* translators: 1: search query, 2: separator, 3: site name */
			__( 'Search: %1$s %2$s %3$s', 'dogo-corporation' ),
			esc_html( get_search_query() ),
			$sep,
			$site
		);
	}
	return $title;
}

/**
 * Canonical URL of current page (without ?lang=).
 */
function dogo_seo_canonical_url() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_post_type_archive() ) {
		return get_post_type_archive_link( get_post_type() ?: get_query_var( 'post_type' ) );
	}
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	return home_url( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) );
}

/**
 * Default OG image (1200×630). Hand-built to match the homepage hero.
 * Singulars override with their featured image when one is set.
 *
 * Re-generate the source PNG with: `python3 scripts/generate-og-image.py`
 */
function dogo_seo_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'dogo-hero' );
		if ( $src ) {
			return $src[0];
		}
	}
	return DOGO_THEME_URI . '/assets/images/og-image.png?v=' . DOGO_THEME_VERSION;
}

/**
 * OG type per page.
 */
function dogo_seo_og_type() {
	if ( is_singular( 'dogo_job' ) ) {
		return 'article';
	}
	if ( is_singular( 'post' ) ) {
		return 'article';
	}
	return 'website';
}

/**
 * Emit meta description, canonical, hreflang, OG and Twitter tags.
 */
add_action( 'wp_head', 'dogo_seo_emit_meta', 5 );
function dogo_seo_emit_meta() {
	$desc = dogo_seo_description();
	$url  = dogo_seo_canonical_url();
	$img  = dogo_seo_og_image();
	$site = get_bloginfo( 'name' );
	$title = wp_get_document_title();

	$lang_map = array( 'ja' => 'ja_JP', 'en' => 'en_US', 'vi' => 'vi_VN' );
	$lang = function_exists( 'dogo_current_lang' ) ? dogo_current_lang() : 'ja';
	$og_locale = $lang_map[ $lang ] ?? 'ja_JP';

	echo "\n<!-- Dogo SEO -->\n";
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";

	// hreflang for the 3 language variants + x-default
	foreach ( array( 'ja', 'en', 'vi' ) as $code ) {
		$alt = add_query_arg( 'lang', $code, $url );
		echo '<link rel="alternate" hreflang="' . esc_attr( $code ) . '" href="' . esc_url( $alt ) . '">' . "\n";
	}
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $url ) . '">' . "\n";

	// Open Graph
	echo '<meta property="og:type" content="' . esc_attr( dogo_seo_og_type() ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site ) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr( $og_locale ) . '">' . "\n";
	foreach ( $lang_map as $code => $loc ) {
		if ( $code === $lang ) { continue; }
		echo '<meta property="og:locale:alternate" content="' . esc_attr( $loc ) . '">' . "\n";
	}
	if ( $img ) {
		echo '<meta property="og:image" content="' . esc_url( $img ) . '">' . "\n";
		echo '<meta property="og:image:width" content="1200">' . "\n";
		echo '<meta property="og:image:height" content="630">' . "\n";
		echo '<meta property="og:image:alt" content="' . esc_attr( $site ) . '">' . "\n";
	}

	// Twitter card
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	if ( $img ) {
		echo '<meta name="twitter:image" content="' . esc_url( $img ) . '">' . "\n";
	}

	// JSON-LD: Organization (only on home/company)
	if ( is_front_page() || is_page( 'company' ) ) {
		$org = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'name'     => $site,
			'alternateName' => '株式会社 DOGO',
			'url'      => home_url( '/' ),
			'logo'     => DOGO_THEME_URI . '/assets/images/logo-dark.png',
			'foundingDate' => '2020',
			'address'  => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => '2-2-26 Yutaka, Hakata-ku',
				'addressLocality' => 'Fukuoka',
				'postalCode'      => '812-0006',
				'addressCountry'  => 'JP',
			),
			'email'    => 'info@dogo-trading.com',
			'sameAs'   => array( 'https://dogo-trading.com' ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}

	// JSON-LD: JobPosting on single career
	if ( is_singular( 'dogo_job' ) && function_exists( 'dogo_job' ) && ( $job = dogo_job() ) ) {
		$jp = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'JobPosting',
			'title'       => dogo_jl( $job['title'] ),
			'description' => dogo_jl( $job['intro'] ),
			'datePosted'  => $job['posted'],
			'employmentType' => strtoupper( str_replace( ' ', '_', is_array( $job['type'] ) ? ( $job['type']['en'] ?? '' ) : $job['type'] ) ),
			'hiringOrganization' => array(
				'@type' => 'Organization',
				'name'  => $site,
				'sameAs'=> home_url( '/' ),
				'logo'  => DOGO_THEME_URI . '/assets/images/logo-dark.png',
			),
			'jobLocation' => array(
				'@type' => 'Place',
				'address' => array(
					'@type' => 'PostalAddress',
					'addressLocality' => 'Fukuoka',
					'addressRegion'   => 'Fukuoka',
					'addressCountry'  => 'JP',
				),
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $jp, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}

	echo "<!-- /Dogo SEO -->\n";
}
