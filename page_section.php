<?php
/**
 *
 *
 * Template Name: Section Page
 *
 * Description: Display a section of site content
 *
 */

add_action( 'genesis_meta', 'outfitter_section_page_genesis_meta' );


function outfitter_section_page_genesis_meta() {

	// Enqueues scripts.
	add_action( 'wp_enqueue_scripts', 'outfitter_enqueue_section_page_scripts_styles' );

	// Removes the full-with-narrow body class.
	remove_filter( 'body_class', 'outfitter_narrow_body_class' );

	// Adds the front-page body class.
	add_filter( 'body_class', 'outfitter_body_class' );

	// Removes breadcrumbs.
	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

	// Forces full width content layout.
	add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

	// Add the custom loop
	add_action('genesis_loop','custom_template_meta_loop');
}

function outfitter_enqueue_section_page_scripts_styles() {

	wp_enqueue_style( 'outfitter-front-styles', get_stylesheet_directory_uri() . '/style-front.css' );
	wp_enqueue_script( 'jquery-masonry', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'outfitter-masonry-args', get_stylesheet_directory_uri() . '/js/masonry-args.js', array( 'jquery-masonry', 'jquery' ), '1.0.0', true );

}

function outfitter_body_class( $classes ) {
	$classes[] = 'section masonry-page';
	return $classes;
}

function custom_template_meta_loop() {?>

	<section class="section-loop">

		<?php $section = get_the_title();

		$sectionQuery = new WP_Query(
			array(
				'orderby' => 'title',
				'order' => 'ASC',
				'posts_per_page'=> '-1',
				'tax_query' => array( //getting all posts in a custom taxonomy
					array(
						'taxonomy' => 'sections', //name of tax
						'field' => 'slug', //can be by slug, name, or id
						'terms' => $section, //the slug of the custom tax
					),
				),
			)
		); ?>
		<div class="woocommerce columns-4 ">
			<ul class="products columns-4">
				<?php while($sectionQuery->have_posts()) : $sectionQuery->the_post();?>
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
	</section>
<?php }

// Runs the Genesis loop.
genesis();
