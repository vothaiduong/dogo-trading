<?php
/**
 * Default fallback template.
 *
 * @package DogoCorporation
 */

get_header(); ?>

<section class="section">
	<div class="container">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Journal', 'dogo-corporation' ); ?></span>
			<h1 class="section__title"><?php single_post_title(); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a class="post-card__media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'dogo-card' ); ?></a>
						<?php endif; ?>
						<div class="post-card__body">
							<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="post-card__excerpt"><?php the_excerpt(); ?></div>
							<a class="post-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?></a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php the_posts_pagination( array(
				'prev_text' => __( '« Previous', 'dogo-corporation' ),
				'next_text' => __( 'Next »', 'dogo-corporation' ),
			) ); ?>
		<?php else : ?>
			<p class="muted"><?php esc_html_e( 'Nothing here yet.', 'dogo-corporation' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer();
