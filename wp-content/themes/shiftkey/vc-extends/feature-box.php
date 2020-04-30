<?php
add_filter('perch_modules/perch_feature_box/name', 'shiftkey_service_box_name', 100);
function shiftkey_service_box_name(){	
	return esc_attr__( 'Services Box', 'shiftkey' );
}

add_filter( 'perch_modules/vc/perch_feature_box', 'shiftkey_vc_feature_box_default_args' );
function shiftkey_vc_feature_box_default_args( $args ){
	$default = array(
		'display_as' => 'sbox-1',
		'icon_tonicons' => 'flaticon-080-shield',
		'icon_color' => 'grey-color',
		'title' => 'Concrete Security',
		'title_font_container' => 'tag:h5|size:sm',        
        'subtitle' => 'Porta semper lacus cursus, feugiat primis ultrice in ligula risus auctor tempus feugiat dolor impedit felis magna dolor vitae ',
        'subtitle_font_container' => 'tag:p|text_color:grey-color',   
        'el_class' => 'sbox',      
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}

add_filter( 'perch_modules/feature_box/output', 'shiftkey_vc_feature_box_output', 10, 3 );
function shiftkey_vc_feature_box_output( $output, $args, $atts ){
	if( empty($args) ) return $output;
	if( empty($atts) ) return $output;		
	extract($atts);
    extract($args);
    if( $display_as == 'sbox-1'){
        if($icon_type == 'flaticon') {
            $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
        }
        $output = '<div '. implode( ' ', $wrapper_attributes ).'>           
            '.$icon_html.'<!-- Icon  -->
            <div class="'.$display_as.'-txt">
            '.$title_html.'<!-- Title -->                                   
            '.$subtitle_html.'<!-- Text -->
            </div>      
        </div>';        
    }elseif( $display_as == 'sbox-2'){
            if($icon_type == 'flaticon') {        
                $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                    <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
                 </span>';           
            }
    	$output = '<div '. implode( ' ', $wrapper_attributes ).'>    		
    		'.$icon_html.'<!-- Icon  -->
			<div class="sbox-2-txt">
			'.$title_html.'<!-- Title -->									
			'.$subtitle_html.'<!-- Text -->
			</div>		
		</div>';
        
    }elseif( $display_as == 'sbox-4'){
        if($icon_type == 'flaticon') {
            $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
         }
         $output = '<div '. implode( ' ', $wrapper_attributes ).'>
         	'.$icon_html.'<!-- Icon  -->
             <div class="'.$display_as.'-txt">
              '.$title_html.'<!-- Title -->
              '.$subtitle_html.'<!-- Text -->         
             </div>            
            '.$buttons_html.'<!-- Title -->                 
        </div>';
    }elseif( $display_as == 'sbox-5'){
        if($icon_type == 'flaticon') {
            $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
         }
         $output = '<div '. implode( ' ', $wrapper_attributes ).'>          
            '.$icon_html.'<!-- Icon  -->
            
            '.$title_html.'<!-- Title -->
            '.$subtitle_html.'<!-- Text -->                 
        </div>';
    }elseif ($display_as == 'sbox-5') {
       if ($icon_type == 'flaticon') {
          
          $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
       }
       $output =  '<div '. implode( ' ', $wrapper_attributes ).'>
                    <div class="sbox-5">
                        <div class="'.$display_as.'-icon">
                        '.$icon_html.'<!-- Icon  -->
                        </div>
                        '.$title_html.'<!-- Title -->
                        '.$subtitle_html.'<!-- Text -->
                    </div>
                </div>';  
    }elseif ($display_as == 'sbox-6') {
       
       if ($icon_type == 'flaticon') {
           
           $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
       }
       $output =  '<div '. implode( ' ', $wrapper_attributes ).'>
                    <div class="icon-sm">
                        <div class="'.$display_as.'-icon">
                        '.$icon_html.'<!-- Icon  -->
                        </div>
                        '.$title_html.'<!-- Title -->
                        '.$subtitle_html.'<!-- Text -->
                        '.$buttons_html.'<!-- Title -->
                    </div>
                </div>';
    }elseif ($display_as == 'sbox-7') {
       if ($icon_type == 'flaticon') {
          
          $icon_html = '<span class="icon-'.esc_attr($icon_size).'">
                <span class="'.esc_attr($icon_flaticon).' icon-sm '.esc_attr($icon_color).'"></span>
             </span>';
       }
       $output =  '<div '. implode( ' ', $wrapper_attributes ).'>
                    <div class="icon-sm">
                        <div class="'.$display_as.'-icon">
                        '.$icon_html.'<!-- Icon  -->
                        </div>
                        '.$title_html.'<!-- Title -->
                        '.$subtitle_html.'<!-- Text -->
                    </div>
                </div>';  
    }else{
    	$output =  '<div '. implode( ' ', $wrapper_attributes ).'>
                    <div class="icon-sm">
                        <div class="'.$display_as.'-icon">
                        '.$icon_html.'<!-- Icon  -->
                        </div>
                        '.$title_html.'<!-- Title -->
                        '.$subtitle_html.'<!-- Text -->
                    </div>
                </div>';
    }
    
    return $output;
}
