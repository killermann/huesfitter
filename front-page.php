<?php
/**
 * Outfitter Pro.
 *
 * This file adds the front page to the Outfitter Pro Theme.
 *
 * @package Outfitter
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/outfitter/
 */

add_action( 'genesis_meta', 'outfitter_front_page_genesis_meta' );

/**
 * Adds widget support for homepage. If no widgets active, displays the default loop.
 *
 * @since 1.0.0
 */
function outfitter_front_page_genesis_meta() {

	if ( is_active_sidebar( 'front-page-1' ) || is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3' ) || ( class_exists( 'WooCommerce' ) && current_theme_supports( 'woocommerce' ) && get_posts( 'post_type=product&posts_per_page=1' ) ) ) {

		// Enqueues scripts.
		add_action( 'wp_enqueue_scripts', 'outfitter_enqueue_front_script_styles' );

		// Removes the full-with-narrow body class.
		remove_filter( 'body_class', 'outfitter_narrow_body_class' );

		// Adds the front-page body class.
		add_filter( 'body_class', 'outfitter_body_class' );

		// Forces full width content layout.
		add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

		// Removes breadcrumbs.
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

		// Removes the default Genesis loop.
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Adds front page widgets.
		add_action( 'genesis_loop', 'outfitter_front_page_widgets' );

	}

}

/**
 * Defines the front page scripts and styles.
 *
 * @since 1.0.0
 */
function outfitter_enqueue_front_script_styles() {

	wp_enqueue_style( 'outfitter-front-styles', get_stylesheet_directory_uri() . '/style-front.css' );
	wp_enqueue_script( 'jquery-masonry', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'outfitter-masonry-args', get_stylesheet_directory_uri() . '/js/masonry-args.js', array( 'jquery-masonry', 'jquery' ), '1.0.0', true );

}

/**
 * Defines the front-page body class.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function outfitter_body_class( $classes ) {

	$classes[] = 'front-page masonry-page';
	return $classes;

}

/**
 * Adds markup for front page widgets areas.
 *
 * @since 1.0.0
 */
function outfitter_front_page_widgets() {

	echo '<h2 class="screen-reader-text">' . esc_html__( 'Main Content', 'outfitter-pro' ) . '</h2>';

	genesis_widget_area(
		'front-page-1', array(
			'before' => '<div id="front-page-1" class="front-page-1"><div class="flexible-widgets widget-area clearfix' . outfitter_widget_area_class( 'front-page-1' ) . '">',
			'after'  => '</div></div>',
		)
	); ?>

	<section id="home-mailing-list" class="skew hero black-bg text-white text-center bigpad">
		<div class="skew-inner">
			<h2 class="yellow">Get a tour of hues</h2>
			<p>Join sK's mailing list and he'll email you about hues projects &mdash; past and future.</p>
			<a class="button button-cta"><span>Join the List</span></a>
		</div>
	</section>

	<section id="home-featured">
		<h2 class="bento-subtitle">Featured hues</h2>

		<?php $featuredQuery = new WP_Query(
			array(
				'posts_per_page' => 6,
				'category_name' => 'featured',
			)
		); ?>
		<div class="flexible-widgets widget-area clearfix">
			<div class="widget widget_text">
				<div class="woocommerce columns-4 ">
					<ul class="products columns-4">
						<?php while($featuredQuery->have_posts()) : $featuredQuery->the_post();?>
						<li class="entry product type-post">
							<a href="<?php the_permalink() ?>" rel="bookmark" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
								<?php the_post_thumbnail('big_square');?>

								<h2 class="woocommerce-loop-product__title">
									<?php the_title();?>
								</h2>
							</a>
						</li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<?php

	if ( is_active_sidebar( 'front-page-2' ) ) {

		genesis_widget_area(
			'front-page-2', array(
				'before' => '<div id="front-page-2" class="front-page-2"><div class="flexible-widgets widget-area clearfix' . outfitter_widget_area_class( 'front-page-2' ) . '">',
				'after'  => '</div></div>',
			)
		);

	} else {

		if ( class_exists( 'WooCommerce' ) && current_theme_supports( 'woocommerce' ) && get_posts( 'post_type=product&posts_per_page=1' ) ) {

			echo '<div id="front-page-2" class="front-page-2"><h2 class="bento-subtitle">In the Store</h2><div class="flexible-widgets widget-area clearfix"><div class="widget widget_text">' . do_shortcode( '[recent_products per_page="9"]' ) . '</div></div></div>';

		}

	}

	genesis_widget_area(
		'front-page-3', array(
			'before' => '<div id="front-page-3" class="front-page-3"><div class="flexible-widgets widget-area clearfix' . outfitter_widget_area_class( 'front-page-3' ) . '">',
			'after'  => '</div></div>',
		)
	);

}

// Runs the Genesis loop.
genesis();
