<?php
/**
 * Platforms & Service Providers strip — homepage logo cloud.
 *
 * @package DogoCorporation
 */

$partners = array(
	array( 'slug' => 'dhl',       'name' => 'DHL',         'url' => 'https://www.dhl.com/' ),
	array( 'slug' => 'fedex',     'name' => 'FedEx',       'url' => 'https://www.fedex.com/' ),
	array( 'slug' => 'ups',       'name' => 'UPS',         'url' => 'https://www.ups.com/' ),
	array( 'slug' => 'japanpost', 'name' => 'Japan Post',  'url' => 'https://www.post.japanpost.jp/' ),
	array( 'slug' => 'google',    'name' => 'Google',      'url' => 'https://www.google.com/' ),
	array( 'slug' => 'paypal',    'name' => 'PayPal',      'url' => 'https://www.paypal.com/' ),
	array( 'slug' => 'shopify',   'name' => 'Shopify',     'url' => 'https://www.shopify.com/' ),
);
?>
<section class="section section--partners" aria-label="<?php esc_attr_e( 'Platforms and service providers', 'dogo-corporation' ); ?>">
	<div class="container">
		<header class="partners__head">
			<span class="eyebrow"><?php esc_html_e( 'Trusted infrastructure', 'dogo-corporation' ); ?></span>
			<h2 class="partners__title">
				<?php esc_html_e( 'Platforms & service providers', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'we work with.', 'dogo-corporation' ); ?></span>
			</h2>
		</header>

		<ul class="partners__grid">
			<?php foreach ( $partners as $p ) :
				$svg = DOGO_THEME_DIR . '/assets/images/partners/' . $p['slug'] . '.svg';
				if ( ! file_exists( $svg ) ) { continue; }
				$markup = file_get_contents( $svg );
			?>
				<li class="partner">
					<a class="partner__link" href="<?php echo esc_url( $p['url'] ); ?>" target="_blank" rel="noopener nofollow" title="<?php echo esc_attr( $p['name'] ); ?>" aria-label="<?php echo esc_attr( $p['name'] ); ?>">
						<span class="partner__logo partner__logo--<?php echo esc_attr( $p['slug'] ); ?>"><?php echo $markup; ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
