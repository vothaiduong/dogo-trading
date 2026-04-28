<?php
/**
 * Single post template.
 *
 * @package DogoCorporation
 */

get_header(); ?>

<section class="section section--page">
	<div class="container container--narrow">
		<?php while ( have_posts() ) : the_post(); ?>
			<header class="section__head">
				<span class="eyebrow"><?php echo esc_html( get_the_date() ); ?></span>
				<h1 class="section__title"><?php the_title(); ?></h1>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="post-hero"><?php the_post_thumbnail( 'dogo-hero' ); ?></figure>
			<?php endif; ?>
			<article <?php post_class( 'prose' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>

<?php get_footer();
