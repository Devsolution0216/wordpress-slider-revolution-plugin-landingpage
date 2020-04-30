<?php
add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

if ( !function_exists( 'shiftkey_is_meta_field_exists' ) ):
function shiftkey_is_meta_field_exists( $field_name, $post_id = NULL ){  

  if( !function_exists('rwmb_meta') ) return false;

  if( $post_id == NULL ){      
      $post_id = get_the_ID();
  }

  $my_post_meta = get_post_meta($post_id, esc_attr($field_name), false); 
  
  if ( ! empty ( $my_post_meta ) ) 
    return true;
  else 
    return false;

}
endif;

if ( !function_exists( 'shiftkey_array_search_by_key_value' ) ):
function shiftkey_array_search_by_key_value($array, $key, $value){
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, shiftkey_array_search_by_key_value($subarray, $key, $value));
        }
    }

    return $results;
}
endif;


if ( !function_exists( 'shiftkey_parse_text' ) ):
function shiftkey_parse_text( $text, $args = array( ) ) {
    if ( is_array( $args ) ) {
        extract( shortcode_atts( array(
             'tag' => 'span',
            'tagclass' => '',
            'before' => '',
            'after' => '' 
        ), $args ) );
    } //is_array( $args )
    else {
        extract( shortcode_atts( array(
            'tag' => $args,
            'tagclass' => 'theme-color',
            'before' => '',
            'after' => '' 
        ), $args ) );
    }    
    
    preg_match_all( "/\{([^\}]*)\}/", $text, $matches );
    $tagclass = trim($tagclass);
    
    if ( !empty( $matches ) ) {
        foreach ( $matches[ 1 ] as $value ) {
            $find    = "{{$value}}";
            $replace = "{$before}<{$tag} class='{$tagclass}'>{$value}</{$tag}>{$after}";
            $text    = str_replace( $find, $replace, $text );
        } //$matches[1] as $value
    }
    return  $text;
}
endif;

if ( !function_exists( 'shiftkey_get_parse_text' ) ):
function shiftkey_get_parse_text($text = '', $args = array()){

    if( $text == '' ) return false;

    extract( shortcode_atts( array(
            'highlight_text' => '',
            'highlight_text_underline' => '',
            'highlight_text_color' => '',
            'highlight_text_bg' => '', 
            'highlight_text_weight' => '', 
            'highlight_text_tag' => 'span', 
        ), $args ));

    if( $highlight_text == '' ) return $text;

    $classes = array();
    $classes[] = $highlight_text_underline;
    $classes[] = ($highlight_text_color != '')? $highlight_text_color.'-color' : '';
    $classes[] = ($highlight_text_bg != '')? $highlight_text_bg : '';
    $classes[] = ($highlight_text_weight != '')? $highlight_text_weight : '';
    $classes = array_filter($classes);

    $parse_args = array(
        'tag' => $highlight_text_tag,
        'tagclass' => implode(' ', $classes),
        'before' => '',
        'after' => ''
    );

    $parse_args = ( $highlight_text != '' )? $parse_args : array();

    return shiftkey_parse_text($text, $parse_args);
}
endif;

if( !function_exists('shiftkey_hex2rgb') ):
function shiftkey_hex2rgb( $color, $opacity='1' ) {
  $color = trim( $color, '#' );

  if ( strlen( $color ) == 3 ) {
    $r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
    $g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
    $b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
  } else if ( strlen( $color ) == 6 ) {
    $r = hexdec( substr( $color, 0, 2 ) );
    $g = hexdec( substr( $color, 2, 2 ) );
    $b = hexdec( substr( $color, 4, 2 ) );
  } else {
    return '';
  }
  if(!$opacity){
    return "{$r}, {$g}, {$b}";
  }else{
    return "rgba( {$r}, {$g}, {$b}, {$opacity} )";
  }
  
}
endif;

function perch_get_server_database_version() {
	global $wpdb;

	if ( empty( $wpdb->is_mysql ) ) {
		return array(
			'string' => '',
			'number' => '',
		);
	}

	if ( $wpdb->use_mysqli ) {
		$server_info = mysqli_get_server_info( $wpdb->dbh ); // @codingStandardsIgnoreLine.
	} else {
		$server_info = mysql_get_server_info( $wpdb->dbh ); // @codingStandardsIgnoreLine.
	}

	return array(
		'string' => $server_info,
		'number' => preg_replace( '/([^\d.]+).*/', '', $server_info ),
	);
}
function perch_let_to_num( $size ) {
	$l    = substr( $size, -1 );
	$ret  = substr( $size, 0, -1 );
	$byte = 1024;

	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
			// No break.
		case 'T':
			$ret *= 1024;
			// No break.
		case 'G':
			$ret *= 1024;
			// No break.
		case 'M':
			$ret *= 1024;
			// No break.
		case 'K':
			$ret *= 1024;
			// No break.
	}
	return $ret;
}
function perch_get_server_system_status(){
	global $wpdb;

		// Figure out cURL version, if installed.
		$curl_version = '';
		if ( function_exists( 'curl_version' ) ) {
			$curl_version = curl_version();
			$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		}

		// WP memory limit.
		$wp_memory_limit = perch_let_to_num( WP_MEMORY_LIMIT );
		if ( function_exists( 'memory_get_usage' ) ) {
			$wp_memory_limit = max( $wp_memory_limit, perch_let_to_num( @ini_get( 'memory_limit' ) ) );
		}

		

		$database_version = perch_get_server_database_version();

		// Return all environment info. Described by JSON Schema.
		return array(
			'home_url'                  => home_url(),
			'site_url'                  => get_option( 'siteurl' ),
			'wp_version'                => get_bloginfo( 'version' ),
			'wp_multisite'              => is_multisite(),
			'wp_memory_limit'           => $wp_memory_limit,
			'wp_debug_mode'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
			'wp_cron'                   => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
			'language'                  => get_locale(),
			'external_object_cache'     => wp_using_ext_object_cache(),			
			'php_version'               => phpversion(),
			'php_post_max_size'         => perch_let_to_num( ini_get( 'post_max_size' ) ),
			'php_max_execution_time'    => ini_get( 'max_execution_time' ),
			'php_max_input_vars'        => ini_get( 'max_input_vars' ),
			'curl_version'              => $curl_version,
			'suhosin_installed'         => extension_loaded( 'suhosin' ),
			'max_upload_size'           => wp_max_upload_size(),
			'mysql_version'             => $database_version['number'],
			'mysql_version_string'      => $database_version['string'],
			'default_timezone'          => date_default_timezone_get(),
			'fsockopen_or_curl_enabled' => ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ),
			'soapclient_enabled'        => class_exists( 'SoapClient' ),
			'domdocument_enabled'       => class_exists( 'DOMDocument' ),
			'gzip_enabled'              => is_callable( 'gzopen' ),
			'mbstring_enabled'          => extension_loaded( 'mbstring' ),			
		);
}
function shiftkey_get_server_invironment( $type= '' ){

    $environment      = perch_get_server_system_status();
    if( $type == 'wp_memory_limit' ){
      if ( $environment['wp_memory_limit'] < 67108864 ) {        
          return '<mark class="no"><span class="dashicons dashicons-warning"></span>' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</mark>';
        } else {
          return '<mark class="yes"><span class="dashicons dashicons-yes"></span>' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</mark>';
        }
    }

    if( $type == 'max_upload_size' ){
      if ( $environment['max_upload_size'] < 67108864 ) {
         return '<mark class="no"><span class="dashicons dashicons-warning"></span>' . esc_html( size_format( $environment['max_upload_size'] ) ) . '</mark>';
      }else{
         return '<mark class="yes"><span class="dashicons dashicons-yes"></span>' . esc_html( size_format( $environment['max_upload_size'] ) ) . '</mark>';
      }      
    }

    if( $type == 'php_post_max_size' ){
      if ( $environment['php_post_max_size'] < 67108864 ) {
         return '<mark class="no"><span class="dashicons dashicons-warning"></span>' . esc_html( size_format( $environment['php_post_max_size'] ) ) . '</mark>';
      }else{
         return '<mark class="yes"><span class="dashicons dashicons-yes"></span>' . esc_html( size_format( $environment['php_post_max_size'] ) ) . '</mark>';
      }      
    }

    if( $type == 'php_max_execution_time' ){
      if ( $environment['php_max_execution_time'] < 300 ) {
         return '<mark class="no"><span class="dashicons dashicons-warning"></span>' . esc_html( $environment['php_max_execution_time'] ) . '</mark>';
      }else{
         return '<mark class="yes"><span class="dashicons dashicons-yes"></span>' . esc_html( $environment['php_max_execution_time'] ) . '</mark>';
      }      
    } 

    if( $type == 'php_version' ){
      if ( version_compare( $environment['php_version'], '5.6', '>=' ) ) {
         return '<mark class="yes"><span class="dashicons dashicons-yes"></span>' . esc_html( $environment['php_version'] ) . '</mark>';         
      }else{
        return '<mark class="no"><span class="dashicons dashicons-warning"></span>' . esc_html( $environment['php_version'] ) . '</mark>';
      }      
    }  
 
}

function shiftkey_intro_text( $default_text ) {
  $default_text .= '<div class="ocdi__intro-text"><table class="widefat">
  <thead><tr>
  <th><h3>Check your server settings</h3></th>
  <th><h3>Common error</h3></th>
  </tr></thead>
  <tbody><tr><td>
  <p>Deactivate all cache plugin before import demo data.</p>
   <p>These defaults are not perfect and it depends on how large of an import you are making. So the bigger the import, the higher the numbers should be.</p>
  <ul>
    <li>PHP version (minimam 5.6+) '.shiftkey_get_server_invironment("php_version").'</li> 
    <li>upload_max_filesize (64MB) '.shiftkey_get_server_invironment("max_upload_size").'</li>    
    <li>memory_limit (256MB) '.shiftkey_get_server_invironment("wp_memory_limit").'</li>
    <li>max_execution_time (300) '.shiftkey_get_server_invironment("php_max_execution_time").'</li>
    <li>post_max_size (64MB) '.shiftkey_get_server_invironment("php_post_max_size").'</li>    
    </ul>
    
    </td><td>
    <h3>Server error 500</h3>
   <p>This usually indicates a poor server configuration, usually on a cheap shared hosting (low values for PHP settings, missing PHP modules, and so on. <br>
   There are two things you can do. You can contact your hosting support and ask them to update some PHP settings for your site</p>   
    <h3>Server error 504 - Gateway timeout</h3>
   <p>This means, that the server did not get a timely response and so it stopped with the current import. What you can try is to run the same import again. If you get the same error, you can try to run the same import a few times. A couple of import tries might finish the import till the end, becaue your server will be able to process the import data in smaller chunks.</p>
   <h4>Error: Not Found (404)</h4>
   <p>Sometime server blocked read permissions for demo data files. Please <a href="http://localhost/shiftkey/demos/wp-admin/themes.php?page=pt-one-click-demo-import&amp;import-mode=manual">Switch to manual import!</a> to avoid this issues.
		You can see demo data files in <strong>shiftkey/admin/demo-data</strong> folder.
	</p>
   </td>
   </tr></tbody>
   </table>
  </div>';

  return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'shiftkey_intro_text' );

if ( !function_exists( 'shiftkey_get_current_post_type' ) ):
/**
* gets the current post type in the WordPress Admin
*/
function shiftkey_get_current_post_type( ) {
    global $wpdb, $post, $typenow, $current_screen;
    //we have a post so we can just get the post type from that
    if ( $typenow )
        return $typenow;
    //check the global $typenow - set in admin.php
    //check the global $current_screen object - set in sceen.php
    elseif ( $current_screen && $current_screen->post_type )
        return $current_screen->post_type;
    //lastly check the post_type querystring
    elseif ( isset( $_REQUEST[ 'post_type' ] ) )
        return sanitize_key( $_REQUEST[ 'post_type' ] );
    elseif ( isset( $_REQUEST[ 'post' ] ) && get_post_type( $_REQUEST[ 'post' ] ) )
        return get_post_type( $_REQUEST[ 'post' ] );
    elseif ( $post && $post->post_type )
        return $post->post_type;
    //we do not know the post type!
    return null;
}
endif;

if ( !function_exists( 'shiftkey_get_terms_choices' ) ):
function shiftkey_get_terms_choices( $tax = 'category', $key = 'slug' ) {
    $terms = array( );
    if ( !taxonomy_exists( $tax ) )
        return false;
    if ( $key === 'id' )
        foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
            $terms[] = array(
                'label' => $term->name,
                'value' => $term->term_id
            );
    elseif ( $key === 'slug' )
        foreach ( (array) get_terms( $tax, array( 'hide_empty' => false ) ) as $term )
            $terms[] = array(
                'label' => $term->name,
                'value' => $term->slug
            );

    return $terms;
}
endif;

if ( !function_exists( 'shiftkey_posts_template' ) ):
    function shiftkey_posts_template( $atts, $content = null, $type = "posts" ) {
        // Prepare error var
        $error               = null;
        // Parse attributes
        $atts                = shortcode_atts( array(
             'template' => 'templates/default-loop.php',
            'id' => false,
            'posts_per_page' => get_option( 'posts_per_page' ),
            'post_type' => 'post',
            'taxonomy' => 'category',
            'tax_term' => false,
            'tax_operator' => 'IN',
            'author' => '',
            'tag' => '',
            'meta_key' => '',
            'offset' => 0,
            'order' => 'DESC',
            'orderby' => 'date',
            'post_parent' => false,
            'post_status' => 'publish',
            'ignore_sticky_posts' => 'no',
            'info' => '' 
        ), $atts, $type );
        $original_atts       = $atts;
        $author              = sanitize_text_field( $atts[ 'author' ] );
        $id                  = $atts[ 'id' ]; // Sanitized later as an array of integers
        $ignore_sticky_posts = ( bool ) ( $atts[ 'ignore_sticky_posts' ] === 'yes' ) ? true : false;
        $meta_key            = sanitize_text_field( $atts[ 'meta_key' ] );
        $offset              = intval( $atts[ 'offset' ] );
        $order               = sanitize_key( $atts[ 'order' ] );
        $orderby             = sanitize_key( $atts[ 'orderby' ] );
        $post_parent         = $atts[ 'post_parent' ];
        $post_status         = $atts[ 'post_status' ];
        $post_type           = sanitize_text_field( $atts[ 'post_type' ] );
        $posts_per_page      = intval( $atts[ 'posts_per_page' ] );
        $tag                 = sanitize_text_field( $atts[ 'tag' ] );
        $tax_operator        = $atts[ 'tax_operator' ];
        $tax_term            = sanitize_text_field( $atts[ 'tax_term' ] );
        $taxonomy            = sanitize_key( $atts[ 'taxonomy' ] );
        // Set up initial query for post
        $args                = array(
             'category_name' => '',
            'order' => $order,
            'orderby' => $orderby,
            'post_type' => explode( ',', $post_type ),
            'posts_per_page' => $posts_per_page,
            'tag' => $tag 
        );
        // Ignore Sticky Posts
        if ( $ignore_sticky_posts )
            $args[ 'ignore_sticky_posts' ] = true;
        // Meta key (for ordering)
        if ( !empty( $meta_key ) )
            $args[ 'meta_key' ] = $meta_key;
        // If Post IDs
        if ( $id ) {
            $posts_in           = array_map( 'intval', explode( ',', $id ) );
            $args[ 'post__in' ] = $posts_in;
        } //$id
        // Post Author
        if ( !empty( $author ) )
            $args[ 'author' ] = $author;
        // Offset
        if ( !empty( $offset ) )
            $args[ 'offset' ] = $offset;
        // Post Status
        $post_status = explode( ', ', $post_status );
        $validated   = array( );
        $available   = array(
             'publish',
            'pending',
            'draft',
            'auto-draft',
            'future',
            'private',
            'inherit',
            'trash',
            'any' 
        );
        foreach ( $post_status as $unvalidated ) {
            if ( in_array( $unvalidated, $available ) )
                $validated[ ] = $unvalidated;
        } //$post_status as $unvalidated
        if ( !empty( $validated ) )
            $args[ 'post_status' ] = $validated;
        // If taxonomy attributes, create a taxonomy query
        if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
            // Term string to array
            $tax_term = explode( ',', $tax_term );
            // Validate operator
            if ( !in_array( $tax_operator, array(
                 'IN',
                'NOT IN',
                'AND' 
            ) ) )
                $tax_operator = 'IN';
            $tax_args         = array(
                 'tax_query' => array(
                     array(
                         'taxonomy' => $taxonomy,
                        'field' => ( is_numeric( $tax_term[ 0 ] ) ) ? 'id' : 'slug',
                        'terms' => $tax_term,
                        'operator' => $tax_operator 
                    ) 
                ) 
            );
            // Check for multiple taxonomy queries
            $count            = 2;
            $more_tax_queries = false;
            while ( isset( $original_atts[ 'taxonomy_' . $count ] ) && !empty( $original_atts[ 'taxonomy_' . $count ] ) && isset( $original_atts[ 'tax_' . $count . '_term' ] ) && !empty( $original_atts[ 'tax_' . $count . '_term' ] ) ) {
                // Sanitize values
                $more_tax_queries           = true;
                $taxonomy                   = sanitize_key( $original_atts[ 'taxonomy_' . $count ] );
                $terms                      = explode( ', ', sanitize_text_field( $original_atts[ 'tax_' . $count . '_term' ] ) );
                $tax_operator               = isset( $original_atts[ 'tax_' . $count . '_operator' ] ) ? $original_atts[ 'tax_' . $count . '_operator' ] : 'IN';
                $tax_operator               = in_array( $tax_operator, array(
                     'IN',
                    'NOT IN',
                    'AND' 
                ) ) ? $tax_operator : 'IN';
                $tax_args[ 'tax_query' ][ ] = array(
                     'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => $terms,
                    'operator' => $tax_operator 
                );
                $count++;
            } //isset( $original_atts['taxonomy_' . $count] ) && !empty( $original_atts['taxonomy_' . $count] ) && isset( $original_atts['tax_' . $count . '_term'] ) && !empty( $original_atts['tax_' . $count . '_term'] )
            if ( $more_tax_queries ):
                $tax_relation = 'AND';
                if ( isset( $original_atts[ 'tax_relation' ] ) && in_array( $original_atts[ 'tax_relation' ], array(
                     'AND',
                    'OR' 
                ) ) )
                    $tax_relation = $original_atts[ 'tax_relation' ];
                $args[ 'tax_query' ][ 'relation' ] = $tax_relation;
            endif;
            $args = array_merge( $args, $tax_args );
        } //!empty( $taxonomy ) && !empty( $tax_term )
        // Fix for pagination
        if ( is_front_page() ) {
            $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
        } //is_front_page()
        else {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }
        $args[ 'paged' ] = $paged;
        // If post parent attribute, set up parent
        if ( $post_parent ) {
            if ( 'current' == $post_parent ) {
                global $post;
                $post_parent = $post->ID;
            } //'current' == $post_parent
            $args[ 'post_parent' ] = intval( $post_parent );
        } //$post_parent
        // Save original posts
        global $posts;
        $original_posts = $posts;
        // Query posts
        $posts          = new WP_Query( $args );
        $posts->info    = $atts;
        // Buffer output
        ob_start();
        // Search for template in stylesheet directory
        if ( file_exists( get_stylesheet_directory() . '/' . $atts[ 'template' ] ) )
            load_template( get_stylesheet_directory() . '/' . $atts[ 'template' ], false );
        // Search for template in theme directory
        elseif ( file_exists( get_template_directory() . '/' . $atts[ 'template' ] ) )
            load_template( get_template_directory() . '/' . $atts[ 'template' ], false );
        // Template not found
        else
            echo esc_attr(__( 'template not found', 'shiftkey' ));
        $output = ob_get_contents();
        ob_end_clean();
        // Return original posts
        $posts = $original_posts;
        // Reset the query
        wp_reset_postdata();
        return $output;
    }
endif;

function shiftkey_get_the_slug( $id = null ) {
    if ( empty( $id ) ):
        global $post;
        if ( empty( $post ) )
            return ''; // No global $post var available.
        $id = $post->ID;
    endif;
    $slug = basename( get_permalink( $id ) );
    return $slug;
}

function shiftkey_get_the_term_list( $id, $taxonomy, $before = '', $sep = '', $after = '', $name = true ) {
    $terms = get_the_terms( $id, $taxonomy );
    if ( is_wp_error( $terms ) )
        return $terms;
    if ( empty( $terms ) )
        return false;
    $links = array( );
    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        } //is_wp_error( $link )
        $links[ ] = ( $name ) ? $term->name : $term->slug;
    } //$terms as $term
    /**    
    * Filters the term links for a given taxonomy.    
    *    
    * The dynamic portion of the filter name, `$taxonomy`, refers    
    * to the taxonomy slug.
    * @param array $links An array of term links.    
    */
    $term_links = apply_filters( "term_links-$taxonomy", $links );
    return $before . join( $sep, $term_links ) . $after;
}

// Add Toolbar Menus
function shiftkey_admin_toolbar( ) {
    global $wp_admin_bar;
    $args = array(
         'id' => 'themeperch',
        'parent' => '',
        'title' => 'SHIFTKEY ' . SHIFTKEY_VERSION,
        'href' => '//jthemes.org/wp/shiftkey/' 
    );
    $wp_admin_bar->add_menu( $args );
    $args = array(
         'id' => 'theme_options',
        'parent' => 'themeperch',
        'title' => esc_attr__( 'Theme options', 'shiftkey' ),
        'href' => admin_url( 'themes.php?page=ot-theme-options' ),
        'target' => '_blank' 
    );
    $wp_admin_bar->add_menu( $args );

    $args = array(
         'id' => 'docs',
        'parent' => 'themeperch',
        'title' => esc_attr__( 'Documentation', 'shiftkey' ),
        'href' => '//jthemes.org/wp/shiftkey/documentation/',
        'target' => '_blank' 
    );
    $wp_admin_bar->add_menu( $args );

    $args = array(
         'id' => 'portfolio',
        'parent' => 'themeperch',
        'title' => esc_attr__( 'Envato Portfolio', 'shiftkey' ),
        'href' => 'http://themeforest.net/user/themeperch/portfolio?ref=themeperch',
        'target' => '_blank' 
    );
    //$wp_admin_bar->add_menu( $args );
    $wp_admin_bar->remove_node( 'essb' );
}
function shiftkey_admin_toolbar_remove( ) {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node( 'essb' );
}
// Hook into the 'wp_before_admin_bar_render' action
if ( is_admin() ) {
    //add_action( 'wp_before_admin_bar_render', 'shiftkey_admin_toolbar', 999 );
} //is_admin()
else {
    add_action( 'wp_before_admin_bar_render', 'shiftkey_admin_toolbar_remove', 999 );
}

function shiftkey_compress($buffer) {
    //Remove CSS comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    //Remove tabs, spaces, newlines, etc.
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}

function shiftkey_get_intermediate_image_sizes_array( ) {
    $arr         = array( );
    $image_sizes = get_intermediate_image_sizes();
    foreach ( $image_sizes as $key => $value ) {
        $arr[ $value ] = $value;
    } //$image_sizes as $key => $value
    return $arr;
}

/**
* Get size information for all currently-registered image sizes.
*
* @global $_wp_additional_image_sizes
* @uses   get_intermediate_image_sizes()
* @return array $sizes Data for all currently-registered image sizes.
*/
function shiftkey_get_image_sizes( ) {
    global $_wp_additional_image_sizes;
    $sizes = array( );
    foreach ( get_intermediate_image_sizes() as $_size ) {
        if ( in_array( $_size, array(
             'thumbnail',
            'medium',
            'medium_large',
            'large',
            'full' 
        ) ) ) {
            $sizes[ $_size ][ 'width' ]  = get_option( "{$_size}_size_w" );
            $sizes[ $_size ][ 'height' ] = get_option( "{$_size}_size_h" );
            $sizes[ $_size ][ 'crop' ]   = (bool) get_option( "{$_size}_crop" );
        } //in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) )
        elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array(
                 'width' => $_wp_additional_image_sizes[ $_size ][ 'width' ],
                'height' => $_wp_additional_image_sizes[ $_size ][ 'height' ],
                'crop' => $_wp_additional_image_sizes[ $_size ][ 'crop' ] 
            );
        } //isset( $_wp_additional_image_sizes[ $_size ] )
    } //get_intermediate_image_sizes() as $_size
    return $sizes;
}

function shiftkey_get_image_sizes_Arr( ) {
    $sizes = shiftkey_get_image_sizes();
    $arr   = array( );
    foreach ( $sizes as $key => $value ) {
        $arr[ $key ] = $key . ' - ' . $value[ 'width' ] . 'X' . $value[ 'height' ] . ' - ' . ( ( $value[ 'crop' ] ) ? 'Cropped' : '' );
    } //$sizes as $key => $value
    return $arr;
}

/**
* Filter callback to add image sizes to Media Uploader
*/
function shiftkey_display_image_size_names_muploader( $sizes ) {
    $new_sizes   = array( );
    $added_sizes = get_intermediate_image_sizes();
    // $added_sizes is an indexed array, therefore need to convert it
    // to associative array, using $value for $key and $value
    foreach ( $added_sizes as $key => $value ) {
        $new_sizes[ $value ] = $value;
    } //$added_sizes as $key => $value
    // This preserves the labels in $sizes, and merges the two arrays
    $new_sizes = array_merge( $new_sizes, $sizes );
    return $new_sizes;
}
add_filter( 'image_size_names_choose', 'shiftkey_display_image_size_names_muploader', 11, 1 );

function shiftkey_get_image_id( $image_url ) {
    global $wpdb;
    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
    return isset( $attachment[ 0 ] ) ? $attachment[ 0 ] : false;
}

/**
* Get size information for a specific image size.
*
* @uses   shiftkey_get_image_sizes()
* @param  string $size The image size for which to retrieve data.
* @return bool|array $size Size data about an image size or false if the size doesn't exist.
*/
function shiftkey_get_image_size( $size ) {
    $sizes = shiftkey_get_image_sizes();
    if ( isset( $sizes[ $size ] ) ) {
        return $sizes[ $size ];
    } //isset( $sizes[ $size ] )
    return false;
}

/**
* Get the width of a specific image size.
*
* @uses   shiftkey_get_image_size()
* @param  string $size The image size for which to retrieve data.
* @return bool|string $size Width of an image size or false if the size doesn't exist.
*/
function shiftkey_get_image_width( $size ) {
    if ( !$size = shiftkey_get_image_size( $size ) ) {
        return false;
    } //!$size = shiftkey_get_image_size( $size )
    if ( isset( $size[ 'width' ] ) ) {
        return $size[ 'width' ];
    } //isset( $size[ 'width' ] )
    return false;
}

/**
* Get the height of a specific image size.
*
* @uses   get_image_size()
* @param  string $size The image size for which to retrieve data.
* @return bool|string $size Height of an image size or false if the size doesn't exist.
*/
function shiftkey_get_image_height( $size ) {
    if ( !$size = shiftkey_get_image_size( $size ) ) {
        return false;
    } //!$size = shiftkey_get_image_size( $size )
    if ( isset( $size[ 'height' ] ) ) {
        return $size[ 'height' ];
    } //isset( $size[ 'height' ] )
    return false;
}

function shiftkey_get_button_groups( $buttons = array(), $extra_class = '' ) {
    if ( empty( $buttons ) )
        return;

    $darkcolorArr = shiftkey_default_dark_color_classes(array('prefix' => 'btn-'));   
    $darkcolortraArr = shiftkey_default_dark_color_classes(array('prefix' => 'btn-tra-'));

    $output = '';
    foreach ( $buttons as $key => $value ):
        extract( shortcode_atts( array(
            'button_type' => 'text_btn', 
            'img_btn' => SHIFTKEY_URI. '/images/googleplay.png',
            'img_btn_size' => '160',
            'icon_position' => 'icon_position-right',
            'button_text' => 'Get Started Now',
            'button_url' => '#',
            'button_target' => '_self',
            'button_style' => 'btn-theme',
            'button_size' => '',
            'icon' => 'fa fa-angle-double-right'
        ), $value ) );
        $iconClass              = array();
        $iconClass[ ]           = $icon;
        $iconClass              = array_filter( $iconClass );

        if( $button_type == 'text_btn' ){
             $buttonClass = array('btn','btn-arrow'); 
             $btntxt = shiftkey_parse_text( $button_text, array( 'tag' => 'strong') );
             $buttonClass[]         = $button_style;
            $buttonClass[]         = $button_size;
        }else{
            $icon_position = '';
            $buttonClass =  array('img-btn');
            $btntxt = '<img src="'.esc_url($img_btn).'" alt="'.esc_attr($button_text).'" class="store-btn" width="'.intval($img_btn_size).'">';
        }
        $buttonClass[] = $extra_class;

            
        if(in_array( $button_style, $darkcolorArr)){
            $buttonClass[] = 'btn-type-dark';
        }
        if(in_array( $button_style, $darkcolortraArr)){
            $buttonClass[] = 'btn-hover-type-dark';
        }
 
        
        $buttonClass            = array_filter( $buttonClass );
        $buttonAttr             = array( );
        $buttonAttr[ 'target' ] = $button_target;
        $buttonAttr[ 'href' ]   = esc_url( $button_url );
        $buttonAttr[ 'title' ]  = esc_attr( $button_text );
        $buttonAttr[ 'class' ]  = implode( ' ', $buttonClass );
        $attr                   = '';
        foreach ( $buttonAttr as $key => $value ) {
            $attr .= ' ' . $key . '="' . $value . '"';
        } //$buttonAttr as $key => $value
       
        if ( $icon != '' ) {
            $icon = '<i class="' . implode( ' ', $iconClass ) . '"></i>';
        } //$icon_shiftkey != ''
        $output .= '<a' . $attr . '><span>';
        $output .= $btntxt;
        $output .= ( $icon_position == 'icon_position-right' ) ? $icon : '';
        $output .= '</span></a>';
    endforeach;
    return $output;
}