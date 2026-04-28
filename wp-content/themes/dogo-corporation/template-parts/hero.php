<?php
/**
 * Hero section.
 *
 * @package DogoCorporation
 */
?>
<section class="hero" id="hero">
	<div class="container hero__inner">
		<div class="hero__copy">
			<a href="#services" class="pill">
				<span class="pill__dot"></span>
				<?php esc_html_e( 'Now serving 1,000,000+ customers worldwide', 'dogo-corporation' ); ?>
				<?php dogo_icon( 'arrow-right' ); ?>
			</a>

			<h1 class="hero__title">
				<span class="gradient-text"><?php esc_html_e( 'Shaping excellence', 'dogo-corporation' ); ?></span><br>
				<?php esc_html_e( 'in cross-border eCommerce.', 'dogo-corporation' ); ?>
			</h1>

			<p class="hero__lede">
				<?php esc_html_e( 'Dogo Corporation is the global gateway to authentic Japanese products — connecting collectors, beauty enthusiasts and home chefs across 180+ countries, with over 3 million items shipped since 2020.', 'dogo-corporation' ); ?>
			</p>

			<div class="hero__actions">
				<a href="#services" class="btn btn--primary"><?php esc_html_e( 'Explore services', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?></a>
				<a href="#about" class="btn btn--ghost"><?php esc_html_e( 'About Dogo', 'dogo-corporation' ); ?></a>
			</div>

			<ul class="hero__chips">
				<li><?php dogo_icon( 'check' ); ?> <?php esc_html_e( 'Authentic-only sourcing', 'dogo-corporation' ); ?></li>
				<li><?php dogo_icon( 'check' ); ?> <?php esc_html_e( 'Worldwide fulfilment', 'dogo-corporation' ); ?></li>
				<li><?php dogo_icon( 'globe' ); ?> <?php esc_html_e( 'EN · VI · 日本語', 'dogo-corporation' ); ?></li>
			</ul>
		</div>

		<div class="hero__visual">
			<div class="hero__card hero__card--main">
				<div class="hero__card-bar">
					<span class="dot"></span><span class="dot"></span><span class="dot"></span>
					<span class="hero__card-title">dogo-corp · global ledger</span>
				</div>
				<div class="hero__card-body">
					<div class="ledger">
						<div class="ledger__row"><span class="ledger__tag tag-jf">JF</span><span><?php esc_html_e( 'Limited figurine', 'dogo-corporation' ); ?></span><span class="muted">¥18,400</span></div>
						<div class="ledger__row"><span class="ledger__tag tag-jwl">JWL</span><span><?php esc_html_e( 'Premium skincare set', 'dogo-corporation' ); ?></span><span class="muted">¥9,200</span></div>
						<div class="ledger__row"><span class="ledger__tag tag-kit">KIT</span><span><?php esc_html_e( 'Hand-forged Santoku', 'dogo-corporation' ); ?></span><span class="muted">¥34,800</span></div>
						<div class="ledger__row"><span class="ledger__tag tag-prx">PRX</span><span><?php esc_html_e( 'Yahoo Auctions sourcing', 'dogo-corporation' ); ?></span><span class="muted">¥ —</span></div>
						<div class="ledger__row ledger__row--total"><span><?php esc_html_e( 'Verified · shipped today', 'dogo-corporation' ); ?></span><span class="badge"><?php dogo_icon( 'sparkle' ); ?> 1,284</span></div>
					</div>
				</div>
			</div>
			<div class="hero__card hero__card--float">
				<span class="hero__card-eyebrow"><?php esc_html_e( 'Live', 'dogo-corporation' ); ?></span>
				<strong>+12.4%</strong>
				<span class="muted"><?php esc_html_e( 'cross-border volume MoM', 'dogo-corporation' ); ?></span>
			</div>
		</div>
	</div>
</section>
