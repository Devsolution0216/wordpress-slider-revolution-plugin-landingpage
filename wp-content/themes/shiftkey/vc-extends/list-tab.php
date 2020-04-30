<?php
if(class_exists('PerchVcMap')):
/*
Element Description: List
*/

// Element Class 
class ShiftkeyList_tab extends PerchVcMap {
     
    private $base = 'perch_list_tab';

    private $title = 'List tab';
    
    function __construct() {
        // vc map inits
        add_action( 'init', array( $this, 'vc_mapping' ) );

        // Shortcode filter
        add_filter( $this->base, array( $this, 'perch_list_tab_output' ), 30, 2 ); 
    }

   

    // Title element mapping
    private function vc_map_args(){        
        $array = array();        
        
        $array = array_merge($array, self::list_tab_group_args());
        $array = array_merge($array, PerchVcMap::element_common_args());
         
        $array = apply_filters( 'perch_modules/vc/'.$this->base , $array);

        return $array;
    } 
     
     
    // Element HTML
    public function perch_list_tab_output( $atts, $content = NULL ) {
        $map_arr = self::vc_mapping(true);
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 
        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $wrapper_attributes = perch_modules_shortcode_wrapper_attributes($atts, $this->base );               

        // Used for periodic animation
        PerchVcMap::periodic_animation_start($atts);
      
        // Fill $html var with data
        $html ='
        <div '. implode( ' ', $wrapper_attributes ).'>                      
            '.self::list_tab_group_html($atts).'     
        </div>';        

        PerchVcMap::periodic_animation_end();     
         
        return wpb_js_remove_wpautop($html);
         
    }

    public function perch_list_tab_slide_output( $atts, $content = NULL ) {
        $map_arr = self::vc_map_args();
        $args = PerchVcMap::get_vc_element_atts_array($map_arr, $content); 

        // Params extraction
        $atts = shortcode_atts( $args, $atts );

        $html ='<div class="perch-slide-item">';
        $html .= self::perch_list_tab_output($atts);
        $html .='</div>';

        return $html;
    }


    // Element Mapping
    public function vc_mapping( $return = false ) {
        $params = $this->vc_map_args();
        if($return) {
            return $params;  
        }        
       
       $vc_map = array(
                'class' => perch_shortcodes_vc_class(),
                'category' => perch_shortcodes_vc_category(),
                'base' => $this->base,
                'name' => $this->title,                
                'description' => __('Display title & subtitle', 'shiftkey'),                 
                'params' => $params,
                //'js_view' => 'PerchVcElementPreview',
                //'custom_markup' => '<div class="perch_vc_element_container">{{title}}</div>',  
                //'admin_enqueue_js' =>   array( PERCH_MODULES_URI. '/assets/js/vc-admin-scripts.js'),       
            ); 
       
        
        vc_map( $vc_map );
       
        
    }

    public static function list_tab_args(){
        $icon_args = PerchVcMapImage::image_args_simple();
        $array = array_merge(array(                               
                array(
                    'type' => 'textfield',
                    'heading' => __( 'List title', 'shiftkey' ),
                    'param_name' => 'title',
                    'description' => '',
                    'value' => 'The easy element for creativity & design',
                    'admin_label' => true 
                ),
                 array(
                    'type' => 'textarea',
                    'heading' => __( 'Description', 'shiftkey' ),
                    'param_name' => 'subtitle',
                    'description' => '',
                    'value' => '',
                ),

            ), $icon_args);        

        

        return $array;
    }

    public static function list_tab_group_args(){
        $array = array(
            array(
                'type' => 'param_group',
                'save_always' => true,
                'heading' => __( 'List Group', 'shiftkey' ),
                'param_name' => 'list_group',                
                'value' => urlencode( json_encode( array(
                    array(                      
                        'source' => 'external_link',
                        'custom_src' => get_template_directory_uri(). '/images/image-02.png',                        
                         'title' => 'Feature Integration',
                         'subtitle' => 'Semper lacus cursus porta, feugiat primis in luctus ultrice tellus potenti neque dolor in primis congue',
                    ),
                    array(                      
                        'source' => 'external_link',
                        'custom_src' => get_template_directory_uri(). '/images/image-04.png',  
                        'onclick' => 'video',
                        'video_link' => '//www.youtube.com/watch?v=7e90gBu4pas', 
                        'icon_class' => 'blue',                     
                         'title' => 'Comments and Mentions',
                         'subtitle' => 'Lacus cursus porta,feugiat primis congue magna purus at pretium ligula rutrum luctus and ultrice tellus',
                    ),
                    array(                      
                        'source' => 'external_link',
                        'custom_src' => get_template_directory_uri(). '/images/image-17.png',                        
                         'title' => 'Elegant User Interface',
                         'subtitle' => 'Luctus congue magna at pretium purus pretium ligula rutrum neque incidunt tempor laoreet ipsum rhoncus, tempor posuere ligula varius donec purus feugiat',
                    ),
                ) ) ),
                'params' => self::list_tab_args()
            ),
            PerchVcMapListGroup::list_group_typography('title', 'List tab title', 'tag:h5|size:sm'),
            PerchVcMapListGroup::list_group_typography('subtitle', 'List tab Sub-Title', 'tag:p'),
        );        

       

        return $array;
    }

    public static function list_tab_html($value){
        $map_arr = self::list_tab_group_args();       
        $_atts = $typo_atts = $value;
        $typo_atts['periodic_animation'] = '';
        $typo_atts['css_animation'] = '';
        extract($value);

      

        $output = '';

        $new_title = isset($value['title'])? $value['title'] : '';
        $new_subtitle = isset($value['subtitle'])? $value['subtitle'] : '';
        $typo_atts['title'] =  $new_title;                   
        $typo_atts['subtitle'] =  $new_subtitle; 
        $tag = 'div';
        $tag = apply_filters( 'perch/list_tab/tag', $tag );
       

        if( ( $new_title != '') || ( $new_subtitle != '') ):                                      
            $li_attr = array();
            $classes = array('ibox-5');             
            
            $classes = self::periodic_getCSSAnimation($classes, 'list_li', $_atts);               
            
           
            $classes = array_filter($classes);
            $li_attr[] = (!empty($classes))? 'class="'.implode(' ', $classes).'"' : ''; 
            $li_attr = self::periodic_wrapperAttributes($li_attr, 'list_li', $_atts );                   
            $li_attributes = implode( ' ', $li_attr );
            //$_class = ($process_style == 3 || $process_style == 1)? 'pbox-icon' : 'step-icon';
            $icon_html = PerchVcMapIcons::icon_html($typo_atts);
            $title_html = perch_modules_get_vc_param_html( 'title', $typo_atts );                    
            $subtitle_html = perch_modules_get_vc_param_html('subtitle', $typo_atts);  
            
                         

            $output = "<{$tag} {$li_attributes}>               
                {$title_html}
                {$subtitle_html}                
            </{$tag}>";           
        endif;

        return $output;        
    }

    public static function list_tab_group_html($atts){
        $_atts = $atts;       
        // typo attr
        $typo_atts = $atts;       

        extract($atts);      

        $paramsArr = (function_exists('vc_param_group_parse_atts'))? vc_param_group_parse_atts($list_group) : array();

        $classes = array('row');          
        $classes = array_filter($classes);
        $wrapper_attributes[] = (!empty($classes))?'class="'.implode(' ', $classes).'"' : '';        
        $wrapper_attributes = array_filter($wrapper_attributes);  
        $wrapper_attributes = apply_filters( 'perch_modules/wrapper_attributes', $wrapper_attributes, $atts);        

        $textblock = $imageblock = '';
        
        if(!empty($paramsArr)):
            $delay = 200;
            $paramsArr = array_filter($paramsArr);
            $count = 1;            
            foreach ($paramsArr as $key => $value) {
            	$textblock_active = ( $count == 1 )? ' active' : ''; 
                $textblock .= '<a id="tab'.$count.'-list" class="list-group-item list-group-item-action'.$textblock_active.'" data-toggle="list" href="#tab-'.$count.'" role="tab" aria-controls="tab-'.$count.'">'.self::list_tab_html(array_merge($atts, $value)).'</a>';  

              
        		$max_width = ''; extract($value);
                $imageblock_active = ( $count == 1 )? ' show active' : ''; 
                $imageblock_active .= ( $max_width == 'yes' )? ' max-width-none' : ''; 
                $imageblock .= '<div id="tab-'.$count.'" class="tab-pane'.$imageblock_active.'" role="tabpanel" aria-labelledby="tab1-list">'.self::List_group_image_html($value).'</div>';    

                $count++;
            }
        endif;
        $output = '<div '.implode( ' ', $wrapper_attributes ).'><div class="row d-flex align-items-center">';
        $output .= '<div class="col-md-6"><div class="txt-block pc-45"><div id="list-tab" class="list-group" role="tablist">'.$textblock.'</div></div></div>	<!-- END TEXT BLOCK -->';
        

		$output .= '<div class="col-md-6"><div class="info-img text-center pr-45"><div id="nav-tabContent" class="tab-content">'.$imageblock.'</div></div></div><!-- END IMAGE BLOCK -->';

        $output .= '</div></div>';

        return $output;

    }

    public static function List_group_image_html($atts, $content = NULL){

    	$map_arr = PerchVcMapImage::image_args();
    	$args = PerchVcMap::get_vc_element_atts_array($map_arr);
        extract($args);
        extract($atts);
        $html = $el_class = '';

        $default_src = vc_asset_url( 'vc/no_image.png' );

        switch ( $source ) {
            case 'media_library':
            case 'featured_image':

                if ( 'featured_image' === $source ) {
                    $post_id = get_the_ID();
                    if ( $post_id && has_post_thumbnail( $post_id ) ) {
                        $img_id = get_post_thumbnail_id( $post_id );
                    } else {
                        $img_id = 0;
                    }
                } else {
                    $img_id = preg_replace( '/[^\d]/', '', $image );
                }

                // set rectangular
                if ( preg_match( '/_circle_2$/', $style ) ) {
                    $style = preg_replace( '/_circle_2$/', '_circle', $style );
                    $img_size = $this->getImageSquareSize( $img_id, $img_size );
                }

                if ( ! $img_size ) {
                    $img_size = 'medium';
                }

                $img = wpb_getImageBySize( array(
                    'attach_id' => $img_id,
                    'thumb_size' => $img_size,
                    'class' => 'vc_single_image-img img-fluid',
                ) );

                // don't show placeholder in public version if post doesn't have featured image
                if ( 'featured_image' === $source ) {
                    if ( ! $img && 'page' === vc_manager()->mode() ) {
                        return;
                    }
                }

                break;

            case 'external_link':
                $dimensions = vc_extract_dimensions( $external_img_size );
                $hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

                $custom_src = $custom_src ? esc_attr( $custom_src ) : $default_src;

                $alt = isset($atts['external_img_alt'])? esc_attr($atts['external_img_alt']) : 'External image link';

                $img = array(
                    'thumbnail' => '<img class="vc_single_image-img img-fluid" ' . $hwstring . ' src="' . $custom_src . '" alt="'.esc_attr($alt).'" />',
                );
                break;

            default:
                $img = false;
        }

        if ( ! $img ) {
            $img['thumbnail'] = '<img class="vc_img-placeholder vc_single_image-img img-fluid" src="' . $default_src . '" />';
        }

        // backward compatibility
        if ( vc_has_class( 'prettyphoto', $el_class ) ) {
            $onclick = 'link_image';
        }

        // backward compatibility. will be removed in 4.7+
        if ( ! empty( $atts['img_link'] ) ) {
            $link = $atts['img_link'];
            if ( ! preg_match( '/^(https?\:\/\/|\/\/)/', $link ) ) {
                $link = 'http://' . $link;
            }
        }

        // backward compatibility
        if ( in_array( $link, array( 'none', 'link_no' ) ) ) {
            $link = '';
        }

        $a_attrs = array();

        switch ( $onclick ) {
            case 'img_link_large':

                if ( 'external_link' === $source ) {
                    $link = $custom_src;
                } else {
                    $link = wp_get_attachment_image_src( $img_id, 'large' );
                    $link = $link[0];
                }

                break;

            case 'link_image':
                wp_enqueue_script( 'prettyphoto' );
                wp_enqueue_style( 'prettyphoto' );

                $a_attrs['class'] = 'prettyphoto';
                $a_attrs['data-rel'] = 'prettyPhoto[rel-' . get_the_ID() . '-' . rand() . ']';

                // backward compatibility
                if ( vc_has_class( 'prettyphoto', $el_class ) ) {
                    // $link is already defined
                } elseif ( 'external_link' === $source ) {
                    $link = $custom_src;
                } else {
                    $link = wp_get_attachment_image_src( $img_id, 'large' );
                    $link = $link[0];
                }

                break;

            case 'custom_link':
                // $link is already defined
                break;
         
            case 'zoom':
                wp_enqueue_script( 'vc_image_zoom' );

                if ( 'external_link' === $source ) {
                    $large_img_src = $custom_src;
                } else {
                    $large_img_src = wp_get_attachment_image_src( $img_id, 'large' );
                    if ( $large_img_src ) {
                        $large_img_src = $large_img_src[0];
                    }
                }

                $img['thumbnail'] = str_replace( '<img ', '<img data-vc-zoom="' . $large_img_src . '" ', $img['thumbnail'] );

                break;
        }
        

        $wrapperClass = '';
       
        $video_icon = '';
        if( $onclick == 'video' ){
        	$link = $video_link;
            $wrapperClass .= 'video-popup2 video-preview';
            $video_icon = '<!-- Play Icon -->                                   
                <div class="video-btn play-icon-'.$icon_class.' fadeInUp">    
                    <div class="video-block-wrapper">
                        <i class="fas fa-play"></i>
                    </div>
                </div>';
        }
        $_attributes = array();
        

        if ( $link ) {
            $a_attrs['href'] = $link;
            $a_attrs['target'] = $img_link_target;
            if ( ! empty( $a_attrs['class'] ) ) {
                $wrapperClass .= ' ' . $a_attrs['class'];
                unset( $a_attrs['class'] );
            }
            $html = '<a ' . vc_stringify_attributes( $a_attrs ) . ' class="' . $wrapperClass . '" '.implode(' ', $_attributes).'>' .$video_icon. $img['thumbnail'] . '</a>';
        } else {
            $html = $img['thumbnail'];
        }


        return $html;
    }
    
    
     
} // End Element Class 
 
// Element Class Init
new ShiftkeyList_tab();
endif;