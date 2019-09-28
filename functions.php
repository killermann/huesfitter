<?php
/**
 * Outfitter Pro.
 *
 * This file adds functions to the Outfitter Pro Theme.
 *
 * @package Outfitter
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    https://my.studiopress.com/themes/outfitter/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'outfitter_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function outfitter_localization_setup() {
	load_child_theme_textdomain( 'outfitter-pro', get_stylesheet_directory() . '/languages' );
}

// Adds the theme helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color selector to the WordPress Theme Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Sets up the header search icon menu.
require_once get_stylesheet_directory() . '/lib/header-icon-menu.php';

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Outfitter Pro' );
define( 'CHILD_THEME_URL', 'https://my.studiopress.com/themes/outfitter/' );
define( 'CHILD_THEME_VERSION', '1.0.2' );

add_action( 'wp_enqueue_scripts', 'outfitter_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function outfitter_enqueue_scripts_styles() {

	wp_enqueue_script( 'font-awesome', '//kit.fontawesome.com/f0ac58250b.js', array(), CHILD_THEME_VERSION );

	wp_enqueue_script( 'outfitter-match-height', get_stylesheet_directory_uri() . '/js/jquery.matchHeight.min.js', array( 'jquery' ), '0.5.2', true );
	wp_enqueue_script( 'outfitter-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery', 'outfitter-match-height' ), '1.0.0', true );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'outfitter-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script( 'outfitter-responsive-menu', 'genesis_responsive_menu', outfitter_responsive_menu_settings() );

}

/**
 * Defines the responsive menu settings.
 *
 * @since 1.0.0
 */
function outfitter_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => '',
		'menuIconClass'    => 'far fa-ellipsis-h',
		'subMenu'          => __( 'Submenu', 'outfitter-pro' ),
		'subMenuIconClass' => 'far fa-chevron-down',
		
	);

	return $settings;

}

// Adds HTML5 markup structure.
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

// Adds Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Adds viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Adds support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array( 'site-inner' ) );

// Adds support for custom header.
add_theme_support(
	'custom-header', array(
		'flex-height'     => true,
		'header-selector' => '.site-title a',
		'header-text'     => false,
		'height'          => 80,
		'width'           => 400,
	)
);

// Adds support for custom background.
add_theme_support( 'custom-background' );

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds image sizes.
add_image_size( 'featured-image', 1280, 800 );
add_image_size( 'featured-image-widget', 530, 330 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'outfitter_remove_genesis_metaboxes' );
/**
 * Removes navigation meta box.
 *
 * @since 1.0.0
 *
 * @param string $_genesis_theme_settings_pagehook The page hook name.
 */
function outfitter_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {

	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );

}

add_action( 'customize_register', 'outfitter_customize_register', 16 );
/**
 * Removes unused sections from Theme Customizer.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function outfitter_customize_register( $wp_customize ) {

	$wp_customize->remove_control( 'genesis_image_alignment' );

}

add_filter( 'genesis_skip_links_output', 'outfitter_skip_links_output' );
/**
 * Removes skip link for primary navigation and adds skip link for footer widgets.
 *
 * @since 1.0.0
 *
 * @param array $links The list of skip links.
 * @return array $links The modified list of skip links.
 */
function outfitter_skip_links_output( $links ) {

	if ( isset( $links['genesis-nav-primary'] ) ) {
		unset( $links['genesis-nav-primary'] );
	}

	$new_links = $links;
	array_splice( $new_links, 3 );

	if ( is_active_sidebar( 'outfitter-footer' ) ) {
		$new_links['footer'] = __( 'Skip to footer', 'outfitter-pro' );
	}

	return array_merge( $new_links, $links );

}

// Renames primary and secondary navigation menus.
add_theme_support(
	'genesis-menus', array(
		'primary'    => __( 'Header Menu', 'outfitter-pro' ),
		'secondary'  => __( 'Footer Menu', 'outfitter-pro' ),
		'off-screen' => __( 'Off-screen Menu', 'outfitter-pro' ),
	)
);

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 13 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

remove_action( 'genesis_footer', 'genesis_do_footer' );

add_filter( 'wp_nav_menu_args', 'outfitter_limit_menu_args' );
/**
 * Reduces the secondary navigation menu to one level depth.
 *
 * @since 1.0.0
 *
 * @param array $args The WP navigation menu arguments.
 * @return array The modified menu arguments.
 */
function outfitter_limit_menu_args( $args ) {

	if ( 'off-screen' === $args['theme_location'] || 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_action( 'genesis_after_header', 'outfitter_off_screen_widget_area' );
/**
 * Adds off-screen content icon if widget area is active.
 *
 * @since 1.0.0
 */
function outfitter_off_screen_widget_area() {

	// Exits early if no menu or widget.
	if ( ! is_active_sidebar( 'off-screen-content' ) && ! has_nav_menu( 'off-screen' ) ) {
		return;
	}
	?>

	<div class="off-screen-content off-screen-widget-area">
		<div class="off-screen-container">
			<div class="off-screen-wrapper">
				<div class="wrap">
					<?php
						genesis_nav_menu( array( 'theme_location' => 'off-screen' ) );
						genesis_widget_area( 'off-screen-content' );
					?>
				</div>
			</div>
			<button class="toggle-off-screen-widget-area close"><span class="screen-reader-text"><?php echo esc_html__( 'Hide Off-screen Content', 'outfitter-pro' ); ?></span> <span class="far fa-times"></span></button>
		</div>
	</div>
	<?php

}

// Adds shortcode support for text widgets.
add_filter( 'widget_text', 'do_shortcode' );

// Repositions default archive image.
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );

// Filters the archive image markup.
add_filter( 'genesis_markup_entry-image-link_open', 'outfitter_custom_archive_image_open' );
add_filter( 'genesis_markup_entry-image-link_close', 'outfitter_custom_archive_image_close' );

add_filter( 'genesis_attr_entry-image', 'outfitter_image_alignment' );
/**
 * Sets classes for the Featured Image.
 *
 * @since 1.0.0
 *
 * @param array $attributes The image attributes.
 * @return array The modified image attributes.
 */
function outfitter_image_alignment( $attributes ) {

	$attributes['class']    = 'aligncenter post-image entry-image';
	$attributes['itemprop'] = 'image';

	return $attributes;

}

add_action( 'genesis_entry_header', 'outfitter_featured_image', 1 );
/**
 * Adds featured image above the entry content.
 *
 * @since 1.0.0
 */
function outfitter_featured_image() {

	$add_single_image = get_theme_mod( 'outfitter_single_image_setting', outfitter_customizer_get_default_image_setting() );

	$image = genesis_get_image(
		array(
			'format'  => 'html',
			'size'    => 'featured-image',
			'context' => '',
			'attr'    => array(
				'alt'   => the_title_attribute( 'echo=0' ),
				'class' => 'aligncenter',
			),
		)
	);

	if ( $add_single_image && $image && is_singular( 'post' ) ) {
		printf( '<div class="featured-image">%s</div>', $image );
	}

}

// Repositions the entry meta in the entry header.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

add_filter( 'genesis_post_info', 'outfitter_post_info_filter' );
/**
 * Modifies the meta information in the entry header.
 *
 * @since 1.0.0
 *
 * @param string $post_info Current post info.
 * @return string New post info.
 */
function outfitter_post_info_filter( $post_info ) {

	$post_info = '[post_date]';

	return $post_info;

}

add_filter( 'genesis_post_meta', 'outfitter_post_meta_filter' );
/**
 * Modifies the meta information in the entry footer.
 *
 * @since 1.0.0
 *
 * @param string $post_meta Current post meta.
 * @return string New post meta.
 */
function outfitter_post_meta_filter( $post_meta ) {

	$post_meta = '[post_tags before="<h6>Tagged</h6>"]';

	return $post_meta;

}

remove_action( 'genesis_after_entry', 'genesis_get_comments_template' );

// Repositions the entry footer and markup.
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
add_action( 'genesis_entry_header', 'genesis_post_meta', 52 );

// Repositions the author box on single posts.
remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 );
add_action( 'genesis_entry_header', 'genesis_do_author_box_single', 51 );

add_action( 'genesis_before_entry', 'outfitter_entry_wrappers' );
/**
 * Adds wrappers to the entry.
 *
 * @since 1.0.0
 */
function outfitter_entry_wrappers() {

	if ( ! is_page() ) {

		// Adds wrapper around the entry header and content, after the featured image.
		add_action( 'genesis_entry_header', 'outfitter_wrapper_open', 2 );
		add_action( 'genesis_entry_footer', 'outfitter_wrapper_close', 15 );

		// Adds wrapper around the author box and entry meta.
		add_action( 'genesis_entry_header', 'outfitter_wrapper_open', 50 );
		add_action( 'genesis_entry_header', 'outfitter_wrapper_close', 53 );

	}

	if ( ! is_archive() ) {
		// Adds wrapper around the after entry items.
		add_action( 'genesis_after_entry', 'outfitter_wrapper_open', 1 );
		add_action( 'genesis_after_entry', 'outfitter_wrapper_close', 100 );
	}

}

add_filter( 'body_class', 'outfitter_narrow_body_class' );

/**
 * Conditionally adds a class to control the content width.
 *
 * @since 1.0.1
 *
 * @param array $classes Classes array.
 * @return array $classes Updated class array.
 */
function outfitter_narrow_body_class( $classes ) {

	if ( in_array( 'full-width-content', $classes, true ) && ( ! is_page_template( 'page_blog.php' ) && is_page() ) ) {
		$classes[] = 'full-width-narrow';
	}

	return $classes;

}

add_filter( 'genesis_author_box_gravatar_size', 'outfitter_author_box_gravatar' );
/**
 * Modifies the size of the Gravatar in the author box.
 *
 * @since 1.0.0
 *
 * @param int $size Current Gravatar size.
 * @return int New size.
 */
function outfitter_author_box_gravatar( $size ) {

	return 90;

}

/** Add Child Pages **/


add_action( 'genesis_after_entry_content', 'loop_child_pages', 1 );

function loop_child_pages() {
    if (is_page()) {

        global $post;

        $args = array(
            'post_parent' => $post->ID,
            'post_type' => 'page',
            'orderby' => 'menu_order'
        );

        $child_query = new WP_Query( $args );

        echo '<div class="loop">';

        while ( $child_query->have_posts() ) : $child_query->the_post(); ?>

            <a <?php post_class('card'); ?> href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
                <h3><?php the_title(); ?></h3>
                <?php the_excerpt(); ?>
            </a>
        <?php endwhile; ?>

        <?php
        wp_reset_postdata();

        echo '</div>';
    }

}


/**
 * Counts used widgets in given sidebar.
 *
 * @since 1.0.0
 *
 * @param string $id The sidebar ID.
 * @return int|void The number of widgets, or nothing.
 */
function outfitter_count_widgets( $id ) {

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

/**
 * Outputs class names based on widget count.
 *
 * @since 1.0.0
 *
 * @param string $id The widget ID.
 * @return string The class.
 */
function outfitter_widget_area_class( $id ) {

	$count = outfitter_count_widgets( $id );

	$class = '';

	if ( 1 === $count ) {
		$class .= ' widget-full';
	} elseif ( 1 === $count % 3 ) {
		$class .= ' widget-thirds';
	} elseif ( 1 === $count % 4 ) {
		$class .= ' widget-fourths';
	} elseif ( 0 === $count % 2 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;

}

add_action( 'genesis_before_footer', 'outfitter_footer_widgets' );
/**
 * Adds the flexible footer widget area.
 *
 * @since 1.0.0
 */
function outfitter_footer_widgets() {

	genesis_widget_area(
		'outfitter-footer', array(
			'before' => '<div id="footer" class="footer-widgets"><h2 class="genesis-sidebar-title screen-reader-text">' . __( 'Footer', 'outfitter-pro' ) . '</h2><div class="flexible-widgets widget-area ' . outfitter_widget_area_class( 'outfitter-footer' ) . '"><div class="wrap">',
			'after'  => '</div></div></div>',
		)
	);

}

// Registers widget areas.
genesis_register_sidebar(
	array(
		'id'          => 'front-page-1',
		'name'        => __( 'Front Page 1', 'outfitter-pro' ),
		'description' => __( 'This is the front page 1 section.', 'outfitter-pro' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'front-page-2',
		'name'        => __( 'Front Page 2', 'outfitter-pro' ),
		'description' => __( 'This is the front page 2 section.', 'outfitter-pro' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'front-page-3',
		'name'        => __( 'Front Page 3', 'outfitter-pro' ),
		'description' => __( 'This is the front page 3 section.', 'outfitter-pro' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'outfitter-footer',
		'name'        => __( 'Footer', 'outfitter-pro' ),
		'description' => __( 'This is the footer section.', 'outfitter-pro' ),
	)
);
genesis_register_sidebar(
	array(
		'id'          => 'off-screen-content',
		'name'        => __( 'Off-Screen Content', 'outfitter-pro' ),
		'description' => __( 'This is the off-screen content section.', 'outfitter-pro' ),
	)
);

/* Custom Featured Image Size */

add_image_size( 'big_square', 630, 630, true );


/* Custom Post Types */


// let's create the function for the custom type
function updates_post_type() {
	// creating (registering) the custom type
	register_post_type( 'updates', /* (https://codex.wordpress.org/Function_Reference/register_post_type) */
	 	// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('Updates', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('Update', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All Updates', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add Update', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit Update', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New Update', 'bonestheme'), /* New Display Title */
			'view_item' => __('View Update', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search Updates', 'bonestheme'), /* Search Custom Type Title */
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'A custom post type for sharing updates.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' => 'dashicons-megaphone', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'updates', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'updates', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt')
	 	) /* end of options */
	); /* end of register post type */

}

// adding the function to the Wordpress init
add_action( 'init', 'updates_post_type');

// now let's add custom tags (these act like categories)
register_taxonomy( 'theme_tag',
	array('updates'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => false,    /* if this is false, it acts like tags */
		'labels' => array(
			'name' => __( 'Themes', 'bonestheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Theme', 'bonestheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Themes', 'bonestheme' ), /* search title for taxomony */
			'all_items' => __( 'All Themes', 'bonestheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Theme', 'bonestheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Theme:', 'bonestheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Theme', 'bonestheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Theme', 'bonestheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Theme', 'bonestheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Theme Name', 'bonestheme' ) /* name title for taxonomy */
		),
		'show_admin_column' => true,
		'show_ui' => true,
		'query_var' => true,
		'has_archive' => 'about', /* you can rename the slug here */
		'rewrite'	=> array( 'slug' => 'updates/about', 'with_front' => true ), /* you can specify its url slug */
	)
);


/*
for more information on taxonomies, go here:
https://codex.wordpress.org/Function_Reference/register_taxonomy
*/

/*
	looking for custom meta boxes?
	check out this fantastic tool:
	https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
*/

// let's create the function for the custom type
function press_clippings_post_type() {
	// creating (registering) the custom type
	register_post_type( 'press_clippings', /* (https://codex.wordpress.org/Function_Reference/register_post_type) */
	 	// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('Press Clippings', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('Press Clipping', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All Press Clippings', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add New Press Clipping', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit Press Clipping', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New Press Clipping', 'bonestheme'), /* New Display Title */
			'view_item' => __('View Press Clipping', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search Press Clippings', 'bonestheme'), /* Search Custom Type Title */
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'For adding links to other cool stuff involving me.', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 7, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' => 'dashicons-share-alt2', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'press', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'press', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'sticky')
	 	) /* end of options */
	); /* end of register post type */

}

// adding the function to the Wordpress init
add_action( 'init', 'press_clippings_post_type');

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5c1d48a35f22d',
	'title' => 'Press Clipping',
	'fields' => array(
		array(
			'key' => 'field_57539b73e311a',
			'label' => 'Article Link',
			'name' => 'article_link',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		),
		array(
			'key' => 'field_57539b7ce311b',
			'label' => 'Source',
			'name' => 'source',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'formatting' => 'html',
			'maxlength' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'press_clippings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => array(
	),
	'active' => 1,
	'description' => '',
));

endif;

// CUSTOM TAXONOMY

    register_taxonomy( 'sections',
    	array('post'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
    	array('hierarchical' => true,     /* if this is true, it acts like categories */
    		'labels' => array(
    			'name' => __( 'Sections', 'bonestheme' ), /* name of the custom taxonomy */
    			'singular_name' => __( 'Section', 'bonestheme' ), /* single taxonomy name */
    			'search_items' =>  __( 'Search Sections', 'bonestheme' ), /* search title for taxomony */
    			'all_items' => __( 'All Sections', 'bonestheme' ), /* all title for taxonomies */
    			'parent_item' => __( 'Parent Section', 'bonestheme' ), /* parent title for taxonomy */
    			'parent_item_colon' => __( 'Parent Section:', 'bonestheme' ), /* parent taxonomy title */
    			'edit_item' => __( 'Edit Section', 'bonestheme' ), /* edit custom taxonomy title */
    			'update_item' => __( 'Update Section', 'bonestheme' ), /* update title for taxonomy */
    			'add_new_item' => __( 'Add New Section', 'bonestheme' ), /* add new title for taxonomy */
    			'new_item_name' => __( 'New Section', 'bonestheme' ) /* name title for taxonomy */
    		),
    		'show_admin_column' => true,
    		'show_ui' => true,
			'show_in_rest' => true,
    		'query_var' => true,
			'rewrite' => array('slug' => 'kind'),
    	)
    );

/**
 * Registers support for Gutenberg wide images in Writy.
 */
function ultra_wide_setup() {
  add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'ultra_wide_setup' );

/************* ADS SHORTCODE *********************/

function getCredo( $atts ){
	return
	'<div class="fourcol last credoFeature">
		<blockquote>LOVE YOUR STORY</blockquote>
	</div>';
}

add_shortcode('credo', 'getCredo');
