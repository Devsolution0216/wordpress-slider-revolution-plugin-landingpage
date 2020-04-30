<?php
add_filter( 'perch_modules/vc/perch_pricing_table', 'shiftkey_vc_pricing_table_default_args' );
function shiftkey_vc_pricing_table_default_args( $args ){
	$default = array(
		'align' => '',              
        'title' => 'Basic', 
        'title_font_container' => 'tag:h4|size:xs',
        'validity' => 'monthly', 
        'validity_font_container' => 'tag:p|extra_class:validity',
        'leadtext' => '',
        'subtitle_font_container' => 'tag:p|size:md',    
        'el_class' => '',       
    );

    $args = shiftkey_set_default_vc_values($default, $args);   
    
    return $args;    
}

add_filter( 'perch_modules/pricing_table/output', 'shiftkey_vc_pricing_table_output', 10, 3 );
function shiftkey_vc_pricing_table_output( $output, $args, $atts ){
    if( !empty($args) ){
        extract($atts);
        extract($args);

        $listArr = explode(',', $content);
        $listArr = array_filter($listArr);        
        if( !empty($listArr) ):        	
	        $feture_list_html = '<ul class="features">';
	        foreach ($listArr as $text) {
	        	$new_text = shiftkey_parse_text($text);
	        	if( $text == $new_text ){
	        		$feture_list_html .= '<li><i class="fas fa-stop-circle"></i> '.esc_attr($text).'</li>';
	        	}else{
	        		$feture_list_html .= '<li class="disabled-option"><i class="fas fa-stop-circle"></i> '.$new_text.'</li>';
	        	}
	        	
	        }
	        $feture_list_html .= '</ul>';
	    endif;
        
        $output ='
        <div '. implode( ' ', $wrapper_attributes ).'> 
            <div class="pricing-table '.esc_attr($el_class).'">
                <div class="pricing-plan">
                    '.$pricing_title.'
                    '.$leadtext_html.'
                     '.$price_html.'   
                    '.$validity_html.'
                    '.$vat_html.' 
                </div>      
                
                <div class="features steelblue-color">                    
                    '.$feture_list_html.'
                </div>                 
                '.$button_html.'           
            </div>       
        </div>';
    }
    return $output;
}