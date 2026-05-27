<?php
/**
 * Site header.
 *
 * @package DogoCorporation
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script>
	/* Theme bootstrap — runs before paint to avoid FOUC. Default = dark. */
	(function () {
		try {
			var saved = localStorage.getItem('dogo-theme');
			var theme = saved === 'light' || saved === 'dark' ? saved : 'dark';
			document.documentElement.setAttribute('data-theme', theme);
		} catch (e) {
			document.documentElement.setAttribute('data-theme', 'dark');
		}
	})();
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'dogo-corporation' ); ?></a>

<div class="site-bg" aria-hidden="true">
	<div class="site-bg__grain"></div>
</div>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				$alt = esc_attr( get_bloginfo( 'name' ) );
				echo '<img class="brand-logo brand-logo--dark" src="' . esc_url( dogo_img( 'logo-dark.png' ) ) . '" alt="' . $alt . '" width="140" height="40" />';
				echo '<img class="brand-logo brand-logo--light" src="' . esc_url( dogo_img( 'logo-light.png' ) ) . '" alt="' . $alt . '" width="140" height="40" />';
			}
			?>
			<span class="site-header__brand-text">
				<span class="site-header__brand-name"><?php bloginfo( 'name' ); ?></span>
			</span>
		</a>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'dogo-corporation' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'site-nav__list',
				'fallback_cb'    => 'dogo_default_menu',
				'depth'          => 2,
			) );
			?>
		</nav>

		<div class="site-header__actions">
			<button class="theme-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle color theme', 'dogo-corporation' ); ?>" title="<?php esc_attr_e( 'Toggle color theme', 'dogo-corporation' ); ?>">
				<span class="theme-toggle__icon theme-toggle__icon--sun"><?php dogo_icon( 'sun' ); ?></span>
				<span class="theme-toggle__icon theme-toggle__icon--moon"><?php dogo_icon( 'moon' ); ?></span>
			</button>
			<?php dogo_language_switcher(); ?>
			<a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>" class="btn btn--primary btn--sm"><?php esc_html_e( 'Contact', 'dogo-corporation' ); ?> <?php dogo_icon( 'arrow-right' ); ?></a>
			<button class="site-header__toggle" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'dogo-corporation' ); ?>">
				<?php dogo_icon( 'menu' ); ?>
			</button>
		</div>
	</div>
</header>

<?php
function dogo_default_menu() {
	$home = home_url( '/' );
	$items = array(
		array( 'url' => $home . '#about',    'label' => __( 'About', 'dogo-corporation' ) ),
		array( 'url' => $home . '#services', 'label' => __( 'Services', 'dogo-corporation' ) ),
		array( 'url' => $home . '#work',     'label' => __( 'Our Work', 'dogo-corporation' ) ),
		array( 'url' => home_url( '/company/' ),  'label' => __( 'Company profile', 'dogo-corporation' ) ),
		array( 'url' => home_url( '/life/' ),     'label' => __( 'Life at Dogo', 'dogo-corporation' ) ),
		array( 'url' => home_url( '/careers/' ), 'label' => __( 'Careers', 'dogo-corporation' ) ),
	);
	echo '<ul class="site-nav__list">';
	foreach ( $items as $it ) {
		printf( '<li class="site-nav__item"><a class="site-nav__link" href="%s">%s</a></li>', esc_url( $it['url'] ), esc_html( $it['label'] ) );
	}
	echo '</ul>';
}
?>

<main id="main" class="site-main" tabindex="-1">
