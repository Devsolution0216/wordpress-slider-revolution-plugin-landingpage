<?php
function shiftkey_get_page_template_type(){
	$template_type = 'default';
	if( is_page() ){
		$newval = get_post_meta( get_the_ID(), 'shiftkey_template_type', true );
		$template_type = ( $newval != '' )? $newval : $template_type;
		return $template_type;
	}
	return $template_type;
}

add_action( 'shiftkey_content_before', 'shiftkey_content_before_callback' );
function shiftkey_content_before_callback(){
	$template_type = shiftkey_get_page_template_type();	
	if( $template_type == 'default' ){
		get_template_part( 'template-parts/content', 'before' );
	}	
	
}

add_action( 'shiftkey_content_after', 'shiftkey_content_after_callback' );
function shiftkey_content_after_callback(){
	$template_type = shiftkey_get_page_template_type();

	if( $template_type == 'default' ){
		get_template_part( 'template-parts/content', 'after' );
	}
}


function shiftkey_post_class( $classes ) {
	global $post;

	if(!has_post_thumbnail()) $classes[] = 'no-post-thumbnail';

	if(is_singular()){
		$classes[] = 'single-'.get_post_type().'-details';
	}
	if(is_singular('portfolio')){
		$classes[] = 'division';
	}
	if(is_search()){
		$classes[] = 'blog-post';
	}

	if ( 'post' == get_post_type() ){
		$classes[] = 'primary-theme';
		if(is_singular()){
			$classes[] = shiftkey_blog_single_classes();
		}else{
			$classes[] = shiftkey_blog_classes();
		}
	}

	if ( 'product' == get_post_type() ){
		if(!is_singular()){
			$classes[] = 'text-center loop-item';			
		}

		if(is_singular()){
			$classes[] = 'single-post-details';
		}

	}

	if ( ('page' == get_post_type()) && !is_home() ){
		
	}
	
	return $classes;
}
add_filter( 'post_class', 'shiftkey_post_class' );

function shiftkey_footer_is_on(){
	$template_type = shiftkey_get_page_template_type();
	$footer_display = 'on';
	if( ($template_type == 'landing') && is_page() ){
	
		$newval = get_post_meta( get_the_ID(), 'onepage_footer_display', true );
		$footer_display = ( $newval != '' )? $newval : $footer_display;		

	}

	if( $footer_display == 'on' ) return true;
	else return false;
}

function shiftkey_get_header_search_icon(){
	$output = '';
	$icon = shiftkey_get_option('header_search_icon', 'on');
	if( $icon == 'off' ) return false;
	$link = shiftkey_get_option('header_search_link', '#');
	return '<li class="search-link"><a href="'.esc_url($link).'"  data-toggle="modal" data-target=".bs-search-modal-lg"><i class="fa fa-search dark-color"></i></a></li>';
}

if ( ! function_exists( 'shiftkey_comment_nav' ) ) :
/**
 * Display navigation to next/previous comments when applicable.
 */
function shiftkey_comment_nav() {
	// Are there comments to navigate through?
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
	?>
	<nav class="Page navigation mt-50 mb-50" role="navigation">
		<h2 class="screen-reader-text"><?php esc_attr_e( 'Comment navigation', 'shiftkey' ); ?></h2>
		<ul class="pagination justify-content-left">
			<?php
				$previous_text = esc_attr__( 'Older Comments', 'shiftkey' );
				if ( $prev_link = get_previous_comments_link( esc_attr($previous_text) ) ) :
					printf( '<li class="page-item nav-previous"><span class="page-link">%s</span></li>', $prev_link );
				endif;

				$next_text = esc_attr__( 'Newer Comments', 'shiftkey' );
				if ( $next_link = get_next_comments_link( esc_attr($next_text) ) ) :
					printf( '<li class="page-item nav-next"><span class="page-link">%s</span></li>', $next_link );
				endif;
			?>
		</ul><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
	<?php
	endif;
}
endif;

if(!function_exists('shiftkey_comment_callback')):
function shiftkey_comment_callback($comment, $args, $depth) {
    if ( 'div' === $args['style'] ) {
        $tag       = 'li';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr($tag) ?> <?php comment_class( empty( $args['has_children'] ) ? 'clearfix' : 'parent clearfix' ); ?> id="comment-<?php comment_ID() ?>">
    <div id="div-comment-<?php comment_ID() ?>" class="media">

    
	    
        <?php if ( '0' !== $args['avatar_size'] && ( $comment->comment_type == '' ) ) echo '<div class="comment-avatar">'.get_avatar( get_comment_author_email( $comment->comment_ID ), $args['avatar_size'] ).'</div>'; ?>

        <div class="media-body">
		        
		        <div class="comment-meta">	
		        	<?php printf( __( '<h5 class="fn h5-xs mt-0">%s</h5>', 'shiftkey' ), get_comment_author_link() ); ?>				
					<a class="comment-date href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><span><?php
		                /* translators: 1: date, 2: time */
		                printf( 
		                    __('%1$s at %2$s', 'shiftkey'), 
		                    get_comment_date(),  
		                    get_comment_time() 
		                ); ?>
		            </span></a>&nbsp;-&nbsp;
		            <?php echo shiftkey_comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		            <span class="btn-reply"><?php edit_comment_link( esc_attr__( 'Edit', 'shiftkey' ), '  ', '' ); ?></span>
				   
				</div>
		    

		    <?php if ( $comment->comment_approved == '0' ) : ?>
		         <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'shiftkey' ); ?></em>
		          <br />
		    <?php endif; ?>

	    		<div class="comment-body">
	    			<?php comment_text(); ?>
	    		</div>	 

	    	</div><!-- .content -->
	    </div><!-- .comment-info -->

    <?php
}
endif;

add_filter('next_posts_link_attributes', 'shiftkey_next_posts_link_attributes');
function shiftkey_next_posts_link_attributes() {
    return 'class="older-posts"';
}

add_filter('previous_posts_link_attributes', 'shiftkey_previous_posts_link_attributes');
function shiftkey_previous_posts_link_attributes(){
	return 'class="newer-posts"';
}

if(!function_exists('shiftkey_posts_navigation')){
	function shiftkey_posts_navigation($numeric = false){	
		if($numeric){
			if( is_singular() )
				return;

			global $wp_query;

			/** Stop execution if there's only 1 page */
			if( $wp_query->max_num_pages <= 1 )
				return;

			$paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
			$max   = intval( $wp_query->max_num_pages );

			/**	Add current page to the array */
			if ( $paged >= 1 )
				$links[] = $paged;

			/**	Add the pages around the current page to the array */
			if ( $paged >= 3 ) {
				$links[] = $paged - 1;
				$links[] = $paged - 2;
			}

			if ( ( $paged + 2 ) <= $max ) {
				$links[] = $paged + 2;
				$links[] = $paged + 1;
			}

			echo '<div class="post-pagination"><ul class="list-inline">';

			/**	Previous Post Link */
			if ( get_previous_posts_link() )
				printf( '<li><a href="%s"><i class="shiftkey-angle-long-left-o"></i></a></li></li>', esc_url( get_pagenum_link( 1 ) ) );

			/**	Link to first page, plus ellipses if necessary */
			if ( ! in_array( 1, $links ) ) {
				$class = 1 == $paged ? ' class="active"' : '';

				printf( '<li><a%s href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( 1 ) ), '1' );

				if ( ! in_array( 2, $links ) )
					echo '<li>...</li>';
			}

			/**	Link to current page, plus 2 pages in either direction if necessary */
			sort( $links );
			foreach ( (array) $links as $link ) {
				$class = $paged == $link ? ' class="active"' : '';
				printf( '<li><a%s href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $link ) ), $link );
			}

			/**	Link to last page, plus ellipses if necessary */
			if ( ! in_array( $max, $links ) ) {
				if ( ! in_array( $max - 1, $links ) )
					echo '<li>...</li>';

				$class = $paged == $max ? ' class="active"' : '';
				printf( '<li><a%s href="%s">%s</a></li>', $class, esc_url( get_pagenum_link( $max ) ), $max );
			}

			/**	Next Post Link */
			if ( get_next_posts_link() )
				printf( '<li><a href="%s"><i class="shiftkey-angle-long-right-o"></i></a></li>', esc_url( get_pagenum_link( $max ) ) );

			echo '</ul></div>' . "\n";
			

		}else{
			echo '<div class="posts-navigation">';	
			echo get_previous_posts_link('<i class="shiftkey-angle-long-left-o"></i>'.esc_attr__('Newer Posts', 'shiftkey'));
			echo get_next_posts_link(__('Older Posts', 'shiftkey').'<i class="shiftkey-angle-long-right-o"></i>');
			echo '</div>';
		}
	}
}

if(!function_exists('shiftkey_post_navigation')):
function shiftkey_post_navigation(){
	if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'shiftkey' ),
				) );
			} elseif ( is_singular( array('post', 'portfolio') ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<h5 class="meta-nav h5-xs" aria-hidden="true">' . sprintf(_x('%s', 'Next post text', 'shiftkey'), shiftkey_get_option('next_post_text', 'Next')) . ' <i class="fas fa-arrow-right"></i></h5> ' .
						'<span class="screen-reader-text">' . esc_attr__( 'Next post:', 'shiftkey' ) . '</span> ' .	'<p class="theme-text">%title</p>',
					'prev_text' => '<h5 class="meta-nav" aria-hidden="true"><i class="fas fa-arrow-left"></i> ' . sprintf(_x('%s', 'Previous post text', 'shiftkey'), shiftkey_get_option('prev_post_text', 'Previous')) . '</h5> ' .
						'<span class="screen-reader-text">' . esc_attr__( 'Previous post:', 'shiftkey' ) . '</span> ' .
						'<p class="theme-text">%title</p>',
				) );
			}
}
endif;

if(!( function_exists('shiftkey_template_pagination') )){
	function shiftkey_template_pagination($pages = '', $range = 2, $number=true){
		$showitems = ($range * 2)+1;
		global $wp_query;
		$paged = (isset($wp_query->query['paged']))? $wp_query->query['paged'] : 1;
		
		if($pages == ''){
			
			$pages = $query->max_num_pages;
				if(!$pages) {
					$pages = 1;
				}
		}
		$older_posts_text = shiftkey_get_option( 'older_posts_text', 'Older Posts' );
		$newer_posts_text = shiftkey_get_option( 'newer_posts_text', 'Newer Posts' );
		
		$output = '';
			
		if((1 != $pages) && (!$number)){
			$output .= "<div class='text-center'><ul class='pagination list-inline'>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) $output .= "<li><a href='".get_pagenum_link(1)."' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li> ";
			
			for ($i=1; $i <= $pages; $i++){
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					$output .= ($paged == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."</a></li> ":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li> ";
				}
			}
		
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $output .= "<li><a href='".get_pagenum_link($pages)."' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li> ";
			$output.= "</ul></div>";
		}else{
			$older_posts_text = sprintf(_x('%s', 'Older post text', 'shiftkey'), $older_posts_text);
			$newer_posts_text = sprintf(_x('%s', 'Newer post text', 'shiftkey'), $newer_posts_text);

			$output .= '<div class="posts-navigation">';
			$output .= ($paged > 1)? '<a href="'.get_pagenum_link($paged-1).'" class="btn btn-default newer-posts"><i class="shiftkey-angle-long-left-o"></i>'.esc_attr($newer_posts_text).'</a>' : '';
			$output .= ($pages != $paged)? '<a href="'.get_pagenum_link($paged+1).'" class="btn btn-default older-posts pull-right">'.esc_attr($older_posts_text).'<i class="shiftkey-angle-long-right-o"></i></a>' : '';
			$output .= '</div>';
		}
		

		return $output;
	}
}

function shiftkey_post_category($prep = false){
	global $post;
	$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'shiftkey' ), '', $post->ID );
	if ( $categories_list && $prep) {
		printf( '%1$s %2$s',			
			_x( 'in', 'Used before post category.', 'shiftkey' ),	
			$categories_list
		);
	}else{
		printf( '%1$s',
			$categories_list
		);
	}
}

function shiftkey_sticky_post_text(){
	global $post;
	if ( is_sticky() ) {
		$sticky = shiftkey_get_option( 'sticky_post_text', 'Sticky' );
		echo '<span class="sticky-post badge">';
		printf( _x('%s', 'Sticky post text', 'shiftkey'), $sticky );
		echo '</span>';
	}
}

function shiftkey_post_date( $format = 'l, M d, Y' ){
	global $post;
	$time_string = '<span><time class="entry-date published updated" datetime="%1$s">%2$s</time></span>';		
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date()
	);
	printf( '%3$s <a class="post-time" href="%1$s" rel="bookmark">%2$s</a>',
		esc_url( get_permalink() ),
		$time_string,	
		_x( 'on', 'Used before post date.', 'shiftkey' )	
	);	
}

function shiftkey_post_author($link = true, $prep = true){
	global $post;

	$prep_text = ($prep)? _x( 'Posted by', 'Used before post author name.', 'shiftkey' ): '';
	if($link){
		printf( '%1$s <a class="by url fn n" href="%2$s"><span class="byline"><span class="author vcard">%3$s</span></span></a> ',
			$prep_text,
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}else{
		printf( '%1$s <span class="author vcard">%2$s</span>',
			$prep_text,			
			get_the_author()
		);
	}
	
}

function shiftkey_post_category_meta(){
	echo '<span class="'.shiftkey_default_color().'-color">&nbsp;';
	echo esc_attr(_x( 'in', 'Used before post category.', 'shiftkey' )).'&nbsp;';
	shiftkey_post_category();
	echo '</span> ';	
}

function shiftkey_post_tag( $before = '', $sep = '', $after = '' ) {
    $output = false;
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma. */
        $tags_list = get_the_tag_list( $before, esc_attr( $sep ), $after );
        if ( $tags_list ) {
            $output = sprintf( /* translators: 1: SVG icon. 2: posted in label, only visible to screen readers. 3: list of tags. */ '<span class="screen-reader-text">%1$s </span>%2$s', __( 'Tags:', 'shiftkey' ), $tags_list ); // WPCS: XSS OK.
        }
    }
    return $output;
}

function shiftkey_post_comment_meta(){
	global $post;
	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$num_comments = get_comments_number();
		if ( comments_open() && $num_comments != 0 ) {
			if ( $num_comments == 0 ) {
				$comments = esc_attr__('0', 'shiftkey');
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments;
			} else {
				$comments = esc_attr__('1', 'shiftkey');
			}
			echo '<span class="comments-meta"><a class="comments" href="' . get_comments_link() .'"><i class="far fa-comment"></i><span>'. $comments.'</span></a></span> ';
			
		}
	}
}

if( !function_exists('shiftkey_get_post_like') ):
function shiftkey_get_post_like($link = true){
    return (function_exists('perch_get_simple_likes_button'))? '<span> '.perch_get_simple_likes_button(get_the_ID()).'</span>' : '';
}
endif;

if ( ! function_exists( 'shiftkey_entry_meta' ) ) :
/**
 * Prints HTML with meta information for the categories, date.
 <p class="post-meta">May 10, 2018 <span class="rose-color">in Photography</span></p>
 */
function shiftkey_entry_meta( $display_in = 'post_meta' ) {	

	$post_meta_display = shiftkey_get_option('post_meta_display', 'on');
	if($post_meta_display == 'off') return false;

	$post_metaArr = shiftkey_get_option( esc_attr($display_in), array('date', 'category') );	

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {

	
			if( in_array('date', $post_metaArr) ) shiftkey_post_date();			
			
			if( in_array('category', $post_metaArr) ) shiftkey_post_category_meta();	

			if( in_array('author', $post_metaArr) ) shiftkey_post_author();

			if( in_array('comment', $post_metaArr) ) shiftkey_post_comment_meta();

		
	}
}
endif;

function shiftkey_date_meta() {
	echo '<span class="post-meta">';	
	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {
		$time_string = '<span><time class="entry-date published updated" datetime="%1$s">%2$s</time></span>';
		
		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			get_the_date('M d, Y')
		);

		printf( '%1$s <a href="%2$s" rel="bookmark">%3$s</a>',
			esc_attr__( 'Posted On :', 'shiftkey' ),
			esc_url( get_permalink() ),
			$time_string
		);
	}

	echo '</span>';
}



if ( ! function_exists( 'shiftkey_footer_entry_meta' ) ) :	
/**
 * Prints HTML with meta information for the categories, tags.
 */
function shiftkey_footer_entry_meta(){
	global $post;

	echo shiftkey_get_post_like();

	echo shiftkey_post_comment_meta();

}
endif;

if ( ! function_exists( 'shiftkey_social_share' ) ) :
function shiftkey_social_share( $icon = true ) {
	$single_post_share = shiftkey_get_option( 'single_post_share', 'off' );
	if( is_singular('post') && ($single_post_share == 'off')) return false;
	
	if( function_exists('perch_social_share') ){
		perch_social_share($icon);
	}
	
}
endif;

if ( ! function_exists( 'shiftkey_excerpt_length' ) ) :
//excerpt length	
	function shiftkey_excerpt_length( $length ) {
		return shiftkey_get_option( 'excerpt_length', 40 );
	}
	add_filter( 'excerpt_length', 'shiftkey_excerpt_length', 999 );
endif;

if ( ! function_exists( 'shiftkey_excerpt_more' ) && ! is_admin() ) :
function shiftkey_excerpt_more( $more ) {
	global $posts;
	$readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );
	return '...';
}
add_filter( 'excerpt_more', 'shiftkey_excerpt_more' );
endif;

function modify_read_more_link() {
	$readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );
    return '<a class="more-link btn-link" href="' . get_permalink() . '">'.esc_attr($readmore_text).'</a>';
}
add_filter( 'the_content_more_link', 'modify_read_more_link' );

function shiftkey_read_more_button( ) {
	global $post;
	$readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );	
	return '<div class="mt-30"><a href="'.get_permalink($post->ID).'" class="btn btn-theme">'.esc_attr($readmore_text).' <i class="fa fa-long-arrow-right"></i></a></div>';
}

if ( ! function_exists( 'shiftkey_get_trim_words' ) ) :
function shiftkey_get_trim_words($content='', $count=55, $ext='...', $wrap=false, $btn=false, $btnclass="read-more", $btntext=''){
	global $post;
	if($count == 0) return false;
	
	if($content == '') $content = get_the_excerpt($post->ID);
	if($wrap){
		$output =  '<p>'.wp_trim_words( $content, $count, $ext ).'</p>';
	}else{
		$output =  wp_trim_words( $content, $count, $ext );
	}
	
	if($btn){
		$readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );
		$readmore_text = ($btntext == '')? $readmore_text : $btntext;

		$output .= '<a class="'.esc_attr($btnclass).'" href="'.get_permalink($post->ID).'">'.
		sprintf( _x( '%s','Continue Reading text', 'shiftkey' ), esc_attr($readmore_text)).' <i class="fa fa-long-arrow-right"></i></a>';
	}

	return $output;
          
}
endif;

//add_filter('get_the_excerpt', 'shiftkey_get_the_excerpt', 10, 2);
function shiftkey_get_the_excerpt($excerpt, $post){

	$output = '';
	$readmore_text = shiftkey_get_option( 'read_more_text', 'Read More' );

	$output .= '<a class="btn btn-primary" href="'.get_permalink($post->ID).'">'.
	sprintf( _x( '%s','Continue Reading text', 'shiftkey' ), esc_attr($readmore_text)).' <i class="shiftkey-angle-long-right-o"></i></a>';

	return '<p class="normal">'.$excerpt.'</p>'.$output;
}



function shiftkey_page_title_display(){
	global $post;
	$display = get_post_meta( $post->ID, 'title_display', true );
	return ($display == 'off')? false : true;
	
}
function shiftkey_get_page_title(){
	global $post;
	$title = get_post_meta( $post->ID, 'title', true );
	$subtitle = get_post_meta( $post->ID, 'subtitle', true );

	$output = '<div class="post-info page-info text-center">
				<h3 class="post-title">'.(($title != '')? esc_attr($title) : esc_attr(get_the_title()) ).'</h3>'
				.(($subtitle != '')? '<p class="subtitle">'.esc_attr($subtitle).'</p>' : '').'
				<div class="shape1"><i class="perch perch-cross-leaf"></i></div>
      			</div><!-- .post-info -->';
	return $output;
	
}

function shiftkey_page_title(){
	echo (shiftkey_page_title_display())?  shiftkey_get_page_title() : '';
}

//add layout option
function shiftkey_body_class($classes ){
	global $wp_query;

	$classes[] = 'shiftkey-layout-'.esc_attr( shiftkey_get_option('shiftkey_layout_style', 'semirounded') );


	$wp_query->shiftkey = shiftkey_layout_option_values();
	if ( !is_page_template( 'page-templates/one-page.php' ) ) {
		$style = shiftkey_get_option( 'background_style', 'width' );
		$classes[] = $style;
		if($style == 'boxed'){
			$classes[] = shiftkey_get_option('background_type', 'pattern');
		}
		
	}


	if($wp_query->shiftkey['layout'] == 'ls'){
		$classes[] = 'left-sidebar';
	}elseif ($wp_query->shiftkey['layout'] == 'rs') {
		$classes[] = 'right-sidebar';
	}else{
		$classes[] = 'no-sidebar';
	}

	if( is_page() && is_front_page() ){
		$header_style = shiftkey_get_option('frontpage_header_style', 'transparent-header');
		$classes[] = 'frontpage-'.$header_style;		
	}

	if( is_page() ){
		$force_transparent_header = get_post_meta( get_the_ID(), 'force_transparent_header', true );
		if( $force_transparent_header == 'on' ){
			$classes[] = 'frontpage-transparent-header';
		}
	}

	$post_type_arr = array('portfolio', 'service', 'team', 'partner', 'job');
	foreach ($post_type_arr as $key => $value) {
		if ( $value == get_post_type() ){
			if( get_post_status(get_option($value.'_archive_id')) == 'publish' ){
				$page_id = get_option($value.'_archive_id'); 
				$force_transparent_header = get_post_meta( $page_id, 'force_transparent_header', true );
				if( $force_transparent_header == 'on' ){
					$classes[] = 'frontpage-transparent-header';
				}
			}
		}
	}

	if ( 'post' == get_post_type() ){
		if( get_post_status(get_option('page_for_posts')) == 'publish' ){
			$page_id = get_option('page_for_posts'); 
			$force_transparent_header = get_post_meta( $page_id, 'force_transparent_header', true );
			if( $force_transparent_header == 'on' ){
				$classes[] = 'frontpage-transparent-header';
			}
		}
	}

	return $classes;
}
add_filter( 'body_class', 'shiftkey_body_class' );



function shiftkey_content_wrap_class(){
	 $classes = array();
    $layout  = shiftkey_get_layout();
    if ( function_exists( 'is_woocommerce' ) && ( 'product' == get_post_type() && !is_singular( 'product' ) ) ) {
        $classes[] = ( $layout != 'full' ) ? 'col-md-8' : 'col-md-12';
    } elseif ( !is_page() ) {
        $classes[] = ( $layout != 'full' ) ? 'col-md-8' : 'col-md-10 offset-md-1';
    } else {
        $classes[] = ( $layout != 'full' ) ? 'col-md-8' : 'col-md-12';
    }
    $classes[] = ( $layout == 'ls' ) ? 'order-md-last' : '';
    $classes   = array_unique( $classes );
    echo implode( ' ', $classes );
}

if(!function_exists('shiftkey_gallery_format_content')):
function shiftkey_gallery_format_content(){
	global $post;
	$meta_value = get_post_meta( $post->ID, '_format_gallery', true );
	if($meta_value != '') echo do_shortcode($meta_value);
}
endif;

if(!function_exists('shiftkey_video_format_content')):
function shiftkey_video_format_content(){
	global $post;
	$meta_value = get_post_meta( $post->ID, '_format_video_embed', true );
	if($meta_value != '') {
		$embed = new WP_Embed();
		echo '<div class="responsive-video">'.$embed->run_shortcode($meta_value).'</div>';
	}	
}
endif;

if(!function_exists('shiftkey_audio_format_content')):
function shiftkey_audio_format_content(){
	global $post;
	$meta_value = get_post_meta( $post->ID, '_format_audio_embed', true );
	if($meta_value != '') {
		$embed = new WP_Embed();
		echo '<div class="audio-holder">'.$embed->run_shortcode($meta_value).'</div>';
	}	
}
endif;

if(!function_exists('shiftkey_status_format_content')):
function shiftkey_status_format_content(){
	global $post;
	$meta_value = get_post_meta( $post->ID, '_format_status_embed', true );
	if($meta_value != '') {
		$style = '';
		$background_type = get_post_meta( $post->ID, 'status_background_type', true );	

		if( ($background_type == 'featured') && has_post_thumbnail($post->ID) ){
			$fullImage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );	
			$imageurl = $fullImage[0];		
		}else{
			$imageurl = get_post_meta( $post->ID, '_format_status_image', true );
		}
		$style = ' style="background-image: url('.esc_url($imageurl).')"';
		$tag = 'iframe';
		echo '<div class="twitter-holder"'.$style.'>
      <'.$tag.'  height=240 width=500  scrolling=no src="http://twitframe.com/show?url='.esc_url($meta_value).'"></'.$tag.'>
    </div>';
	}	
}
endif;

if(!function_exists('shiftkey_quote_format_content')):
function shiftkey_quote_format_content(){
	global $post;
	$meta_value = get_post_meta( $post->ID, '_format_quote_text', true );
	if($meta_value != '') {
		$style = '';
		$background_type = get_post_meta( $post->ID, 'quote_background_type', true );	

		if( ($background_type == 'featured') && has_post_thumbnail($post->ID) ){
			$fullImage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );	
			$imageurl = $fullImage[0];		
		}else{
			$imageurl = get_post_meta( $post->ID, '_format_quote_image', true );
		}
		$style = ' style="background-image: url('.esc_url($imageurl).')"';

		$name = get_post_meta( $post->ID, '_format_quote_name', true );

		echo '<div class="blog-post qutoe-wrap"'.$style.'>
			    <div class="post-content"> 
			      <div class="post-info text-center">
			        <span class="post-format-icon">&ldquo;</span>
			      </div><!-- .post-info -->
			      
			      <div class="entry-summary qutoe-content text-center">
			          <p><span>&ldquo;</span> '.esc_attr($meta_value).' <span>&rdquo;</span></p>
			      <div class="shape1"><i class="perch perch-cross-leaf"></i></div>
			      '.(($name != '')? '<h3 class="post-title">'.esc_attr($name).'</h3>' : '').'
			      </div><!-- .entry-summary -->                      
			    </div><!-- .post-content -->
			  </div><!-- .blog-post --> ';
	}	
}
endif;

if(!function_exists('shiftkey_link_format_content')):
function shiftkey_link_format_content(){
	global $post;
	$linkurl = get_post_meta( $post->ID, '_format_link_url', true );
	$title = get_post_meta( $post->ID, '_format_link_title', true );

	$style = '';
	if( has_post_thumbnail($post->ID) ){
		$fullImage = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );	
		$imageurl = $fullImage[0];	
		$style = ' style="background-image: url('.esc_url($imageurl).')"';	
	}
	
	$title = ($title != '')? $title : get_the_title();
	echo '<div class="link-holder text-center"'.$style.'>
	    <div class="link-content">
	      <i class="fa fa-link format-link-icon"></i>
	      <h3 class="post-title"><a href="'.get_permalink($post->ID).'" title="'.esc_attr($title).'">'.esc_attr($title).'</a></h3>
	      '.(($linkurl != '')? '<span class="link">'.esc_url($linkurl).'</span>' : '').'
	    </div>
	</div>';
		
}
endif;




/* Check if Class Exists. */
if ( !class_exists( 'Shiftkey_Walker_Menu' ) ) {

  /**
   * WP_Bootstrap_Navwalker class
   *
   * @package        Template
   * @subpackage     Bootstrap4
   *
   * @since          1.0.0
   * @see            https://getbootstrap.com/docs/4.0/components/navbar/
   * @extends        Walker_Nav_Menu
   * @author         Javier Prieto
   */
  class Shiftkey_Walker_Menu extends Walker_Nav_Menu {

    /**
     * @since       1.0.0
     * @access      public
     * @var type    bool
     */
    private $dropdown = false;

    /**
     * Starts the list before the elements are added.
     *
     * @since       1.0.0
     *
     * @see Walker::start_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $this->dropdown = true;
      $output         .= $n . str_repeat( $t, $depth ) . '<ul class="dropdown-menu" role="menu">' . $n;
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @since       1.0.0
     *
     * @see Walker::end_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $this->dropdown = false;
      $output         .= $n . str_repeat( $t, $depth ) . '</ul>' . $n;
    }

    /**
     * Starts the element output.
     *
     * @since 1.0.0
     *
     * @see Walker::start_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $indent = str_repeat( $t, $depth );

      if ( 0 === strcasecmp( $item->attr_title, 'divider' ) && $this->dropdown ) {
        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
        return;
      } elseif ( 0 === strcasecmp( $item->title, 'divider' ) && $this->dropdown ) {
        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
        return;
      }

      $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
      $classes[] = 'menu-item-' . $item->ID;
      $classes[] = 'nav-item';

      if ( $args->walker->has_children ) {
        $classes[] = 'dropdown';
      }else{
      	$classes[] = 'nl-simple';
      }

      if ( 0 < $depth ) {
        $classes[] = 'dropdown-menu-item';
      }

      /**
       * Filters the arguments for a single nav menu item.
       *
       * @since 1.2.0
       *
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param WP_Post  $item  Menu item data object.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

      /**
       * Filters the CSS class(es) applied to a menu item's list item element.
       *
       * @since 3.0.0
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
       * @param WP_Post  $item    The current menu item.
       * @param stdClass $args    An object of wp_nav_menu() arguments.
       * @param int      $depth   Depth of menu item. Used for padding.
       */
      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
      $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

      /**
       * Filters the ID applied to a menu item's list item element.
       *
       * @since 3.0.1
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
       * @param WP_Post  $item    The current menu item.
       * @param stdClass $args    An object of wp_nav_menu() arguments.
       * @param int      $depth   Depth of menu item. Used for padding.
       */
      $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
      $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

      if ( !$this->dropdown ) {
        $output .= $indent . '<li' . $id . $class_names . '>' . $n . $indent . $t;
      }else{
      	$output .= $indent . '<li'. $class_names . '>' . $n . $indent . $t;
      }

      $atts           = array();
      $atts['title']  = !empty( $item->attr_title ) ? $item->attr_title : '';
      $atts['target'] = !empty( $item->target ) ? $item->target : '';
      $atts['rel']    = !empty( $item->xfn ) ? $item->xfn : '';
      $atts['href']   = !empty( $item->url ) ? $item->url : '';

      /**
       * Filters the HTML attributes applied to a menu item's anchor element.
       *
       * @since 3.6.0
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param array $atts {
       *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
       *
       *     @type string $title  Title attribute.
       *     @type string $target Target attribute.
       *     @type string $rel    The rel attribute.
       *     @type string $href   The href attribute.
       * }
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

      if ( $args->walker->has_children && ( $depth < 1 ) ) {
        $atts['data-toggle']   = 'dropdown';
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
      }

      $attributes = '';
      foreach ( $atts as $attr => $value ) {
        if ( !empty( $value ) ) {
          $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
          $attributes .= ' ' . $attr . '="' . $value . '"';
        }
      }

      /** This filter is documented in wp-includes/post-template.php */
      $title = apply_filters( 'the_title', $item->title, $item->ID );

      /**
       * Filters a menu item's title.
       *
       * @since 4.4.0
       *
       * @param string   $title The menu item's title.
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

      $item_classes = array( 'nav-link' );

      if ( $args->walker->has_children && ( $depth < 1 ) ) {
        $item_classes[] = 'dropdown-toggle';
      }

      if ( 0 < $depth ) {
        $item_classes = array_diff( $item_classes, [ 'nav-link' ] );
        $item_classes[] = 'dropdown-item';
      }

      $item_output = $args->before;
      $item_output .= '<a class="' . implode( ' ', $item_classes ) . '" ' . $attributes . '>';
      $item_output .= $args->link_before . $title . $args->link_after;
      $item_output .= '</a>';
      $item_output .= $args->after;

      /**
       * Filters a menu item's starting output.
       *
       * The menu item's starting output only includes `$args->before`, the opening `<a>`,
       * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
       * no filter for modifying the opening and closing `<li>` for a menu item.
       *
       * @since 3.0.0
       *
       * @param string   $item_output The menu item's starting HTML output.
       * @param WP_Post  $item        Menu item data object.
       * @param int      $depth       Depth of menu item. Used for padding.
       * @param stdClass $args        An object of wp_nav_menu() arguments.
       */
      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 1.0.0
     *
     * @see Walker::end_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Page data object. Not used.
     * @param int      $depth  Depth of page. Not Used.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $output .= $this->dropdown ? '' : str_repeat( $t, $depth ) . '</li>' . $n;
    }

    /**
     * Menu Fallback
     *
     * @since 1.0.0
     *
     * @param array $args passed from the wp_nav_menu function.
     */
    public static function fallback( $args ) {
      if ( current_user_can( 'edit_theme_options' ) ) {

        $defaults = array(
            'container'       => '',
            'container_id'    => false,
            'container_class' => false,
            'menu_class'      => '',
            'menu_id'         => false,
        );
        $args     = wp_parse_args( $args, $defaults );
        if ( !empty( $args['container'] ) ) {
          echo sprintf( '<%s id="%s" class="%s">', $args['container'], $args['container_id'], $args['container_class'] );
        }
        echo sprintf( '<ul id="%s" class="%s">', $args['menu_id'], $args['menu_class'] ) .
        '<li class="nav-item">' .
        '<a href="' . admin_url( 'nav-menus.php' ) . '" class="nav-link">' . esc_attr__( 'Add a menu', 'shiftkey' ) . '</a>' .
        '</li></ul>';
        if ( !empty( $args['container'] ) ) {
          echo sprintf( '</%s>', $args['container'] );
        }
      }
    }

  }

}

function shiftkey_image_gallery_ids($gallery){
	if($gallery == '') return false;

	$arr = explode(',', $gallery);
	$arr = array_filter($arr);

	return $arr;
}

function shiftkey_image_gallery($gallery, $echo=true){
	if($gallery == '') return false;

	$arr = explode(',', $gallery);
	$arr = array_filter($arr);
	if( !empty($arr) ){
		$count = $remain = 0;
		$moreclass = (count($arr) > 3)? ' plusmore' : '';		
		echo '<div class="row shiftkey-image-gallery'.$moreclass.'">';
		foreach ($arr as $key => $value) {
			$size = ( $count > 0 )? 'shiftkey-300x300-crop' : 'shiftkey-630x335-crop';
			$class = ( $count > 0 )? 'col-md-6 col-sm-6 col-xs-6 image'.$count : 'col-md-12 col-sm-12 col-xs-12 image'.$count;
			$image = wp_get_attachment_image_src($value, $size);
			$thumb = $image[0];
			$image = wp_get_attachment_image_src($value, 'full');
			$fullimage = $image[0];
			$title = get_the_title($value);

			if( $count > 2 ){
				echo '<a class="hidden" href="'.$fullimage.'" title="'.$title.'"></a>';
			}else{
				if(($count == 2) && (count($arr) > 3)){
					echo '<div class="'.esc_attr($class).'"><div><a href="'.esc_url($fullimage).'" title="'.esc_attr($title).'">'.(count($arr) -3).'+</a><img src="'.esc_url($thumb).'" alt="'.esc_attr($title).'"></div></div>';
				}else{
					echo '<div class="'.esc_attr($class).'"><div><a href="'.esc_url($fullimage).'" title="'.esc_attr($title).'"><img src="'.esc_url($thumb).'" alt="'.esc_attr($title).'"></a></div></div>';
				}
				
			}
			$count++;
			
		}
		
		echo '</div>';
		
	}
}
function shiftkey_page_wrapper_class($class=""){
	$classArr = array();
	$classArr[] = $class;
	$classArr[] = 'boxed-wrapper';
	if( !is_page() && ('post' != get_post_type()) ){
		$classArr[] = 'page-wrapper-'.get_post_type(); 
	}
	$classArr = array_filter($classArr);

	echo 'class="'.implode(' ', $classArr).'"';
}


function shiftkey_add_nav_menu_classes($classes, $item){

	if(get_post_status(get_option('team_archive_id')) == 'publish'){
		$mypost = get_post(get_option('team_archive_id')); 
		$title = $mypost->post_title;

		if( is_post_type_archive('team') && ($item->title == $title ) ){
		      $classes[] = 'current-menu-item current_page_item active';
		   }
	}
   
   return $classes;
}
add_filter('nav_menu_css_class' , 'shiftkey_add_nav_menu_classes' , 10 , 2);

if( !function_exists('shiftkey_team_archive_content') ):
function shiftkey_team_archive_content(){
	if( get_post_status(get_option('team_archive_id')) == 'publish' ){
		$page_id = get_option('team_archive_id'); 
		$post = get_post( $page_id );
		echo apply_filters('the_content', $post->post_content);
	}
}
endif;

function shiftkey_numeric_posts_nav($class = 'mt-80') {

    if( is_singular() )
        return;

    global $wp_query;

    /** Stop execution if there's only 1 page */
    if( $wp_query->max_num_pages <= 1 )
        return;

    $paged = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
    $max   = intval( $wp_query->max_num_pages );

    /** Add current page to the array */
    if ( $paged >= 1 )
        $links[] = $paged;

    /** Add the pages around the current page to the array */
    if ( $paged >= 3 ) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }

    if ( ( $paged + 2 ) <= $max ) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }

 
    echo '<div class="blog-page-pagination '.esc_attr($class).' b-top wow fadeInUp"><nav aria-label="Page navigation"><ul class="pagination justify-content-center">' . "\n";

    /** Previous Post Link */
    if ( get_previous_posts_link() ){
    	$nexttext = esc_attr__('Previous', 'shiftkey');
        printf( '<li class="page-item prev"><span class="page-link">%s</span></li>' . "\n", get_previous_posts_link('<i class="icon-chevron-left"></i>'.esc_attr($nexttext)) );
    }
    	

    /** Link to first page, plus ellipses if necessary */
    if ( ! in_array( 1, $links ) ) {
        $class = 1 == $paged ? ' class="active"' : '';

        printf( '<li class="page-item"><a%s href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

        if ( ! in_array( 2, $links ) )
            echo '<li class="page-item"><span class="page-link">...</span></li>';
    }

    /** Link to current page, plus 2 pages in either direction if necessary */
    sort( $links );
    foreach ( (array) $links as $link ) {
        $class = $paged == $link ? ' class="page-item active"' : ' class="page-item"';
        printf( '<li%s><a href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
    }

    /** Link to last page, plus ellipses if necessary */
    if ( ! in_array( $max, $links ) ) {
        if ( ! in_array( $max - 1, $links ) )
            echo '<li class="page-item"><span class="page-link">...</span></li>' . "\n";

        $class = $paged == $max ? ' class="active"' : '';
        printf( '<li class="page-item"><a%s href="%s" class="page-link">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
    }

    /** Next Post Link */
    if ( get_next_posts_link() ){
    	$nexttext = esc_attr__('Next', 'shiftkey');
        printf( '<li class="page-item next"><span class="page-link">%s</span></li>' . "\n", get_next_posts_link(esc_attr($nexttext).' <i class="icon-chevron-right"></i>') );
    }
    	

    echo '</ul></nav></div>' . "\n";

}

add_filter('wp_link_pages', 'shiftkey_link_pages', 10, 2);
function shiftkey_link_pages( $output, $args = '' ) {
    global $page, $numpages, $multipage, $more;

    $classes = 'Page navigation'.shiftkey_default_pagination_classes();
 
    $defaults = array(
        'before'           => '<nav class="'.esc_attr($classes).'"><ul class="pagination justify-content-left"><li class="page-item"><span class="page-link">' . __( 'Pages:', 'shiftkey' ) . '</span></li>',
        'after'            => '</ul></nav>',
        'link_before'      => '<span class="page-link">',
        'link_after'       => '</span>',
        'next_or_number'   => 'number',
        'separator'        => '',
        'nextpagelink'     => esc_attr__( 'Next', 'shiftkey'),
        'previouspagelink' => esc_attr__( 'Previous', 'shiftkey' ),
        'pagelink'         => '%',
        'echo'             => 1
    );
 
    $params = wp_parse_args( $args, $defaults );
 
    /**
     * Filters the arguments used in retrieving page links for paginated posts.
     *
     * @since 3.0.0
     *
     * @param array $params An array of arguments for page links for paginated posts.
     */
    $r = apply_filters( 'wp_link_pages_args', $params );
 
    $output = '';
    if ( $multipage ) {
        if ( 'number' == $r['next_or_number'] ) {
            $output .= $r['before'];
            for ( $i = 1; $i <= $numpages; $i++ ) {
            	if( $page == $i ){
            		$link = '<li class="page-item active">'.$r['link_before'] . str_replace( '%', $i, $r['pagelink'] ) . $r['link_after'].'</li>';
            	}else{
            		$link = '<li class="page-item">'.$r['link_before'] . str_replace( '%', $i, $r['pagelink'] ) . $r['link_after'].'</li>';
            	}
                
                if ( $i != $page || ! $more && 1 == $page ) {
                    $link = '<li class="page-item">'._wp_link_page( $i ) . $link . '</a></li>';
                }
                /**
                 * Filters the HTML output of individual page number links.
                 *
                 * @since 3.6.0
                 *
                 * @param string $link The page number HTML output.
                 * @param int    $i    Page number for paginated posts' page links.
                 */
                $link = apply_filters( 'wp_link_pages_link', $link, $i );
 
                // Use the custom links separator beginning with the second link.
                $output .= ( 1 === $i ) ? ' ' : $r['separator'];
                $output .= $link;
            }
            $output .= $r['after'];
        } elseif ( $more ) {
            $output .= $r['before'];
            $prev = $page - 1;
            if ( $prev > 0 ) {
                $link = '<li class="page-item">'._wp_link_page( $prev ) . $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a></li>';
 
                /** This filter is documented in wp-includes/post-template.php */
                $output .= apply_filters( 'wp_link_pages_link', $link, $prev );
            }
            $next = $page + 1;
            if ( $next <= $numpages ) {
                if ( $prev ) {
                    $output .= $r['separator'];
                }
                $link = '<li class="page-item">'._wp_link_page( $next ) . $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</a></li>';
 
                /** This filter is documented in wp-includes/post-template.php */
                $output .= apply_filters( 'wp_link_pages_link', $link, $next );
            }
            $output .= $r['after'];
        }
    }
 
    /**
     * Filters the HTML output of page links for paginated posts.
     *
     * @since 3.6.0
     *
     * @param string $output HTML output of paginated posts' page links.
     * @param array  $args   An array of arguments.
     */
    //$html = apply_filters( 'wp_link_pages', $output, $args );
 
   
    return $output;
}
add_filter( 'shiftkey_thumbnail_size', 'shiftkey_thumbnail_size' );
function shiftkey_thumbnail_size($size){
	$column = 3;
	if('portfolio' == get_post_type() ){
			$column = shiftkey_get_portfolio_column();
	}
	if('service' == get_post_type() ){
			$column = shiftkey_get_service_column();
	}
	if('team' == get_post_type() ){
			$column = shiftkey_get_team_column();
	}
	$size = ( $column == 2 )? 'shiftkey-630x335-crop' : $size; 
	return $size;
}

add_filter( 'shiftkey_portfolio_post_type_slug', 'shiftkey_portfolio_post_type_slug', 10, 1 );
function shiftkey_portfolio_post_type_slug($slug){
	$id = shiftkey_get_post_type_archive_page_id('portfolio');
	
	if($id){
		$slug = shiftkey_get_the_slug($id);
	}

	return $slug;
}

add_filter( 'shiftkey_team_post_type_slug', 'shiftkey_team_post_type_slug', 10, 1 );
function shiftkey_team_post_type_slug($slug){
	$id = shiftkey_get_post_type_archive_page_id('team');
	
	if($id){
		$slug = shiftkey_get_the_slug($id);
	}

	return $slug;
}

function shiftkey_footer_social_icons($args = array()){
	$arr = shiftkey_get_option( 'social_icons', shiftkey_default_social_icons() );	
	return '<!-- Social Icons -->
                <div class="footer-socials-links">
                '.shiftkey_get_social_icons($arr, $args).'                                  
                </div>
              ';
}

if( !function_exists('shiftkey_team_archive_content') ):
function shiftkey_team_archive_content(){
	$id = shiftkey_get_option('team_archive');
	if( get_post_status($id) == 'publish' ){
		$page_id = $id; 
		$post = get_post( $page_id );
		echo apply_filters('the_content', $post->post_content);
	}
}
endif;

if( !function_exists('shiftkey_portfolio_archive_content') ):
function shiftkey_portfolio_archive_content(){
	$id = shiftkey_get_option('portfolio_archive');
	if( get_post_status($id) == 'publish' ){
		$page_id = $id; 
		$post = get_post( $page_id );
		echo apply_filters('the_content', $post->post_content);
	}
}
endif;

function shiftkey_footer_widgets_social_links_args(){
	return array(
		'wrap' => 'ul',
        'wrapid' => '',
        'wrapclass' => 'foo-socials text-center clearfix',
        'linkwrapbefore' => '',
        'linkwrap' => 'li',
        'linkwrapclass' => '',
        'linkclass' => '',
        'iconprefix' => 'ico-',
        'iconclass' => '',
        'linktext' => false, 
        'display_icon' => true,
	);
}

add_action( 'perch_modules/widgets/social_links', 'shiftkey_footer_widgets_social_links' );
function shiftkey_footer_widgets_social_links(){
	global $shiftkey_options;
	$iconsArr = shiftkey_general_options_social_link();
	if( isset($shiftkey_options['social_links']) ){
		$array = $shiftkey_options['social_links'];
		$icon_list = shiftkey_supported_social_links();
		$iconsArr = array();
		foreach ($array as $key => $value) 
			$iconsArr[] =  isset($icon_list[$value]['key'])? $icon_list[$value]['key'] : '';		
	}
	
	$args = shiftkey_footer_widgets_social_links_args();
	echo '<div class="footer-socials-links">'.shiftkey_get_social_links_by_options($iconsArr, $args).'</div>';
}

include get_template_directory(). '/includes/dynamic-style.php';
include get_template_directory(). '/includes/template-assets.php';
include get_template_directory(). '/includes/comment-form.php';
include get_template_directory(). '/includes/template-tags.php';
if(function_exists('is_woocommerce')){
	include get_template_directory(). '/includes/woo-functions.php';
}