<?php
/**
 * Outfitter Pro.
 *
 * This defines the WooCommerce functions for use in the Outfitter Pro Theme.
 *
 * @package Outfitter
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/outfitter/
 */

add_action( 'wp_enqueue_scripts', 'outfitter_woocommerce_scripts' );
/**
 * Adds WooCommerce Scripts.
 *
 * @since 1.0.0
 */
function outfitter_woocommerce_scripts() {

	if ( class_exists( 'WooCommerce' ) && current_theme_supports( 'woocommerce' ) ) {

		wp_enqueue_script( 'outfitter-woocommerce', get_stylesheet_directory_uri() . '/lib/woocommerce/js/outfitter-woocommerce.js', array( 'jquery' ), '1.0.0', true );

	}

}

add_action( 'wp_enqueue_scripts', 'outfitter_products_match_height', 99 );
/**
 * Prints an inline script to the footer to keep products the same height.
 *
 * @since 1.0.0
 */
function outfitter_products_match_height() {

	// If WooCommerce isn't active or not on a WooCommerce page, exits early.
	if ( ! class_exists( 'WooCommerce' ) || ! is_shop() && ! is_woocommerce() && ! is_cart() ) {
		return;
	}

	wp_add_inline_script( 'outfitter-match-height', "jQuery(document).ready( function() { jQuery( '.product .woocommerce-LoopProduct-link').matchHeight(); });" );

}

/**
 * Outputs the WooCommerce cart button.
 *
 * @since 1.0.0
 *
 * @return string HTML output of the Show Cart button.
 */
function outfitter_get_off_screen_cart_toggle() {

	global $woocommerce;
	$cartcount = $woocommerce->cart->cart_contents_count;

	return '<a href="#" class="toggle-off-screen-cart"><span class="screen-reader-text">' . __( 'Show Shopping Cart', 'outfitter-pro' ) . '</span> <span class="far fa-shopping-bag"></span><span class="cart-count">' . $cartcount . '</span></a>';

}

add_action( 'genesis_after_header', 'outfitter_off_screen_woocommerce_cart_output' );
/**
 * Add the Mini Cart, which is accessible by clicking the cart icon appended to the menu.
 *
 * @since 1.0.0
 *
 * @param array $args Arguments to use when fetching the mini-cart.php template.
 */
function outfitter_off_screen_woocommerce_cart_output( $args = array() ) {

	if ( class_exists( 'WooCommerce' ) && current_theme_supports( 'woocommerce' ) ) {

		$button = '<button class="toggle-off-screen-cart close"><span class="screen-reader-text">' . __( 'Hide Shopping Cart', 'outfitter-pro' ) . '</span> <span class="far fa-times"></span></button>';

		echo '<div class="off-screen-content off-screen-cart"><div class="off-screen-container"><div class="off-screen-wrapper"><div class="wrap"><section class="widget woocommerce widget_shopping_cart">';

		$defaults = array(
			'list_class' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		wc_get_template( 'cart/mini-cart.php', $args );

		echo '</section></div>' . wp_kses_post( $button ) . '</div></div></div>';

	}

}

/**
 * Output a cart icon menu item.
 *
 * @since 1.0.0
 */
function outfitter_do_woocommerce_cart_icon() {

	printf( '<li class="menu-item menu-item-has-toggle cart-item">%s</li>', wp_kses_post( outfitter_get_off_screen_cart_toggle() ) );

}

add_filter( 'post_class', 'product_has_gallery' );
/**
 * Adds product-has-gallery class to products that have a gallery.
 *
 * @since 1.0.0
 *
 * @param array $classes The original post classes.
 * @return string The updated post class list.
 */
function product_has_gallery( $classes ) {

	global $product;

	$post_type = get_post_type( get_the_ID() );

	if ( ! is_admin() ) {
		if ( 'product' === $post_type ) {
			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids ) {
				$classes[] = 'product-has-gallery';
			}
		}
	}

	return $classes;

}

add_action( 'woocommerce_before_shop_loop_item_title', 'product_woocommerce_second_product_thumbnail', 11 );
/**
 * Displays the second thumbnails for products.
 *
 * @since 1.0.0
 */
function product_woocommerce_second_product_thumbnail() {

	global $product, $woocommerce;

	$attachment_ids = $product->get_gallery_image_ids();

	if ( $attachment_ids ) {

		$secondary_image_id = $attachment_ids['0'];

		echo wp_get_attachment_image(
			$secondary_image_id, 'shop_catalog', '', array(
				'class' => 'secondary-image attachment-shop-catalog',
			)
		);

	}

}

add_filter( 'woocommerce_add_to_cart_fragments', 'outfitter_cart_count_fragments', 10, 1 );
/**
 * Add HTML fragments to be auto-updated by WooCommerce during AJAX refresh.
 *
 * @since 1.0.1
 *
 * @param array $fragments The HTML fragments. The key is the CSS selector and the value is the HTML to replace it.
 * @return array The HTML fragments including sections specific to Outfitter.
 */
function outfitter_cart_count_fragments( $fragments ) {

	$menu_cart_count_link = outfitter_get_off_screen_cart_toggle();
	$mini_cart            = wc_get_template_html(
		'cart/mini-cart.php', array(
			'list_class' => '',
		)
	);

	$fragments['.genesis-nav-menu .toggle-off-screen-cart'] = $menu_cart_count_link;
	$fragments['.off-screen-cart .widget_shopping_cart']    = '<section class="widget woocommerce widget_shopping_cart">' . $mini_cart . '</section>';

	return $fragments;

}
