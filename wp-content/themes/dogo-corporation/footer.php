<?php
/**
 * Site footer.
 *
 * @package DogoCorporation
 */
?>
</main><!-- #main -->

<footer class="site-footer" id="site-footer">
	<div class="container site-footer__top">
		<div class="site-footer__brand">
			<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img class="brand-logo brand-logo--dark" src="<?php echo esc_url( dogo_img( 'logo-dark.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="40" />
				<img class="brand-logo brand-logo--light" src="<?php echo esc_url( dogo_img( 'logo-light.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="40" />
			</a>
			<p class="site-footer__tagline">
				<?php esc_html_e( 'Shaping excellence in cross-border eCommerce. Authentic Japanese products, delivered worldwide.', 'dogo-corporation' ); ?>
			</p>
			<?php dogo_language_switcher(); ?>
		</div>

		<div class="site-footer__cols">
			<div class="site-footer__col">
				<h4 class="site-footer__title"><?php esc_html_e( 'Services', 'dogo-corporation' ); ?></h4>
				<ul>
					<?php foreach ( dogo_services() as $svc ) : ?>
						<li><a href="#service-<?php echo esc_attr( $svc['key'] ); ?>"><?php echo esc_html( $svc['title'] ); ?></a></li>
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
					<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'dogo-corporation' ); ?></a></li>
				</ul>
			</div>

			<div class="site-footer__col">
				<h4 class="site-footer__title"><?php esc_html_e( 'Legal', 'dogo-corporation' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'dogo-corporation' ); ?></a></li>
				</ul>
			</div>

			<div class="site-footer__col site-footer__col--contact">
				<h4 class="site-footer__title"><?php esc_html_e( 'Get in touch', 'dogo-corporation' ); ?></h4>
				<ul>
					<li><a href="mailto:info@dogo-trading.com">info@dogo-trading.com</a></li>
					<li><span><?php esc_html_e( 'Fukuoka, Japan', 'dogo-corporation' ); ?></span></li>
					<li><span class="muted"><?php esc_html_e( '2-2-26 Yutaka, Hakata-ku, Fukuoka', 'dogo-corporation' ); ?></span></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container site-footer__bottom">
		<p class="site-footer__copy">© <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'dogo-corporation' ); ?></p>
		<p class="site-footer__credit"><?php esc_html_e( 'Crafted for cross-border excellence.', 'dogo-corporation' ); ?></p>
	</div>

	<button class="back-to-top" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'dogo-corporation' ); ?>">
		<?php dogo_icon( 'arrow-up' ); ?>
	</button>
</footer>

<?php wp_footer(); ?>
</body>
</html>
