<?php
/**
 * 404 not found.
 *
 * @package DogoCorporation
 */

get_header(); ?>

<section class="section section--page section--404">
	<div class="container container--narrow text-center">
		<span class="eyebrow"><?php esc_html_e( 'Error 404', 'dogo-corporation' ); ?></span>
		<h1 class="section__title gradient-text"><?php esc_html_e( 'Page not found', 'dogo-corporation' ); ?></h1>
		<p class="section__lede"><?php esc_html_e( 'The page you were looking for doesn\'t exist or has been moved.', 'dogo-corporation' ); ?></p>
		<p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Back to home', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?></a>
		</p>
	</div>
</section>

<?php get_footer();
