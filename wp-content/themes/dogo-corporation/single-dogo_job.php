<?php
/**
 * Career detail — /careers/<slug>/
 *
 * @package DogoCorporation
 */

get_header();

while ( have_posts() ) :
	the_post();
	$job = dogo_job();

	if ( ! $job ) {
		// post exists but no seed entry — fallback to the post body
		?>
		<section class="section section--page">
			<div class="container container--narrow">
				<header class="section__head">
					<span class="eyebrow"><?php esc_html_e( 'Careers', 'dogo-corporation' ); ?></span>
					<h1 class="section__title"><?php the_title(); ?></h1>
				</header>
				<article class="prose"><?php the_content(); ?></article>
			</div>
		</section>
		<?php
		get_footer();
		return;
	}

	$mailto = 'mailto:info@dogo-trading.com?subject='
		. rawurlencode( sprintf( /* translators: %s = job title */ __( 'Application: %s', 'dogo-corporation' ), dogo_jl( $job['title'] ) ) );
?>

<section class="section section--page section--job">
	<div class="container container--narrow">
		<a class="back-link" href="<?php echo esc_url( get_post_type_archive_link( 'dogo_job' ) ); ?>">
			← <?php esc_html_e( 'All openings', 'dogo-corporation' ); ?>
		</a>

		<header class="job-hero">
			<span class="eyebrow"><?php echo esc_html( dogo_jl( $job['department'] ) ); ?></span>
			<h1 class="section__title"><?php echo esc_html( dogo_jl( $job['title'] ) ); ?></h1>

			<dl class="job-hero__meta">
				<div><dt><?php esc_html_e( 'Location', 'dogo-corporation' ); ?></dt><dd><?php echo esc_html( dogo_jl( $job['location'] ) ); ?></dd></div>
				<div><dt><?php esc_html_e( 'Employment type', 'dogo-corporation' ); ?></dt><dd><?php echo esc_html( dogo_jl( $job['type'] ) ); ?></dd></div>
				<div><dt><?php esc_html_e( 'Experience', 'dogo-corporation' ); ?></dt><dd><?php echo esc_html( dogo_jl( $job['level'] ) ); ?></dd></div>
				<div><dt><?php esc_html_e( 'Salary range', 'dogo-corporation' ); ?></dt><dd><?php echo esc_html( $job['salary'] ); ?></dd></div>
				<div><dt><?php esc_html_e( 'Posted', 'dogo-corporation' ); ?></dt><dd><?php echo esc_html( mysql2date( get_option( 'date_format' ), $job['posted'] ) ); ?></dd></div>
			</dl>

			<a class="btn btn--primary" href="<?php echo esc_url( $mailto ); ?>">
				<?php esc_html_e( 'Apply by email', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
			</a>
		</header>

		<article class="job-body prose">
			<p class="job-body__intro"><?php echo esc_html( dogo_jl( $job['intro'] ) ); ?></p>

			<h2><?php esc_html_e( 'What you\'ll do', 'dogo-corporation' ); ?></h2>
			<ul>
				<?php foreach ( dogo_jl( $job['responsibilities'] ) as $item ) : ?>
					<li><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>

			<h2><?php esc_html_e( 'What we\'re looking for', 'dogo-corporation' ); ?></h2>
			<ul>
				<?php foreach ( dogo_jl( $job['requirements'] ) as $item ) : ?>
					<li><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>

			<h2><?php esc_html_e( 'Nice to have', 'dogo-corporation' ); ?></h2>
			<ul>
				<?php foreach ( dogo_jl( $job['nice'] ) as $item ) : ?>
					<li><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>

			<h2><?php esc_html_e( 'What you\'ll get', 'dogo-corporation' ); ?></h2>
			<ul>
				<?php foreach ( dogo_jl( $job['benefits'] ) as $item ) : ?>
					<li><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>

			<h2><?php esc_html_e( 'How to apply', 'dogo-corporation' ); ?></h2>
			<p>
				<?php
				printf(
					/* translators: %s = email link */
					esc_html__( 'Send your CV (and a short note on why this role) to %s. We respond within one business day.', 'dogo-corporation' ),
					'<a href="' . esc_url( $mailto ) . '">info@dogo-trading.com</a>'
				);
				?>
			</p>
		</article>

		<div class="job-foot">
			<a class="btn btn--primary" href="<?php echo esc_url( $mailto ); ?>">
				<?php esc_html_e( 'Apply by email', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
			</a>
			<a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'dogo_job' ) ); ?>">
				<?php esc_html_e( 'See other openings', 'dogo-corporation' ); ?>
			</a>
		</div>
	</div>
</section>

<?php
endwhile;
get_footer();
