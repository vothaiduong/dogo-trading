<?php
/**
 * Life at Dogo — /life/
 *
 * @package DogoCorporation
 */

$cats   = dogo_life_categories();
$quotes = dogo_life_quotes();
$stats  = dogo_life_stats();

// Curate which categories appear in the top hero collage (asymmetric, 6 tiles).
$hero_keys = array( 'party', 'food', 'birthday', 'office', 'teambuilding', 'halloween' );
$hero_tiles = array_filter( $cats, function ( $c ) use ( $hero_keys ) {
	return in_array( $c['key'], $hero_keys, true );
} );

get_header(); ?>

<section class="section section--life-hero">
	<div class="container">
		<header class="life-hero__head">
			<span class="eyebrow"><?php esc_html_e( 'Life at Dogo', 'dogo-corporation' ); ?></span>
			<h1 class="life-hero__title">
				<?php esc_html_e( 'A team in Fukuoka,', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'shipping joy worldwide.', 'dogo-corporation' ); ?></span>
			</h1>
			<p class="life-hero__lede">
				<?php esc_html_e( 'We work hard on the craft of cross-border eCommerce — and we have a lot of fun while doing it. Here\'s a look behind the curtain.', 'dogo-corporation' ); ?>
			</p>
		</header>

		<div class="life-collage">
			<?php $i = 0; foreach ( $hero_tiles as $tile ) : $i++; ?>
				<figure class="life-collage__tile life-collage__tile--<?php echo (int) $i; ?> life-photo life-photo--<?php echo esc_attr( $tile['palette'] ); ?>">
					<?php if ( ! empty( $tile['image'] ) ) : ?>
						<img src="<?php echo esc_url( $tile['image'] ); ?>" alt="<?php echo esc_attr( dogo_jl( $tile['title'] ) ); ?>" loading="lazy" />
						<figcaption class="life-collage__cap">
							<span class="life-collage__cap-icon"><?php dogo_icon( $tile['icon'] ); ?></span>
							<?php echo esc_html( dogo_jl( $tile['title'] ) ); ?>
						</figcaption>
					<?php else : ?>
						<div class="life-photo__ph">
							<span class="life-photo__icon"><?php dogo_icon( $tile['icon'] ); ?></span>
							<span class="life-photo__label"><?php echo esc_html( dogo_jl( $tile['title'] ) ); ?></span>
						</div>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--life-stats" aria-label="<?php esc_attr_e( 'Culture metrics', 'dogo-corporation' ); ?>">
	<div class="container">
		<div class="stats__grid">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="stats__item">
					<div class="stats__value"><?php echo esc_html( $stat['value'] ); ?></div>
					<div class="stats__label"><?php echo esc_html( dogo_jl( $stat['label'] ) ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--life-cats" id="moments">
	<div class="container">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Moments we cherish', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'Nine windows into', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'how we work and play.', 'dogo-corporation' ); ?></span>
			</h2>
			<p class="section__lede">
				<?php esc_html_e( 'A growing collection of glimpses from our HQ, our warehouse and the corners in between. New photos drop every quarter.', 'dogo-corporation' ); ?>
			</p>
		</header>

		<div class="life-cats">
			<?php foreach ( $cats as $cat ) : ?>
				<article class="life-cat">
					<div class="life-cat__media life-photo life-photo--<?php echo esc_attr( $cat['palette'] ); ?>">
						<?php if ( ! empty( $cat['image'] ) ) : ?>
							<img src="<?php echo esc_url( $cat['image'] ); ?>" alt="<?php echo esc_attr( dogo_jl( $cat['title'] ) ); ?>" loading="lazy" />
						<?php else : ?>
							<div class="life-photo__ph">
								<span class="life-photo__icon"><?php dogo_icon( $cat['icon'] ); ?></span>
								<span class="life-photo__chip"><?php esc_html_e( 'Photos coming soon', 'dogo-corporation' ); ?></span>
							</div>
						<?php endif; ?>
					</div>
					<div class="life-cat__body">
						<h3 class="life-cat__title"><?php echo esc_html( dogo_jl( $cat['title'] ) ); ?></h3>
						<p class="life-cat__desc"><?php echo esc_html( dogo_jl( $cat['desc'] ) ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--life-voices" id="voices">
	<div class="container">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Team voices', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'In their own words.', 'dogo-corporation' ); ?>
			</h2>
		</header>

		<div class="voices">
			<?php foreach ( $quotes as $q ) :
				$name = dogo_jl( $q['name'] );
				$initial = mb_substr( $name, 0, 1 );
			?>
				<figure class="voice">
					<blockquote class="voice__quote"><?php echo esc_html( dogo_jl( $q['quote'] ) ); ?></blockquote>
					<figcaption class="voice__author">
						<span class="voice__avatar" aria-hidden="true"><?php echo esc_html( $initial ); ?></span>
						<span class="voice__meta">
							<strong><?php echo esc_html( $name ); ?></strong>
							<span class="muted"><?php echo esc_html( dogo_jl( $q['role'] ) ); ?> · <?php echo esc_html( $q['since'] ); ?>–</span>
						</span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--life-cta">
	<div class="container">
		<div class="cta">
			<div class="cta__copy">
				<h2 class="cta__title">
					<?php esc_html_e( 'Want to be in the next photo?', 'dogo-corporation' ); ?>
					<span class="gradient-text"><?php esc_html_e( 'We\'re hiring.', 'dogo-corporation' ); ?></span>
				</h2>
				<p class="cta__desc">
					<?php esc_html_e( 'Browse open roles in Fukuoka and remote — we\'d love to share a Friday lunch with you.', 'dogo-corporation' ); ?>
				</p>
			</div>
			<div class="cta__actions">
				<a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'See open roles', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
				</a>
				<a href="mailto:info@dogo-trading.com" class="btn btn--ghost">
					<?php esc_html_e( 'Send an open application', 'dogo-corporation' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
