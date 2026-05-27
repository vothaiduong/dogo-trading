<?php
/**
 * Contact section.
 *
 * @package DogoCorporation
 */
?>
<?php
$dogo_map_address = '福岡市博多区豊2-2-26';
$dogo_map_lang    = function_exists( 'dogo_current_lang' ) ? dogo_current_lang() : 'ja';
$dogo_map_hl      = $dogo_map_lang === 'vi' ? 'vi' : ( $dogo_map_lang === 'en' ? 'en' : 'ja' );
$dogo_map_url     = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $dogo_map_address );
?>
<section class="section section--contact" id="contact">
	<div class="container contact__grid">
		<div class="contact__copy">
			<span class="eyebrow"><?php esc_html_e( 'Contact', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'Let\'s talk about your', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'next cross-border move.', 'dogo-corporation' ); ?></span>
			</h2>
			<p class="section__lede">
				<?php esc_html_e( 'Wholesale buyers, brand partners and proxy customers — we\'d love to hear from you.', 'dogo-corporation' ); ?>
			</p>

			<ul class="contact__channels">
				<li>
					<span class="contact__label"><?php esc_html_e( 'Email', 'dogo-corporation' ); ?></span>
					<a href="mailto:info@dogo-trading.com">info@dogo-trading.com</a>
				</li>
				<li>
					<span class="contact__label"><?php esc_html_e( 'Headquarters', 'dogo-corporation' ); ?></span>
					<span><?php esc_html_e( 'Fukuoka, Japan', 'dogo-corporation' ); ?></span>
				</li>
				<li>
					<span class="contact__label"><?php esc_html_e( 'Address', 'dogo-corporation' ); ?></span>
					<span><?php esc_html_e( '2-2-26 Yutaka, Hakata-ku, Fukuoka', 'dogo-corporation' ); ?></span>
				</li>
				<li>
					<span class="contact__label"><?php esc_html_e( 'Languages', 'dogo-corporation' ); ?></span>
					<span><?php esc_html_e( 'English · Tiếng Việt · 日本語', 'dogo-corporation' ); ?></span>
				</li>
			</ul>
		</div>

		<form class="contact__form"
			action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
			method="post"
			data-dogo-contact
			novalidate>
			<input type="hidden" name="action" value="dogo_contact" />
			<input type="hidden" name="_dogo_nonce" value="<?php echo esc_attr( wp_create_nonce( 'dogo_contact' ) ); ?>" />
			<!-- Honeypot — keep this empty; bots fill it. -->
			<div class="form-honeypot" aria-hidden="true">
				<label>Website<input type="text" name="website" tabindex="-1" autocomplete="off" /></label>
			</div>

			<div class="form-row">
				<label class="form-field">
					<span><?php esc_html_e( 'Name', 'dogo-corporation' ); ?></span>
					<input type="text" name="name" required />
				</label>
				<label class="form-field">
					<span><?php esc_html_e( 'Your company', 'dogo-corporation' ); ?></span>
					<input type="text" name="company" />
				</label>
			</div>
			<label class="form-field">
				<span><?php esc_html_e( 'Email', 'dogo-corporation' ); ?></span>
				<input type="email" name="email" required />
			</label>
			<label class="form-field">
				<span><?php esc_html_e( 'Message', 'dogo-corporation' ); ?></span>
				<textarea name="message" rows="6" required minlength="10"></textarea>
			</label>
			<div class="form-captcha"
				data-sitekey="<?php echo esc_attr( dogo_turnstile_sitekey() ); ?>"
				data-action="contact"
				aria-label="<?php esc_attr_e( 'Captcha challenge', 'dogo-corporation' ); ?>"></div>

			<button type="submit" class="btn btn--primary">
				<span class="btn__label"><?php esc_html_e( 'Send message', 'dogo-corporation' ); ?></span>
				<?php dogo_icon( 'arrow-right' ); ?>
			</button>
			<p class="form-note muted">
				<?php esc_html_e( 'Protected by Cloudflare Turnstile. We respond within one business day.', 'dogo-corporation' ); ?>
			</p>

			<?php
			// Non-JS fallback: surface the redirect query flag if present.
			$flag = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : '';
			if ( $flag === 'sent' ) {
				echo '<div class="form-status form-status--ok" role="status">' . esc_html__( 'Thank you. We\'ll be in touch within one business day.', 'dogo-corporation' ) . '</div>';
			} elseif ( $flag === 'error' ) {
				echo '<div class="form-status form-status--err" role="alert">' . esc_html__( 'Sorry, something went wrong. Please try again or email us directly at info@dogo-trading.com.', 'dogo-corporation' ) . '</div>';
			} else {
				echo '<div class="form-status" role="status" hidden></div>';
			}
			?>
		</form>
	</div>

	<div class="container">
		<?php
		$dogo_map_src = 'https://maps.google.com/maps?q=' . rawurlencode( $dogo_map_address ) . '&hl=' . $dogo_map_hl . '&z=16&output=embed';
		?>
		<figure class="contact__map"
			data-map-src="<?php echo esc_attr( $dogo_map_src ); ?>"
			data-map-height="420"
			data-map-title="<?php esc_attr_e( 'Map to Dogo Corporation headquarters', 'dogo-corporation' ); ?>">
			<div class="contact__map-skeleton" aria-hidden="true">
				<span class="contact__pin-pulse"></span>
				<?php echo esc_html( $dogo_map_address ); ?>
			</div>
			<figcaption class="contact__map-caption">
				<div>
					<span class="contact__label"><?php esc_html_e( 'Headquarters', 'dogo-corporation' ); ?></span>
					<strong><?php echo esc_html( $dogo_map_address ); ?></strong>
				</div>
				<a class="btn btn--ghost btn--sm" href="<?php echo esc_url( $dogo_map_url ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Open in Google Maps', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-up' ); ?>
				</a>
			</figcaption>
		</figure>
	</div>
</section>
