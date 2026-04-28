<?php
/**
 * Template for the /privacy/ page.
 *
 * @package DogoCorporation
 */

$c = dogo_company_info();
get_header(); ?>

<section class="section section--page section--privacy">
	<div class="container container--narrow">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'Legal', 'dogo-corporation' ); ?></span>
			<h1 class="section__title"><?php esc_html_e( 'Privacy Policy', 'dogo-corporation' ); ?></h1>
			<p class="section__lede">
				<?php
				printf(
					/* translators: %s — company legal name */
					esc_html__( '%s ("the Company") values the privacy of every visitor and customer. This policy explains what personal information we collect, how we use it, and the choices you have.', 'dogo-corporation' ),
					'<strong>' . esc_html( $c['name_ja'] ) . '（' . esc_html( $c['name_en'] ) . '）</strong>'
				);
				?>
			</p>
		</header>

		<article class="prose privacy">
			<h2><?php esc_html_e( '1. Information we collect', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'When you contact us, place an order, or use our services, we may collect: your name, company name, email address, phone number, shipping/billing address, payment information, and the content of your inquiries. We also collect technical data automatically (IP address, browser type, referring URL, pages visited, timestamps) for security and analytics.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '2. Purpose of use', 'dogo-corporation' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Responding to inquiries and providing requested services', 'dogo-corporation' ); ?></li>
				<li><?php esc_html_e( 'Processing orders, payments and shipments', 'dogo-corporation' ); ?></li>
				<li><?php esc_html_e( 'Cross-border customs handling and partner communication', 'dogo-corporation' ); ?></li>
				<li><?php esc_html_e( 'Customer support, after-sales service and dispute resolution', 'dogo-corporation' ); ?></li>
				<li><?php esc_html_e( 'Service improvement, fraud prevention and legal compliance', 'dogo-corporation' ); ?></li>
				<li><?php esc_html_e( 'Marketing communications, only with your prior consent', 'dogo-corporation' ); ?></li>
			</ul>

			<h2><?php esc_html_e( '3. Disclosure to third parties', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'We do not sell your personal information. We may share necessary data with: payment processors, shipping/customs partners, manufacturer or marketplace partners required to fulfil your order, and authorities when required by law. All partners are bound by confidentiality obligations consistent with this policy.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '4. Outsourcing & sub-processors', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'We may entrust limited processing (hosting, email, analytics, customer-support tooling) to qualified vendors. We require these vendors to maintain security standards equivalent to ours.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '5. Cookies & analytics', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'We use cookies and similar technologies to remember your language preference, keep your session secure, and measure aggregate site usage. You can disable cookies in your browser, but some features (e.g. language switching) may not work correctly without them.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '6. Your rights', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'You may request access to, correction of, or deletion of your personal information at any time. You may also withdraw consent for marketing communications. To exercise these rights, contact us using the details below.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '7. Data retention & security', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'We retain personal information only for as long as necessary to provide our services and meet legal obligations. We apply reasonable administrative, technical and physical safeguards to protect your data.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '8. International transfer', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'As a cross-border eCommerce operator we transfer data internationally to fulfil shipments and provide support. We take steps to ensure such transfers are protected under contractual safeguards.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '9. Updates to this policy', 'dogo-corporation' ); ?></h2>
			<p><?php esc_html_e( 'We may revise this policy from time to time. The revised version will be posted on this page with an updated effective date.', 'dogo-corporation' ); ?></p>

			<h2><?php esc_html_e( '10. Contact', 'dogo-corporation' ); ?></h2>
			<address class="privacy__contact">
				<strong><?php echo esc_html( $c['name_ja'] ); ?></strong> （<?php echo esc_html( $c['name_en'] ); ?>）<br>
				<?php echo esc_html( $c['postal'] ); ?> <?php echo esc_html( $c['address_ja'] ); ?><br>
				<a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a>
			</address>

			<p class="muted">
				<?php
				printf(
					/* translators: %s — last updated date (Y-m-d) */
					esc_html__( 'Effective date: %s', 'dogo-corporation' ),
					esc_html( gmdate( 'Y-m-d' ) )
				);
				?>
			</p>
		</article>
	</div>
</section>

<?php get_footer();
