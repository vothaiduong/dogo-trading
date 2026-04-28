<?php
/**
 * Default page template.
 *
 * @package DogoCorporation
 */

get_header(); ?>

<section class="section section--page">
	<div class="container container--narrow">
		<?php while ( have_posts() ) : the_post(); ?>
			<header class="section__head">
				<h1 class="section__title"><?php the_title(); ?></h1>
			</header>
			<article <?php post_class( 'prose' ); ?>>
				<?php the_content(); ?>
			</article>
		<?php endwhile; ?>
	</div>
</section>

<?php get_footer();
