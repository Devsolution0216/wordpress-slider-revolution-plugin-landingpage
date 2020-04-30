<?php
add_action( 'vc_load_default_templates_action','shiftkey_template_sections_for_vc' ); // Hook in
function shiftkey_template_sections_for_vc() {
	$templates = array();
	$empty_value = '';

		
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



