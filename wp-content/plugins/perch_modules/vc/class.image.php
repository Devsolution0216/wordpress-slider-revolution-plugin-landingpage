<?php
class PerchVcMapImage extends PerchVcMap{
	function __construct() {        
    }

    public static function image_args_simple(){
        $array = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image source', 'perch' ),
                'param_name' => 'source',
                'std' => 'external_link',
                'value' => array(
                    __( 'Media library', 'perch' ) => 'media_library',
                    __( 'External link', 'perch' ) => 'external_link',
                    __( 'Featured Image', 'perch' ) => 'featured_image',
                ),
                'description' => __( 'Select image source.', 'perch' ),
                 'admin_label' => true,
            ),
            array(
                'type' => 'attach_image',
                'heading' => __( 'Image', 'perch' ),
                'param_name' => 'image',
                'value' => '',
                'description' => __( 'Select image from media library.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'media_library',
                ),                
                'edit_field_class' => 'vc_col-sm-8', 
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( 'External link', 'perch' ),
                'param_name' => 'custom_src',
                'value' => get_template_directory_uri(). '/images/image-01.png',
                'description' => __( 'Select external link.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),               
                'edit_field_class' => 'vc_col-sm-8', 
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'img_size',
                'std' => 'full',
                'value' => array_flip( perch_get_image_sizes_Arr() ),
                'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => array(
                        'media_library',
                        'featured_image',
                    ),
                ),
                'edit_field_class' => 'vc_col-sm-4', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'external_img_size',
                'value' => '',
                'description' => __( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
                'edit_field_class' => 'vc_col-sm-4', 
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image alt text', 'perch' ),
                'param_name' => 'external_img_alt',
                'value' => 'External image',
                'description' => __( 'Enter text for image caption.', 'perch' ),
                'edit_field_class' => 'vc_col-sm-6',
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Caption', 'perch' ),
                'param_name' => 'caption',
                'value' => '',
                'description' => __( 'Enter text for image caption.', 'perch' ),
                'edit_field_class' => 'vc_col-sm-6',
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'On click action', 'perch' ),
                'param_name' => 'onclick',
                'value' => array(
                    __( 'None', 'perch' ) => '',
                    __( 'Link to large image', 'perch' ) => 'img_link_large',
                    __( 'Open prettyPhoto', 'perch' ) => 'link_image',
                    __( 'Open custom link', 'perch' ) => 'custom_link',
                   // __( 'Zoom', 'perch' ) => 'zoom',
                    __( 'Video', 'perch' ) => 'video',
                ),
                'description' => __( 'Select action for click action.', 'perch' ),
                'std' => '',
                'edit_field_class' => 'vc_col-sm-8', 
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Force image to overflow container?', 'perch' ),
                'param_name' => 'max_width',
                'description' => __( 'Checked to force image to overflow container.', 'perch' ),
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,
                'edit_field_class' => 'vc_col-sm-4', 
            ),
            array(
                'type' => 'href',
                'heading' => __( 'Video link', 'perch' ),
                'param_name' => 'video_link',
                'value' => 'https://www.youtube.com/embed/SZEflIVnhH8',
                'description' => __( 'Enter URL if you want this image to have a popup video link', 'perch' ),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'video',
                ),
                'edit_field_class' => 'vc_col-sm-8',
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Video icon color', 'perch' ),
                'param_name' => 'icon_class',               
                'value' => perch_vc_global_color_options(),
                'std' => 'tra',
                'description' => '',
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'video',
                ),
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'href',
                'heading' => __( 'Image link', 'perch' ),
                'param_name' => 'link',
                'description' => __( 'Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'perch' ),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'custom_link',
                ),
                'edit_field_class' => 'vc_col-sm-8',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Link Target', 'perch' ),
                'param_name' => 'img_link_target',
                'value' => perch_target_param_list(),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => array(
                        'custom_link',
                        'img_link_large',
                    ),
                ),
                'edit_field_class' => 'vc_col-sm-4',
            ),
        );

        return $array;
    }

    public static function image_args(){
         $array = array(
                array(
                'type' => 'textfield',
                'heading' => __( 'Widget title', 'perch' ),
                'param_name' => 'image_title',
                'description' => __( 'Enter text used as widget title (Note: located above content element).', 'perch' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image source', 'perch' ),
                'param_name' => 'source',
                'std' => 'external_link',
                'value' => array(
                    __( 'Media library', 'perch' ) => 'media_library',
                    __( 'External link', 'perch' ) => 'external_link',
                    __( 'Featured Image', 'perch' ) => 'featured_image',
                ),
                'description' => __( 'Select image source.', 'perch' ),
            ),
            array(
                'type' => 'attach_image',
                'heading' => __( 'Image', 'perch' ),
                'param_name' => 'image',
                'value' => '',
                'description' => __( 'Select image from media library.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'media_library',
                ),
                'admin_label' => true,
            ),
            array(
                'type' => 'image_upload',
                'heading' => __( 'External link', 'perch' ),
                'param_name' => 'custom_src',
                'value' => get_template_directory_uri(). '/images/image-01.png',
                'description' => __( 'Select external link.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
                'admin_label' => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'img_size',
                'std' => 'full',
                'value' => array_flip( perch_get_image_sizes_Arr() ),
                'description' => __( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => array(
                        'media_library',
                        'featured_image',
                    ),
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image size', 'perch' ),
                'param_name' => 'external_img_size',
                'value' => '',
                'description' => __( 'Enter image size in pixels. Example: 200x100 (Width x Height).', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image alt text', 'perch' ),
                'param_name' => 'external_img_alt',
                'value' => 'External image',
                'description' => __( 'Enter text for image caption.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Caption', 'perch' ),
                'param_name' => 'caption',
                'value' => '',
                'description' => __( 'Enter text for image caption.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Add caption?', 'perch' ),
                'param_name' => 'add_caption',
                'description' => __( 'Add image caption.', 'perch' ),
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => array(
                        'media_library',
                        'featured_image',
                    ),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image alignment', 'perch' ),
                'param_name' => 'alignment',
                'value' => array(
                    __( 'Inherit', 'perch' ) => '',
                    __( 'Left', 'perch' ) => 'left',
                    __( 'Right', 'perch' ) => 'right',
                    __( 'Center', 'perch' ) => 'center',
                ),
                'description' => __( 'Select image alignment.', 'perch' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image style', 'perch' ),
                'param_name' => 'style',
                'value' => vc_get_shared( 'single image styles' ),
                'description' => __( 'Select image display style.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => array(
                        'media_library',
                        'featured_image',
                    ),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image style', 'perch' ),
                'param_name' => 'external_style',
                'value' => vc_get_shared( 'single image external styles' ),
                'description' => __( 'Select image display style.', 'perch' ),
                'dependency' => array(
                    'element' => 'source',
                    'value' => 'external_link',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Border color', 'perch' ),
                'param_name' => 'border_color',
                'value' => vc_get_shared( 'colors' ),
                'std' => '',
                'dependency' => array(
                    'element' => 'style',
                    'value' => array(
                        'vc_box_border',
                        'vc_box_border_circle',
                        'vc_box_outline',
                        'vc_box_outline_circle',
                        'vc_box_border_circle_2',
                        'vc_box_outline_circle_2',
                    ),
                ),
                'description' => __( 'Border color.', 'perch' ),
                'param_holder_class' => 'vc_colored-dropdown',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Border color', 'perch' ),
                'param_name' => 'external_border_color',
                'value' => vc_get_shared( 'colors' ),
                'std' => '',
                'dependency' => array(
                    'element' => 'external_style',
                    'value' => array(
                        'vc_box_border',
                        'vc_box_border_circle',
                        'vc_box_outline',
                        'vc_box_outline_circle',
                    ),
                ),
                'description' => __( 'Border color.', 'perch' ),
                'param_holder_class' => 'vc_colored-dropdown',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Force image to overflow container?', 'perch' ),
                'param_name' => 'max_width',
                'description' => __( 'Checked to force image to overflow container.', 'perch' ),
                'value' => array( __( 'Yes', 'perch' ) => 'yes' ),  
                'admin_label' => true,
            ),            
            array(
                'type' => 'dropdown',
                'heading' => __( 'On click action', 'perch' ),
                'param_name' => 'onclick',
                'value' => array(
                    __( 'None', 'perch' ) => '',
                    __( 'Link to large image', 'perch' ) => 'img_link_large',
                    __( 'Open prettyPhoto', 'perch' ) => 'link_image',
                    __( 'Open custom link', 'perch' ) => 'custom_link',
                    __( 'Zoom', 'perch' ) => 'zoom',
                    __( 'Video', 'perch' ) => 'video',
                ),
                'description' => __( 'Select action for click action.', 'perch' ),
                'std' => '',
                'group' => __('On click action', 'perch'),
            ),
            array(
                'type' => 'href',
                'heading' => __( 'Video link', 'perch' ),
                'param_name' => 'video_link',
                'value' => 'https://www.youtube.com/embed/SZEflIVnhH8',
                'description' => __( 'Enter URL if you want this image to have a popup video link', 'perch' ),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'video',
                ),
                'group' => __('On click action', 'perch'),
            ),
            array(
                 'type' => 'dropdown',
                'heading' => __( 'Video icon color', 'perch' ),
                'param_name' => 'icon_class',               
                'value' => perch_vc_global_color_options(),
                'std' => 'preset',
                'description' => '',
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'video',
                ), 
                'group' => __('On click action', 'perch'),
            ),
            array(
                'type' => 'href',
                'heading' => __( 'Image link', 'perch' ),
                'param_name' => 'link',
                'description' => __( 'Enter URL if you want this image to have a link (Note: parameters like "mailto:" are also accepted).', 'perch' ),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => 'custom_link',
                ),
                'group' => __('On click action', 'perch'),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Link Target', 'perch' ),
                'param_name' => 'img_link_target',
                'value' => perch_target_param_list(),
                'dependency' => array(
                    'element' => 'onclick',
                    'value' => array(
                        'custom_link',
                        'img_link_large',
                    ),
                ),
                'group' => __('On click action', 'perch'),
            ),
         );       

        return $array;   
    }

    // Element HTML
    public static function image_html( $atts ) {

        $shortcode_atts = $atts;
        $map_arr = self::image_args();

        $args = PerchVcMap::get_vc_element_atts_array($map_arr);
        extract($args);
        $atts = array_merge( $args, $atts );

        extract($atts);
       
      
        $default_src = vc_asset_url( 'vc/no_image.png' );

        // backward compatibility. since 4.6
        if ( empty( $onclick ) && isset( $img_link_large ) && 'yes' === $img_link_large ) {
            $onclick = 'img_link_large';
        } elseif ( empty( $atts['onclick'] ) && ( ! isset( $atts['img_link_large'] ) || 'yes' !== $atts['img_link_large'] ) ) {
            $onclick = 'custom_link';
        }

        if ( 'external_link' === $source ) {
            $style = $external_style;
            $border_color = $external_border_color;
        }
        
        $border_color = ( '' !== $border_color ) ? ' vc_box_border_' . $border_color : '';

        $img = false;

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

        $el_class = PerchVcMap::getExtraClass( $el_class );

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

            case 'video':
                $link = $video_link;
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

        // backward compatibility
        if ( vc_has_class( 'prettyphoto', $el_class ) ) {
            $el_class = vc_remove_class( 'prettyphoto', $el_class );
        }

        $wrapperClass = 'vc_single_image-wrapper ' . $style . ' ' . $border_color;

        if( $max_width == 'yes' ){
            $wrapperClass .= ' max-width-none';
        }

        $video_icon = '';
        if( $onclick == 'video' ){
            $wrapperClass .= ' video-popup1 video-preview';
            $video_icon = '<!-- Play Icon -->                                   
                <div class="video-btn play-icon-'.$icon_class.' wow fadeInUp" data-wow-delay="700ms">    
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

        $spacing_classes = array(
                $mtop, 
                $mbottom,
                $pleft,  
                $pright, 
                ($alignment != '')? 'vc_align_' . $alignment : '' ,    
            );

        $class_to_filter = 'wpb_single_image ' . PerchVcMap::getCSSAnimation( $css_animation, $atts ). ' '. implode(' ', $spacing_classes);
        $class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . PerchVcMap::getExtraClass( $el_class );

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, 'perch_image', $atts );
        if( $max_width == 'yes' ){
            $css_class .= ' max-width-none';
        }
        if( $onclick == 'video' ){
            $css_class .= ' video-preview';
        }

        $shortcode_atts['css_animation'] = $css_animation;
        $css_class = PerchVcMap::periodic_getCSSAnimation( $css_class, 'image', $shortcode_atts );

        if ( in_array( $source, array( 'media_library', 'featured_image' ) ) && 'yes' === $add_caption ) {
            $img_id = apply_filters( 'wpml_object_id', $img_id, 'attachment' );
            $post = get_post( $img_id );
            $caption = $post->post_excerpt;
        } else {
            if ( 'external_link' === $source ) {
                $add_caption = 'yes';
            }
        }

        if ( 'yes' === $add_caption && '' !== $caption ) {
            $html .= '<figcaption class="vc_figure-caption">' . esc_html( $caption ) . '</figcaption>';
        }
        $wrapper_attributes = array();
        if ( ! empty( $el_id ) ) {
            $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
        }

       $wrapper_attributes = PerchVcMap::periodic_wrapperAttributes( $wrapper_attributes, 'image', $atts);;

        /*$output = '
            <div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
                ' . $image_title . '               
                ' . $html . '                
            </div>
        ';*/

       $output = $html;

     
         
        return force_balance_tags($output);
         
    }
   
}
new PerchVcMapImage();