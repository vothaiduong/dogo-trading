<?php
/**
 * Stats strip.
 *
 * @package DogoCorporation
 */
?>
<section class="stats" id="stats" aria-label="<?php esc_attr_e( 'Key metrics', 'dogo-corporation' ); ?>">
	<div class="container">
		<div class="stats__grid">
			<?php foreach ( dogo_stats() as $stat ) : ?>
				<div class="stats__item">
					<div class="stats__value"><?php echo esc_html( $stat['value'] ); ?></div>
					<div class="stats__label"><?php echo esc_html( $stat['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
