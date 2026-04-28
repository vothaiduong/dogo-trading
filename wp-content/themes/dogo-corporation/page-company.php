<?php
/**
 * Template for the /company/ page.
 *
 * Auto-bound when a WP page with slug "company" exists.
 *
 * @package DogoCorporation
 */

$c = dogo_company_info();
get_header(); ?>

<section class="section section--page section--company">
	<div class="container container--narrow">
		<header class="section__head text-center">
			<span class="eyebrow"><?php esc_html_e( 'Company profile', 'dogo-corporation' ); ?></span>
			<h1 class="section__title">
				<span class="gradient-text"><?php echo esc_html( $c['name_ja'] ); ?></span>
			</h1>
			<p class="section__lede"><?php echo esc_html( $c['name_legal'] ); ?> · <?php echo esc_html( $c['name_en'] ); ?></p>
		</header>

		<dl class="profile">
			<div class="profile__row">
				<dt><?php esc_html_e( 'Trade name', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['name_ja'] ); ?>（<?php echo esc_html( $c['name_en'] ); ?>）</dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Founded', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['founded'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Representative', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['representative'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Capital', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['capital'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Corporate number', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['reg_code'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Employees', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['employees'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Address', 'dogo-corporation' ); ?></dt>
				<dd>
					<?php echo esc_html( $c['postal'] ); ?> <?php echo esc_html( $c['address_ja'] ); ?><br>
					<span class="muted"><?php echo esc_html( $c['address_en'] ); ?></span>
				</dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Business hours', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['hours'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Lines of business', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['business'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Phone', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['phone'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Email', 'dogo-corporation' ); ?></dt>
				<dd><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Website', 'dogo-corporation' ); ?></dt>
				<dd><a href="<?php echo esc_url( $c['website'] ); ?>"><?php echo esc_html( $c['website'] ); ?></a></dd>
			</div>
		</dl>

		<h2 class="profile__subhead"><?php esc_html_e( 'Bank account', 'dogo-corporation' ); ?></h2>
		<p class="muted profile__bank-note"><?php esc_html_e( 'For wholesale and B2B partners — please contact us before sending payment.', 'dogo-corporation' ); ?></p>
		<dl class="profile profile--bank">
			<div class="profile__row">
				<dt><?php esc_html_e( 'Bank', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['bank']['name'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Branch', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['bank']['branch'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Account type', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['bank']['type'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Account number', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['bank']['account'] ); ?></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Account holder', 'dogo-corporation' ); ?></dt>
				<dd><?php echo esc_html( $c['bank']['holder'] ); ?></dd>
			</div>
		</dl>

		<div class="text-center" style="margin-top:48px;">
			<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn--primary">
				<?php esc_html_e( 'Contact our team', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
			</a>
		</div>
	</div>
</section>

<?php get_footer();
