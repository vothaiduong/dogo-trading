<?php
/**
 * Our work / capabilities section.
 *
 * @package DogoCorporation
 */
$pillars = array(
	array(
		'title' => __( 'Authentic-only sourcing', 'dogo-corporation' ),
		'desc'  => __( 'Direct relationships with Japanese brands and verified resellers — every item ships with provenance.', 'dogo-corporation' ),
	),
	array(
		'title' => __( 'Cross-border logistics', 'dogo-corporation' ),
		'desc'  => __( 'Consolidated warehousing, customs handling and tracked international fulfilment to 180+ countries.', 'dogo-corporation' ),
	),
	array(
		'title' => __( 'Multi-channel commerce', 'dogo-corporation' ),
		'desc'  => __( 'Storefronts, marketplaces and proxy bidding across Amazon JP, Yahoo Auctions, Mercari and Rakuma.', 'dogo-corporation' ),
	),
	array(
		'title' => __( 'Native-language concierge', 'dogo-corporation' ),
		'desc'  => __( 'Bilingual support team handles negotiation, translation, payment and post-sale care.', 'dogo-corporation' ),
	),
	array(
		'title' => __( 'Brand partnerships', 'dogo-corporation' ),
		'desc'  => __( 'Authorized distributor for leading Japanese beauty, kitchen and collectible labels.', 'dogo-corporation' ),
	),
	array(
		'title' => __( 'Quality & compliance', 'dogo-corporation' ),
		'desc'  => __( 'Inspection workflows aligned with destination-market regulations and customer-protection standards.', 'dogo-corporation' ),
	),
);
?>
<section class="section section--work" id="work">
	<div class="container">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Our work', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'A complete', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'cross-border operating system.', 'dogo-corporation' ); ?></span>
			</h2>
			<p class="section__lede">
				<?php esc_html_e( 'Six capabilities that compound — sourcing, logistics, commerce, support, partnerships, compliance.', 'dogo-corporation' ); ?>
			</p>
		</header>

		<div class="pillars">
			<?php foreach ( $pillars as $i => $p ) : ?>
				<div class="pillar">
					<div class="pillar__num"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></div>
					<h3 class="pillar__title"><?php echo esc_html( $p['title'] ); ?></h3>
					<p class="pillar__desc"><?php echo esc_html( $p['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
