<?php
add_action( 'vc_load_default_templates_action','shiftkey_brands_sections_for_vc' ); // Hook in
function shiftkey_brands_sections_for_vc() {
	$templates = array();
	$empty_title = $empty_subtitle = '';
	$empty_title = ''.esc_html($empty_title).'';

	$templates['Section: Brand- 02'] = '[vc_section section_type="brands" brands_type="2" bg_class="bg-tra-dark" parallax_image_attachment="inherit" padding_class="" el_class="brands-section division"][vc_row][vc_column][perch_section_title '.esc_html($empty_title).' subtitle="We partner with companies of all sizes, all around the world" subtitle_font_container="tag:p|text_color:Default|text_underline:none|" el_class=""][perch_vc_carousel cacrousel_style="" column_lg="6" column_md="4" dots="0"][perch_brand_logo_slide][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-2.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-3.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-4.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-5.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-6.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-7.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-3.png"][perch_brand_logo_slide custom_src="'.get_template_directory_uri().'/images/brand-5.png"][/perch_vc_carousel][/vc_column][/vc_row][/vc_section]';

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



