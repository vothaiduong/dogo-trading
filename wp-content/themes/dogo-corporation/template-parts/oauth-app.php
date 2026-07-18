<?php
/**
 * "Dogo Operations Data Integration" disclosure — required for Google OAuth
 * app verification (homepage must describe the app, its data use, and link
 * to the privacy policy, visible without login).
 *
 * Kept in English intentionally: Google's verification reviewers read the
 * page in English, and the text must match the consent-screen submission.
 *
 * @package DogoCorporation
 */
?>
<section class="section section--oauth-app" id="operations-data-integration">
	<div class="container container--narrow">
		<header class="section__head">
			<span class="eyebrow">Internal application disclosure</span>
			<h2 class="section__title">About Dogo Operations <span class="gradient-text">Data Integration</span></h2>
		</header>

		<div class="prose oauth-app__body">
			<p>
				Dogo Corporation is a cross-border e-commerce company that connects customers worldwide
				with authentic Japanese products. We operate services for collectibles and figures,
				beauty and personal care products, kitchenware, and proxy purchasing from Japanese
				marketplaces. Our operations include product sourcing, authenticity checks, inventory
				and order processing, international logistics, customer support, and compliance for
				deliveries to more than 180 countries.
			</p>
			<p>
				<strong>Dogo Operations Data Integration</strong> is an internal business automation
				application used only by authorized Dogo Corporation personnel. It supports the
				company&rsquo;s operational reporting and data management by securely transferring and
				organizing business data from Dogo&rsquo;s internal systems into Google BigQuery.
			</p>
			<p>
				The application uses Google BigQuery solely to run data queries and create, update,
				or maintain datasets and tables required for internal reporting, analytics, operational
				monitoring, and business planning. It does not provide a public consumer-facing service,
				access personal Google content such as Gmail, Drive, Contacts, or Calendar, or share
				Google user data with third parties.
			</p>
			<p>
				Access to Google services is limited to the minimum permissions necessary for the
				application to perform these internal BigQuery data-processing functions. Data is
				accessed and used only for Dogo Corporation&rsquo;s legitimate business operations and
				is handled in accordance with our
				<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy Policy</a>.
			</p>
			<p>
				For questions about this application or our data practices, please contact us at
				<a href="mailto:info@dogo-trading.com">info@dogo-trading.com</a>.
			</p>
		</div>
	</div>
</section>
