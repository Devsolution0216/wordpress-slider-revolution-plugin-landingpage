<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Brand
 */

if ( ! function_exists( 'brand_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function brand_posted_on() {
	$post = get_post();

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	/*if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	} */

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: a link with the date of the post */
		esc_html_x( 'Posted on %s', 'post date', 'brand' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		/* translators: a link to the author of the post */
		esc_html_x( 'by %s', 'post author', 'brand' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $post->post_author ) ) ) . '">' . esc_html( get_the_author_meta( 'nicename', $post->post_author ) ) . '</a></span>'
	);

	if( function_exists('brand_get_default_blog') ) {
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_default_blog()
		);
		if($brand_settings['show_date'] === 'no') {
			$posted_on='';
		}
		if($brand_settings['show_author'] === 'no') {
			$byline='';
		}
	}

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'brand_show_categories' ) ) :
	function brand_show_categories() {
		if( function_exists('brand_get_default_blog') ) {
			$brand_settings = wp_parse_args(
				get_option( 'brand_settings', array() ),
				brand_get_default_blog()
			);
			if($brand_settings['show_categories'] === 'no') {
				return false;
			} else {
				return true;
			}
		}
		return true;
	}
endif;

if ( ! function_exists( 'brand_show_tags' ) ) :
	function brand_show_tags() {
		if( function_exists('brand_get_default_blog') ) {
			$brand_settings = wp_parse_args(
				get_option( 'brand_settings', array() ),
				brand_get_default_blog()
			);
			if($brand_settings['show_tags'] === 'no') {
				return false;
			} else {
				return true;
			}
		}
		return true;
	}
endif;

if ( ! function_exists( 'brand_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function brand_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'brand' ) );
		if ( $categories_list && brand_categorized_blog() && brand_show_categories() ) {
			printf( '<span class="cat-links"><i class="fa fa-folder"> </i>' . esc_html( '%1$s' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'brand' ) );
		if ( $tags_list && brand_show_tags() ) {
			printf( '<span class="tags-links"><i class="fa fa-tags"> </i>' . esc_html( '%1$s' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link"> <i class="fa fa-comment-o"> </i>';
		comments_popup_link( esc_html__( 'Leave a comment', 'brand' ), esc_html__( '1 Comment', 'brand' ), esc_html__( '% Comments', 'brand' ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'brand' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function brand_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'brand_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'brand_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so brand_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so brand_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in brand_categorized_blog.
 */
function brand_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'brand_categories' );
}
add_action( 'edit_category', 'brand_category_transient_flusher' );
add_action( 'save_post',     'brand_category_transient_flusher' );

if ( ! function_exists( 'brand_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * Create your own brand_excerpt_more() function to override in a child theme.
 *
 * @since Brand 1.0.0
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function brand_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'brand' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'brand_excerpt_more' );
endif;

if ( ! function_exists( 'brand_show_excerpt' )) :
	function brand_show_excerpt() {

		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);

		$show_excerpt =  $brand_settings['show_excerpt'];
		return $show_excerpt;

	}
endif;

if ( ! function_exists( 'brand_logo_urls' ) ) :
	function brand_logo_urls() {

		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);

		$logo_url        = ! empty( $brand_settings['logo_url'] ) ? $brand_settings['logo_url'] : 0;
		$logo_mobile_url = ! empty( $brand_settings['logo_mobile_url'] ) ? $brand_settings['logo_mobile_url'] : 0;
		$logos           = array( 'logo' => $logo_url, 'logo_mobile' => $logo_mobile_url );
		return $logos;
	}
endif;

if ( ! function_exists( 'brand_nav_search_icon' ) ) :
/**
 * Add search icon to primary menu if set
 *
 * @since 1.0
 */
add_filter( 'wp_nav_menu_items','brand_nav_items', 10, 2 );
function brand_nav_items( $nav, $args )
{
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);

	// If the search icon isn't enabled, return the regular nav
	if ( 'enabled' !== $brand_settings['nav_search'] )
		return $nav;

	// If the search icon isn't enabled, return the regular nav
	if ( 'enabled' !== $brand_settings['nav_search'] )
		return $nav;

	// If our primary menu is set, add the search icon
    if( $args->theme_location == 'primary' )
        return $nav . '<li class="search-item" title="' . _x( 'Search', 'submit button', 'brand' ) . '"><a href="#"><i class="fa fa-fw fa-search menu-search-form-icon"></i></a></li>';

	// Our primary menu isn't set, return the regular nav
	return $nav;
}
endif;

/**
 * Add callback function if primary menu is not set
 *
 * @since 1.0
 */
if ( ! function_exists( 'brand_primary_menu_fb' ) ) :
	function brand_primary_menu_fb($args) {

		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);
		?>
		<nav id="primary-menu" class="visible-lg main-nav container">
			<ul>
				<?php
				$args = array(
					'sort_column' => 'menu_order',
					'title_li' => '',
				);
				wp_list_pages( $args );
				if ( 'enabled' === $brand_settings['nav_search'] ) :
					echo '<li class="search-item" title="' . esc_html_x( 'Search', 'submit button', 'brand' ) . '"><a href="#"><i class="fa fa-fw fa-search menu-search-form-icon"></i></a></li>';
				endif;
				?>
			</ul>
		</nav><!-- .main-nav -->
		<?php
	}
endif;

if ( ! function_exists( 'brand_mobile_nav_bar' ) ) :
	function brand_mobile_nav_bar() {

		if( brand_is_hidden( 'navigation' ) ) {
			 return '';
		} ?>
    <div class="container mobile-nav-bar no-sticky"> <?php
			$logos = brand_logo_urls();
			if( $logos['logo_mobile'] !== 0 ) {
				$logo_mobile_url = $logos['logo_mobile'];
				printf( // WPCS: XSS ok.
					'<a href="%1$s" title="%2$s" rel="home">
						<img id="logo-mobile" src="%3$s" alt="%2$s" title="%2$s" />
					</a>',
					esc_url( home_url( '/' ) ),
					esc_attr( get_bloginfo( 'name', 'display' ) ),
					esc_url( $logo_mobile_url )
				);

			} else {
				brand_site_title();
			}
			brand_compact_navigation(); ?>
    	</div>
      <?php
	}
endif;

if ( ! function_exists( 'brand_site_title' ) ) :
	function brand_site_title() {
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);
		if ( $brand_settings['show_site_title'] === '1' ) : ?>
			<div id="site-title">
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1> <?php
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description lighter"><?php echo $description; /* WPCS: xss ok. */ ?></p> <?php
				endif; ?>
		  </div> <?php
		endif;
	}
endif;

if ( ! function_exists( 'brand_title' ) ) :
	function brand_title() {

    	if ( is_home() && ! is_front_page() ) : ?>
      	<header class="page-header main-title">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
        </header> <!-- .page-header -->
			<?php
			endif;

			if ( is_archive() ) : ?>
        <header class="page-header main-title">
          <?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>
				</header> <!-- .page-header -->
            <?php
        	endif;

			if ( is_search() ) : ?>
          <header class="page-header main-title">
						<h1 class="page-title">
							<?php
							/* translators: the search query */
							printf( esc_html__( 'Search Results for: %s', 'brand' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header> <!-- .page-header -->
        	<?php endif;

			if ( is_404() ) : ?>
        <header class="page-header main-title">
					<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'brand' ); ?></h1>
				</header> <!-- .page-header -->
        	<?php endif;
			if ( is_singular() ) : ?>
        <header class="page-header main-title">
					<?php the_title( '<h1 class="entry-title">', '</h1>' );
					if ( 'post' === get_post_type() ) : ?>
					<div class="entry-meta">
						<?php brand_posted_on(); ?>
					</div><!-- .entry-meta --> <?php
					endif; ?>
      	</header> <!-- .entry-header --> <?php
			endif;

	}
endif;

if ( ! function_exists( 'brand_get_footer_sidebars' ) ) :
	function brand_get_footer_sidebars() {

		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);

		if( $brand_settings['footer_widgets'] > 4 || $brand_settings['footer_widgets'] < 1 || brand_is_hidden( 'footer_widgets' ) ) {
			return;
		}

		$widgets_col = 12 / $brand_settings['footer_widgets']; ?>
    	<div class="container">
        	<div class="row"> <?php
					for($i = 1; $i <= $brand_settings['footer_widgets']; $i++) { ?>
						<div class="col-md-<?php echo intval( $widgets_col ); ?> footer-widget-wrapper">
							<?php get_sidebar( 'footer-' . $i ); ?>
						</div>
					<?php
				} ?>
					</div>
			 </div> <!-- .container --> <?php

	}
endif;

if ( ! function_exists( 'brand_featured_image' ) ) :
/**
 * Generates the markup needed to display post thumbnails in posts and pages.
 *
 * @since 1.1.0
 */
function brand_featured_image() {
	global $post;
	if( has_post_thumbnail() ) {
		printf(
			'<div class="page-header-featured"> %s </div>',
			get_the_post_thumbnail()
		);
	}
}
endif;

if ( ! function_exists( 'brand_featured_inside' ) ) :
/**
 * Places the featured image in an action hook according to settings.
 *
 * @since 1.1.0
 */
function brand_featured_inside() {
	if ( is_singular() ) {
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);
		if( $brand_settings['featured_position'] === 'inside' ) {
			brand_featured_image();
		}
	}
}
add_action( 'brand_inside_singular_content_above', 'brand_featured_inside' );
endif;

if ( ! function_exists( 'brand_featured_before' ) ) :
/**
 * Places the featured image according to settings.
 *
 * @since 1.1.0
 */
function brand_featured_before() {
	if ( is_singular() ) {
		$brand_settings = wp_parse_args(
			get_option( 'brand_settings', array() ),
			brand_get_defaults()
		);
		if( $brand_settings['featured_position'] === 'before' ) {
			brand_featured_image();
		}
	}
}
add_action( 'brand_before_singular_content', 'brand_featured_before' );
endif;

if ( ! function_exists( 'brand_is_hidden' ) ) :
/**
 * Checks if an element is hidden.
 *
 * @since 1.1.0
 */
function brand_is_hidden( $element ) {
	if ( is_singular() ) {
		global $post;
		$hide_elements_meta = get_post_meta( $post->ID, '_brand_hide_elements', true );
		$hide_el = isset( $hide_elements_meta['hide_' . $element] ) && $hide_elements_meta['hide_' . $element] === 1 ? true : false;
		return $hide_el;
	}
}
endif;

if ( ! function_exists( 'brand_addons_installed' ) ) :
/**
 * Checks for add-ons installation.
 *
 * @since 1.0.0
 */
function brand_addons_installed() {
	if( function_exists( 'brand_premium_admin_enqueue_scripts' ) ) {
		return true;
	}
	return false;
}

endif;

/**
 * Checks if the page uses sections.
 *
 * @since 1.2.0
 */
function brand_use_sections() {
	if ( is_singular() ) {
		global $post;
		$brand_sections_meta = get_post_meta( $post->ID, '_brand_sections_meta', true );
		if ( isset( $brand_sections_meta['use_sections'] ) && $brand_sections_meta['use_sections'] === 'yes' ) {
			return true;
		}
	}
	return false;
}

if ( ! function_exists( 'brand_get_sidebar' ) ) :
/**
 * Checks if the sidebar should be shown.
 *
 * @since 1.6.3
 */
function brand_get_sidebar() {
	$brand_settings = wp_parse_args(
		get_option( 'brand_settings', array() ),
		brand_get_defaults()
	);
	if( is_singular() && ! is_attachment() ) {
		global $post;
		$brand_show_sidebar = get_post_meta( $post->ID, '_brand_show_sidebar', true );
		if( $brand_show_sidebar === 'no' || $brand_show_sidebar === 'default' && $brand_settings['sidebar_layout'] === 'no' ) {
			return;
		}
	} else if( $brand_settings['sidebar_layout'] === 'no' ) {
		return;
	}
	return get_sidebar();
}

endif;

/**
 * Checks if the header type is no-header.
 *
 * @since 1.8
 */
 function brand_no_header() {
	 $brand_settings = wp_parse_args(
		 get_option( 'brand_settings', array() ),
		 brand_get_defaults()
	 );
	 if( is_singular() ) {
		 if( is_front_page() ) {
			 if ( $brand_settings['header_type_front'] !== 'no-header' ) {
				return false;
			} else {
				return true;
			}
		 }
		 if( brand_is_hidden( 'header' ) ) {
			 return true;
		 }
		 if( has_header_image() || ( has_post_thumbnail() && $brand_settings['featured_position'] === 'inside_header' ) ) {
			 return false;
		 }
	 } else {
		if( ( is_front_page() && $brand_settings['header_type_front'] !== 'no-header' ) || ( ! is_front_page() && $brand_settings['header_type'] !== 'no-header' ) ) {
			return false;
	 	}
	}
	return true;
}

/**
 * Return posts listing style.
 *
 * @since 1.8.1
 */
 function brand_posts_listing_style() {
	 	$brand_settings = wp_parse_args(
 			get_option( 'brand_settings', array() ),
 			brand_get_default_blog()
 		);
		return $brand_settings['posts_listing_style'];
}
