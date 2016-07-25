<?php
/**
 * Custom helper functions for this theme.
 *
 * @package Primer
 */

/**
 * Return the current layout.
 *
 * @global string $primer_customizer_layouts
 * @since  1.0.0
 *
 * @param  int $post_id (optional)
 *
 * @return string
 */
function primer_get_layout( $post_id = null ) {

	global $primer_customizer_layouts;

	return $primer_customizer_layouts->get_current_layout( $post_id );

}

/**
 * Return the global layout.
 *
 * @global string $primer_customizer_layouts
 * @since 1.0.0
 *
 * @return string
 */
function primer_get_global_layout() {

	global $primer_customizer_layouts;

	return $primer_customizer_layouts->get_global_layout();

}

/**
 * Return the featured image URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function primer_get_featured_image_url() {

	$url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'featured' );
	$url = empty( $url[0] ) ? null : $url;

	/**
	 * Filter the featured image URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	return (string) apply_filters( 'primer_featured_image_url', $url );

}

/**
 * Return the posts page URL.
 *
 * In the event a custom homepage exists, we need
 * to find the posts page and return its URL.
 *
 * @since 1.0.0
 *
 * @return string
 */
function primer_get_posts_url() {

	$url = ( 'page' === get_option( 'show_on_front' ) ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : null;

	/**
	 * Filter the posts page URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	return (string) apply_filters( 'primer_posts_url', $url );

}

/**
 * Return the number of active footer widget areas.
 *
 * @global array $wp_registered_sidebars
 * @since  1.0.0
 *
 * @return int
 */
function primer_active_footer_areas_count() {

	global $wp_registered_sidebars;

	$count    = 0;
	$sidebars = preg_grep( '/^footer-(.*)/', array_keys( $wp_registered_sidebars ) );

	foreach ( $sidebars as $sidebar ) {

		if ( is_active_sidebar( $sidebar ) ) {

			$count++;

		}

	}

	/**
	 * Filter the number of active footer widget areas.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	return (int) apply_filters( 'primer_active_footer_areas_count', $count );

}

/**
 * Check if the site has active categories.
 *
 * We will store the result in a transient so this function
 * can be called frequently without any performance concern.
 *
 * @see   primer_has_active_categories_reset()
 * @since 1.0.0
 *
 * @return bool
 */
function primer_has_active_categories() {

	if ( WP_DEBUG || false === ( $has_active_categories = get_transient( 'primer_has_active_categories' ) ) ) {

		$categories = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				'number'     => 2, // We only care if more than 1 exists
			)
		);

		$has_active_categories = ( count( $categories ) > 1 );

		set_transient( 'primer_has_active_categories', $has_active_categories );

	}

	/**
	 * Filter if the site has active categories.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	return (bool) apply_filters( 'primer_has_active_categories', ! empty( $has_active_categories ) );

}

/**
 * Convert a 3- or 6-digit hexadecimal color to an associative RGB array.
 *
 * @since 1.0.0
 *
 * @param  string $color
 *
 * @return array
 */
function primer_hex2rgb( $color ) {

	$color = trim( $color, '#' );

	switch ( strlen( $color ) ) {

		case 3 :

			$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
			$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
			$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );

			break;

		case 6 :

			$r = hexdec( substr( $color, 0, 2 ) );
			$g = hexdec( substr( $color, 2, 2 ) );
			$b = hexdec( substr( $color, 4, 2 ) );

			break;

		default :

			return array();

	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );

}