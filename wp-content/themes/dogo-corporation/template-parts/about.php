<?php
/**
 * About section.
 *
 * @package DogoCorporation
 */
?>
<section class="section section--about" id="about">
	<div class="container about__grid">
		<div class="about__copy">
			<span class="eyebrow"><?php esc_html_e( 'About Dogo', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'Built around a single belief:', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'authenticity scales globally.', 'dogo-corporation' ); ?></span>
			</h2>

			<p>
				<?php esc_html_e( 'Since 2020, Dogo Corporation has bridged Japanese makers and global buyers — operating as a full cross-border eCommerce specialist across collectibles, beauty, kitchenware and proxy procurement.', 'dogo-corporation' ); ?>
			</p>
			<p>
				<?php esc_html_e( 'Our infrastructure handles sourcing, verification, multi-language support and global fulfilment, so partners can focus on what matters: bringing the very best of Japan to the world.', 'dogo-corporation' ); ?>
			</p>

			<dl class="about__highlights">
				<div>
					<dt><?php esc_html_e( 'Mission', 'dogo-corporation' ); ?></dt>
					<dd><?php esc_html_e( 'Make authentic Japanese products accessible to every buyer, in every language, on every continent.', 'dogo-corporation' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Promise', 'dogo-corporation' ); ?></dt>
					<dd><?php esc_html_e( '100% verified inventory · official brand partnerships · transparent logistics.', 'dogo-corporation' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Reach', 'dogo-corporation' ); ?></dt>
					<dd><?php esc_html_e( 'Headquarters in Fukuoka · shipping to 180+ countries worldwide.', 'dogo-corporation' ); ?></dd>
				</div>
			</dl>
		</div>

		<div class="about__visual">
			<div class="about__media">
				<img src="<?php echo esc_url( dogo_img( 'about-1.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Dogo Corporation operations', 'dogo-corporation' ); ?>" loading="lazy" />
			</div>
			<div class="about__media about__media--small">
				<img src="<?php echo esc_url( dogo_img( 'about-2.jpg' ) ); ?>" alt="<?php esc_attr_e( 'Dogo team and warehouse', 'dogo-corporation' ); ?>" loading="lazy" />
			</div>
		</div>
	</div>
</section>
