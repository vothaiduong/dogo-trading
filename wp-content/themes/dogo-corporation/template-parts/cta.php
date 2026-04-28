<?php
/**
 * CTA banner.
 *
 * @package DogoCorporation
 */
?>
<section class="section section--cta" aria-label="<?php esc_attr_e( 'Call to action', 'dogo-corporation' ); ?>">
	<div class="container">
		<div class="cta">
			<div class="cta__copy">
				<h2 class="cta__title">
					<?php esc_html_e( 'Ready to source from Japan,', 'dogo-corporation' ); ?>
					<span class="gradient-text"><?php esc_html_e( 'the right way?', 'dogo-corporation' ); ?></span>
				</h2>
				<p class="cta__desc">
					<?php esc_html_e( 'Talk to a Dogo specialist about wholesale, retail, proxy or partnership opportunities. We respond within one business day.', 'dogo-corporation' ); ?>
				</p>
			</div>
			<div class="cta__actions">
				<a href="#contact" class="btn btn--primary"><?php esc_html_e( 'Start a conversation', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?></a>
				<a href="#services" class="btn btn--ghost"><?php esc_html_e( 'See services', 'dogo-corporation' ); ?></a>
			</div>
		</div>
	</div>
</section>
