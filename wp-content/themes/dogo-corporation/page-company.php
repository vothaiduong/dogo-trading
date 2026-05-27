<?php
/**
 * Template for the /company/ page (会社概要).
 *
 * @package DogoCorporation
 */

$c     = dogo_company_info();
$lines = isset( $c['business_lines'] ) ? dogo_jl( $c['business_lines'] ) : array();
$banks = isset( $c['banks'] ) ? $c['banks'] : array();

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
				<dd><code class="profile__code"><?php echo esc_html( $c['reg_code'] ); ?></code></dd>
			</div>
			<?php if ( ! empty( $c['license'] ) ) : ?>
				<div class="profile__row">
					<dt><?php esc_html_e( 'License & registration', 'dogo-corporation' ); ?></dt>
					<dd><?php echo esc_html( $c['license'] ); ?></dd>
				</div>
			<?php endif; ?>
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
			<?php if ( ! empty( $lines ) ) : ?>
				<div class="profile__row">
					<dt><?php esc_html_e( 'Lines of business', 'dogo-corporation' ); ?></dt>
					<dd>
						<ul class="profile__list">
							<?php foreach ( $lines as $line ) : ?>
								<li><?php echo esc_html( $line ); ?></li>
							<?php endforeach; ?>
						</ul>
					</dd>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $banks ) ) : ?>
				<div class="profile__row">
					<dt><?php esc_html_e( 'Trading banks', 'dogo-corporation' ); ?></dt>
					<dd>
						<ul class="profile__list">
							<?php foreach ( $banks as $bank ) : ?>
								<li><?php echo esc_html( $bank ); ?></li>
							<?php endforeach; ?>
						</ul>
					</dd>
				</div>
			<?php endif; ?>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Email', 'dogo-corporation' ); ?></dt>
				<dd><a href="mailto:<?php echo esc_attr( $c['email'] ); ?>"><?php echo esc_html( $c['email'] ); ?></a></dd>
			</div>
			<div class="profile__row">
				<dt><?php esc_html_e( 'Website', 'dogo-corporation' ); ?></dt>
				<dd><a href="<?php echo esc_url( $c['website'] ); ?>"><?php echo esc_html( $c['website'] ); ?></a></dd>
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
