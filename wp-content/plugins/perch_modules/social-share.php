<?php

if( !function_exists('perch_modules_social_share_links') ):
function perch_modules_social_share_links() {
	global $post;

	$twitter = 'http://twitter.com/home/?status='.get_the_title($post->ID).' &amp; '. get_permalink($post->ID);
	$facebook = 'http://www.facebook.com/sharer.php?u='.get_permalink($post->ID).'&amp;t='.get_the_title($post->ID);
	$linkedin = 'http://www.linkedin.com/shareArticle?mini=true&amp;title='.get_the_title($post->ID).'&amp;url='. get_permalink($post->ID);
	$gplus = 'https://plus.google.com/share?url='. get_permalink($post->ID);
	$args = array(
		'facebook' => array(
			'url' => $facebook,
			'icon_class' => 'fa fa-facebook-f',
			'title' => '',
			'class' => 'ico-facebook'
		),
		'twitter' => array(
			'url' => $twitter,
			'icon_class' => 'fa fa-twitter',
			'title' => '',
			'class' => 'ico-twitter'
		),
		'gplus' => array(
			'url' => $twitter,
			'icon_class' => 'fab  fa-google-plus-g',
			'title' => '',
			'class' => 'ico-google-plus'
		),
		'linkedin' => array(
			'url' => $linkedin,
			'icon_class' => 'fab fa-linkedin-in',
			'title' => '',
			'class' => 'ico-google-plus'
		)
	);

	$args = apply_filters( 'perch_modules/social_share_links', $args );

	return $args;
}
endif;

if( !function_exists('perch_modules_social_share') ):
function perch_modules_social_share( $icon = true, $args = array() ) {		
	$args = shortcode_atts(array(
		'before' => '',
        'wrap' => 'ul',
        'wrapclass' => '',
        'linkwrapbefore' => '',
        'linkwrap' => 'li',
        'linkwrapclass' => '',
        'linkclass' => '',
        'iconprefix' => '',
        'iconclass' => '',
        'linktext' => true, 
        'linktextbefore' => '', 
        'linktextafter' => '', 
        'icon' => true, 
        'after' => ''
    ), $args);

	$links = perch_modules_social_share_links();

	if($icon){
		$args['linkclass'] = 'share-ico';		
    }else{
    	$args['linkclass'] = '';   	
    }


    $output = perch_modules_generate_social_icons($links, $args);
    return $output;    
	
}
endif;

function perch_modules_generate_social_icons( $social_icons = array( ), $args = array( ) ) {
    if ( empty( $social_icons ) )
        return;    
    extract($args);

    $output = $before;

    $output .= ( $wrap != '' ) ? '<' . esc_attr( $wrap ) . ( ( $wrapclass != '' ) ? ' class="' . esc_attr( $wrapclass ) . '"' : '' ) . '>' : '';
    $output .= ( $linkwrapbefore != '' ) ? wpautop( $linkwrapbefore ) : '';
    $linkbefore = ( $linkwrap != '' ) ? '<' . esc_attr( $linkwrap ) . ( ( $linkwrapclass != '' ) ? ' class="' . esc_attr( $linkwrapclass ) . '"' : '' ) . '>' : '';
    $linkafter  = ( $linkwrap != '' ) ? '</' . esc_attr( $linkwrap ) . '>' : '';

    $output .= apply_filters('perch_modules/generate_social_icons/before', '', $args);
    foreach ( $social_icons as $key => $value ) {
    	$value = shortcode_atts( array(
	        'url' => '',
			'icon_class' => '',
			'title' => '',
			'class' => ''
	    ), $value );
    	extract($value);
       

        $_linkclass = array($class);
        $_linkclass[]  = ( $linkclass != '' ) ? esc_attr( $linkclass ) : '';
        $_linkclass[]  = ( $iconprefix != '' ) ? esc_attr($iconprefix).'-'. sanitize_title($title) : '';
        $_linkclass = array_filter($_linkclass);
        if( !empty($_linkclass) ) $linkclass = implode(' ', $_linkclass);        

        $iconhtml = ($icon)? '<i class="fa ' . esc_attr( $icon_class ) . '"></i>' : '';
        $linktexthtml =  ( $linktext ) ? $linktextbefore . esc_attr( $title ) . $linktextafter : ''; 

        $output .= $linkbefore . 
        '<a target="_blank" href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" class="' . trim($linkclass) . '">
          '.$iconhtml.'
          '.$linktexthtml.'
          </a>' 
      . $linkafter;
    } //$social_icons as $key => $value
    $output .= apply_filters('perch_modules/generate_social_icons/after', '', $args);

    $output .= ( $wrap != '' ) ? '</' . esc_attr( $wrap ) . '>' : '';
    $output .= $after;

    return $output;
}
