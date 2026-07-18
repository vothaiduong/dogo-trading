<?php
/**
 * Front page (homepage) — composes all section partials.
 *
 * @package DogoCorporation
 */

get_header();

get_template_part( 'template-parts/hero' );
get_template_part( 'template-parts/stats' );
get_template_part( 'template-parts/services' );
get_template_part( 'template-parts/about' );
get_template_part( 'template-parts/work' );
get_template_part( 'template-parts/cta' );
get_template_part( 'template-parts/contact' );
get_template_part( 'template-parts/oauth-app' );

get_footer();
