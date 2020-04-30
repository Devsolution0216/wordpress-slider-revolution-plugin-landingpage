<?php
add_action( 'vc_load_default_templates_action','shiftkey_statistic_sections_for_vc' ); // Hook in
function shiftkey_statistic_sections_for_vc() {
	$templates = array();

	$templates['Section: Statistic 01'] = '[vc_section bg_class="bg-tra" parallax_image_attachment="fixed" el_id="statistic-1" el_class="blue-waves"][vc_row][vc_column width="1/12"][/vc_column][vc_column width="10/12"][perch_section_title title="More Faster, More Powerful App" title_font_container="tag:h3|size:lg|text_color:Default|text_underline:none|" subtitle="Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero at tempus, blandit posuere ligula varius congue cursus porta feugiat" subtitle_font_container="tag:p|size:md|text_color:Default|text_underline:none|" el_class="section-title white-color"][/vc_column][vc_column width="1/12"][/vc_column][/vc_row][vc_row][vc_column width="2/12"][/vc_column][vc_column width="8/12"][vc_row_inner][vc_column_inner width="1/3"][perch_counter_up align="" icon_type="tonicons" icon_color="Inherit" prefix="" count="38" postfix="%" title="Fast Load Time" el_class="statistic-block box-icon white-color"][/vc_column_inner][vc_column_inner width="1/3"][perch_counter_up align="" icon_type="tonicons" icon_color="Inherit" prefix="" count="47" postfix="%" title="More Productivity" el_class="statistic-block box-icon white-color"][/vc_column_inner][vc_column_inner width="1/3"][perch_counter_up align="" icon_type="tonicons" icon_color="Inherit" prefix="" count="43" postfix="%" title="Less RAM Loading" el_class="statistic-block box-icon white-color"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="2/12"][/vc_column][/vc_row][/vc_section]';

	$templates['Section: Statistic 02'] = '[vc_section bg_class="bg-tra" parallax_image_attachment="inherit" padding_top="pt-80" padding_bottom="pb-40" padding_class="" el_id="statistic-2" el_class="statistic-section division"][vc_row][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-323-download-2" icon_size="icon" icon_color="Inherit" prefix="9," count="632" title="Total Downloads" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-269-heart" icon_size="icon" icon_color="Inherit" prefix="5," count="281" title="Happy Customers" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-032-user-3" icon_size="icon" icon_color="Inherit" prefix="6," count="179" title="Active Accounts" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs"][/vc_column][vc_column width="1/4"][perch_counter_up align="text-center" icon_flaticon="flaticon-266-help" icon_size="icon" icon_color="Inherit" prefix="1," count="473" title="Tickets Closed" title_font_container="tag:p|text_color:Default|text_underline:none|font_weight:400|" el_class="statistic-block icon-xs"][/vc_column][/vc_row][/vc_section]';	

	
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



