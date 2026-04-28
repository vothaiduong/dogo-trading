<?php
/**
 * Careers list — /careers/
 *
 * @package DogoCorporation
 */

get_header();

// Sort seeds by posted date desc.
$jobs = dogo_job_seeds();
usort( $jobs, function ( $a, $b ) {
	return strcmp( $b['posted'], $a['posted'] );
} );
?>

<section class="section section--page section--careers">
	<div class="container">
		<header class="section__head text-center">
			<span class="eyebrow"><?php esc_html_e( 'Careers', 'dogo-corporation' ); ?></span>
			<h1 class="section__title">
				<?php esc_html_e( 'Build the future of', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'cross-border commerce.', 'dogo-corporation' ); ?></span>
			</h1>
			<p class="section__lede">
				<?php esc_html_e( 'We\'re a multilingual team in Fukuoka building the gateway for authentic Japanese products. If you care about craft, customers and culture — let\'s talk.', 'dogo-corporation' ); ?>
			</p>
		</header>

		<?php if ( empty( $jobs ) ) : ?>
			<p class="muted text-center"><?php esc_html_e( 'No openings right now — please check back soon.', 'dogo-corporation' ); ?></p>
		<?php else : ?>
			<div class="job-list">
				<?php foreach ( $jobs as $job ) :
					$post = get_page_by_path( $job['slug'], OBJECT, 'dogo_job' );
					$url  = $post ? get_permalink( $post ) : home_url( '/careers/' . $job['slug'] . '/' );
				?>
					<a class="job-card" href="<?php echo esc_url( $url ); ?>">
						<div class="job-card__head">
							<span class="job-card__dept"><?php echo esc_html( dogo_jl( $job['department'] ) ); ?></span>
							<span class="job-card__date"><?php echo esc_html( mysql2date( get_option( 'date_format' ), $job['posted'] ) ); ?></span>
						</div>
						<h2 class="job-card__title"><?php echo esc_html( dogo_jl( $job['title'] ) ); ?></h2>
						<p class="job-card__intro"><?php echo esc_html( dogo_jl( $job['intro'] ) ); ?></p>
						<div class="job-card__meta">
							<span><?php dogo_icon( 'globe' ); ?> <?php echo esc_html( dogo_jl( $job['location'] ) ); ?></span>
							<span class="dot-sep">·</span>
							<span><?php echo esc_html( dogo_jl( $job['type'] ) ); ?></span>
							<span class="dot-sep">·</span>
							<span><?php echo esc_html( dogo_jl( $job['level'] ) ); ?></span>
							<span class="job-card__more">
								<?php esc_html_e( 'View role', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
							</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>

			<div class="careers__cta">
				<h3><?php esc_html_e( 'Don\'t see your role?', 'dogo-corporation' ); ?></h3>
				<p class="muted">
					<?php esc_html_e( 'We\'re always open to exceptional people. Tell us how you can help us scale Japan to the world.', 'dogo-corporation' ); ?>
				</p>
				<a href="mailto:info@dogo-trading.com?subject=<?php echo rawurlencode( __( 'Open application', 'dogo-corporation' ) ); ?>" class="btn btn--primary">
					<?php esc_html_e( 'Send us an open application', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer();
