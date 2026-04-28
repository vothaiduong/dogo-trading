<?php
/**
 * Services section.
 *
 * @package DogoCorporation
 */
$services = dogo_services();
?>
<section class="section section--services" id="services">
	<div class="container">
		<header class="section__head">
			<span class="eyebrow"><?php esc_html_e( 'What we do', 'dogo-corporation' ); ?></span>
			<h2 class="section__title">
				<?php esc_html_e( 'Four specialized lines.', 'dogo-corporation' ); ?>
				<span class="gradient-text"><?php esc_html_e( 'One trusted gateway.', 'dogo-corporation' ); ?></span>
			</h2>
			<p class="section__lede">
				<?php esc_html_e( 'Each service is purpose-built for a community that values authenticity. Powered by cross-border trade infrastructure proven since 2020.', 'dogo-corporation' ); ?>
			</p>
		</header>

		<div class="services">
			<?php foreach ( $services as $i => $svc ) : ?>
				<article id="service-<?php echo esc_attr( $svc['key'] ); ?>" class="service service--<?php echo esc_attr( $svc['key'] ); ?>">
					<div class="service__media" style="background-image:url('<?php echo esc_url( dogo_img( $svc['image'] ) ); ?>');" role="img" aria-label="<?php echo esc_attr( $svc['title'] ); ?>"></div>
					<div class="service__body">
						<span class="service__tag"><?php echo esc_html( $svc['tag'] ); ?></span>
						<h3 class="service__title"><?php echo esc_html( $svc['title'] ); ?></h3>
						<p class="service__desc"><?php echo esc_html( $svc['desc'] ); ?></p>
						<ul class="service__features">
							<?php foreach ( $svc['features'] as $feature ) : ?>
								<li><?php dogo_icon( 'check' ); ?> <?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
						<a class="service__more" href="#contact">
							<?php esc_html_e( 'Discuss this line', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
