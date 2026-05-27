<?php
/**
 * Site footer.
 *
 * @package DogoCorporation
 */
$dogo_year = date( 'Y' );
?>
</main><!-- #main -->

<footer class="site-footer" id="site-footer">
	<div class="site-footer__bg" aria-hidden="true"></div>

	<div class="container site-footer__inner">

		<div class="site-footer__top">
			<div class="site-footer__brand">
				<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img class="brand-logo brand-logo--dark"  src="<?php echo esc_url( dogo_img( 'logo-dark.png' ) ); ?>"  alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="40" />
					<img class="brand-logo brand-logo--light" src="<?php echo esc_url( dogo_img( 'logo-light.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="40" />
				</a>
				<p class="site-footer__tagline">
					<?php esc_html_e( 'Shaping excellence in cross-border eCommerce. Authentic Japanese products, delivered worldwide.', 'dogo-corporation' ); ?>
				</p>
				<div class="site-footer__status" role="status">
					<span class="status-dot" aria-hidden="true"></span>
					<span><?php esc_html_e( 'All systems operational · shipping to 180+ countries', 'dogo-corporation' ); ?></span>
				</div>
				<?php dogo_language_switcher(); ?>
			</div>

			<nav class="site-footer__cols" aria-label="<?php esc_attr_e( 'Footer navigation', 'dogo-corporation' ); ?>">
				<div class="site-footer__col">
					<h4 class="site-footer__title"><?php esc_html_e( 'Services', 'dogo-corporation' ); ?></h4>
					<ul>
						<?php foreach ( dogo_services() as $svc ) : ?>
							<li><a href="<?php echo esc_url( home_url( '/#service-' . $svc['key'] ) ); ?>"><?php echo esc_html( $svc['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>

				<div class="site-footer__col">
					<h4 class="site-footer__title"><?php esc_html_e( 'Company', 'dogo-corporation' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/company/' ) ); ?>"><?php esc_html_e( 'Company profile', 'dogo-corporation' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Careers', 'dogo-corporation' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/life/' ) ); ?>"><?php esc_html_e( 'Life at Dogo', 'dogo-corporation' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"><?php esc_html_e( 'About us', 'dogo-corporation' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#work' ) ); ?>"><?php esc_html_e( 'Our work', 'dogo-corporation' ); ?></a></li>
					</ul>
				</div>

				<div class="site-footer__col">
					<h4 class="site-footer__title"><?php esc_html_e( 'Resources', 'dogo-corporation' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'dogo-corporation' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'dogo-corporation' ); ?></a></li>
					</ul>
				</div>

				<div class="site-footer__col">
					<h4 class="site-footer__title"><?php esc_html_e( 'Connect', 'dogo-corporation' ); ?></h4>
					<ul>
						<li>
							<a class="site-footer__email" href="mailto:info@dogo-trading.com">
								<span class="site-footer__email-label"><?php esc_html_e( 'Email', 'dogo-corporation' ); ?></span>
								info@dogo-trading.com
							</a>
						</li>
						<li>
							<span class="site-footer__email-label"><?php esc_html_e( 'Hours', 'dogo-corporation' ); ?></span>
							<span class="muted"><?php esc_html_e( 'Mon–Fri 9:00–18:00 JST', 'dogo-corporation' ); ?></span>
						</li>
					</ul>
				</div>
			</nav>
		</div>

		<div class="site-footer__address">
			<div class="site-footer__address-card">
				<span class="site-footer__pin" aria-hidden="true">〒</span>
				<div>
					<strong><?php esc_html_e( 'Headquarters', 'dogo-corporation' ); ?></strong>
					<span><?php esc_html_e( '812-0006 福岡県福岡市博多区豊 2-2-26', 'dogo-corporation' ); ?></span>
				</div>
			</div>
			<a class="site-footer__map-link" href="https://www.google.com/maps/search/?api=1&query=<?php echo rawurlencode( '福岡市博多区豊2-2-26' ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Open in Google Maps', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-up' ); ?>
			</a>
		</div>

		<div class="site-footer__art" aria-hidden="true">
			<span>DOGO</span>
		</div>

		<div class="site-footer__bottom">
			<div class="site-footer__bottom-left">
				<span>© <?php echo esc_html( $dogo_year ); ?> 株式会社 DOGO · Dogo Corporation</span>
				<span class="dot-sep">·</span>
				<span><?php esc_html_e( 'All rights reserved.', 'dogo-corporation' ); ?></span>
			</div>
			<div class="site-footer__bottom-right">
				<span><?php esc_html_e( 'Crafted in Fukuoka', 'dogo-corporation' ); ?> <span class="heart" aria-hidden="true">♥</span></span>
			</div>
		</div>
	</div>

	<button class="back-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'dogo-corporation' ); ?>">
		<?php dogo_icon( 'arrow-up' ); ?>
	</button>
</footer>

<?php wp_footer(); ?>
</body>
</html>
