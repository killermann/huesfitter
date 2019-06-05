/**
 * This script adds the masonry jquery arguments to the front page of the Outfitter Pro Theme.
 *
 * @package Outfitter\JS
 * @author StudioPress
 * @license GPL-2.0+
 */

(function( $ ) {

	$( document ).ready( function() {

		// Front page WooCommerce widget.
		var $products = $( '.masonry-page .content .woocommerce ul.products' );
		var $grid = $products.masonry({
			columnWidth: '.product:nth-child(2)',
			horizontalOrder: true,
			percentPosition: true
		});

		// Grid layout.
		$grid.imagesLoaded().progress( function() {
			$grid.masonry( 'layout' );
		});

	});

})(jQuery);
