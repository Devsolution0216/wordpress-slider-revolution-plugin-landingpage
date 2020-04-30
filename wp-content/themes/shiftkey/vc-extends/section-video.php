<?php
add_action( 'vc_load_default_templates_action','shiftkey_video_sections_for_vc' ); // Hook in
function shiftkey_video_sections_for_vc() {
	$templates = array();
	$empty_title = $empty_subtitle = '';
	$empty_title = ''.esc_html($empty_title).'';
	$empty_subtitle = ''.esc_html($empty_subtitle).'';


	$templates['Section: Video 03'] = '[vc_section bg_class="bg-tra" parallax_image_attachment="inherit" padding_top="pt-80" padding_bottom="pb-40" padding_class="" el_id="statistic-2"][vc_row][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-069-download" icon_size="icon" icon_color="Inherit" prefix="9," count="632" title="Total Downloads" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs steelblue-color"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-068-favorite" icon_size="icon" icon_color="Inherit" prefix="5," count="281" title="Happy Customers" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs steelblue-color"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-027-user" icon_size="icon" icon_color="Inherit" prefix="6," count="179" title="Active Accounts" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs steelblue-color"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-075-support" icon_size="icon" icon_color="Inherit" prefix="1," count="473" title="Tickets Closed" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs steelblue-color"][/vc_column][/vc_row][/vc_section][vc_section section_type="video" video_type="3" bg_class="bg-tra" parallax_image_attachment="fixed" padding_class=""][vc_row][vc_column][perch_watch_video2 url="https://www.youtube.com/embed/SZEflIVnhH8" icon_class="tra"'.esc_html($empty_title).' '.esc_html($empty_title).' el_class=""][/vc_column][/vc_row][/vc_section]';



	
	foreach ($templates as $key => $template) {
		$data               = array(); 
	    $data['name']       = esc_attr($key); // Assign name for your custom template
	    $data['weight']     = 0; 
	    $data['image_path'] = '';
	    $data['custom_class'] = ''; // CSS class name
	    $data['content']    = $template;

	    vc_add_default_templates( $data );
	}
      

}



