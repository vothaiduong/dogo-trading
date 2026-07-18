<?php
/**
 * Template for /operations-data-integration/ — Google OAuth app disclosure.
 *
 * Auto-bound when a WP page with slug "operations-data-integration" exists
 * (created by dogo_ensure_static_pages()).
 *
 * Localized like the rest of the theme (JA default / EN / VI). The English
 * strings are the msgids and match the consent-screen submission — Google's
 * reviewers can read the exact approved text at /operations-data-integration/?lang=en.
 *
 * @package DogoCorporation
 */

get_header(); ?>

<section class="section section--page section--oauth-app">
	<div class="container container--narrow">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Internal application disclosure', 'dogo-corporation' ); ?></span>
			<h1 class="section__title gradient-text"><?php esc_html_e( 'About Dogo Operations Data Integration', 'dogo-corporation' ); ?></h1>
		</header>

		<article class="prose oauth-app__body">
			<p>
				<?php esc_html_e( 'Dogo Corporation is a cross-border e-commerce company that connects customers worldwide with authentic Japanese products. We operate services for collectibles and figures, beauty and personal care products, kitchenware, and proxy purchasing from Japanese marketplaces. Our operations include product sourcing, authenticity checks, inventory and order processing, international logistics, customer support, and compliance for deliveries to more than 180 countries.', 'dogo-corporation' ); ?>
			</p>
			<p>
				<?php
				printf(
					/* translators: %s — application name (kept in English) */
					esc_html__( '%s is an internal business automation application used only by authorized Dogo Corporation personnel. It supports the company\'s operational reporting and data management by securely transferring and organizing business data from Dogo\'s internal systems into Google BigQuery.', 'dogo-corporation' ),
					'<strong>Dogo Operations Data Integration</strong>'
				);
				?>
			</p>
			<p>
				<?php esc_html_e( 'The application uses Google BigQuery solely to run data queries and create, update, or maintain datasets and tables required for internal reporting, analytics, operational monitoring, and business planning. It does not provide a public consumer-facing service, access personal Google content such as Gmail, Drive, Contacts, or Calendar, or share Google user data with third parties.', 'dogo-corporation' ); ?>
			</p>
			<p>
				<?php
				printf(
					/* translators: %s — link to the privacy policy */
					esc_html__( 'Access to Google services is limited to the minimum permissions necessary for the application to perform these internal BigQuery data-processing functions. Data is accessed and used only for Dogo Corporation\'s legitimate business operations and is handled in accordance with our %s.', 'dogo-corporation' ),
					'<a href="' . esc_url( home_url( '/privacy/' ) ) . '">' . esc_html__( 'Privacy Policy', 'dogo-corporation' ) . '</a>'
				);
				?>
			</p>
			<p>
				<?php
				printf(
					/* translators: %s — contact email link */
					esc_html__( 'For questions about this application or our data practices, please contact us at %s.', 'dogo-corporation' ),
					'<a href="mailto:info@dogo-trading.com">info@dogo-trading.com</a>'
				);
				?>
			</p>
		</article>
	</div>
</section>

<?php get_footer();
