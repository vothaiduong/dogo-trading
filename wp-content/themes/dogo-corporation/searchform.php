<?php
/**
 * Search form.
 *
 * @package DogoCorporation
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="s"><?php esc_html_e( 'Search', 'dogo-corporation' ); ?></label>
	<input type="search" id="s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'dogo-corporation' ); ?>" />
	<button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Search', 'dogo-corporation' ); ?></button>
</form>
