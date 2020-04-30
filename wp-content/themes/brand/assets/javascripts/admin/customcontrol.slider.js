jQuery(window).load(function(){
	jQuery( '.slider-input' ).change(function () {
		var value = this.value;
		jQuery( this ).parent().parent().parent().next( 'div.slider' ).slider( 'value', parseFloat(value));
	});

	function brand_range_slider( name, min, max, step ) {
		setTimeout(function() {
			jQuery('input[name="' + name + '"]').parent().parent().parent().next('div.slider').slider({
				value: jQuery('input[name="' + name + '"]').val(),
				min: min,
				max: max,
				step: step,
				slide: function( event, ui ) {
					jQuery('input[name="' + name + '"]').val( ui.value ).change();
					jQuery('#customize-control-' + name + ' .value input').val( ui.value );
				}
			});
		});
	}

	brand_range_slider( 'brand_settings[navigation_bg_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[footer_bg_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[header_image_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[content_image_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[sidebar_image_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[footer_image_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[portfolio_img_bg_opacity]', 0, 1, 0.1 );
	brand_range_slider( 'brand_settings[container_width]', 700, 2000, 5 );
	brand_range_slider( 'brand_settings[body_font_size]', 6, 25, 1 );
	brand_range_slider( 'brand_settings[site_title_font_size]', 10, 200, 1 );
	brand_range_slider( 'brand_settings[mobile_site_title_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[site_tagline_font_size]', 6, 50, 1 );
	brand_range_slider( 'brand_settings[mobile_site_tagline_font_size]', 6, 50, 1 );
	brand_range_slider( 'brand_settings[navigation_font_size]', 6, 30, 1 );
	brand_range_slider( 'brand_settings[mob_navigation_font_size]', 10, 60, 1 );
	brand_range_slider( 'brand_settings[mobile_mob_navigation_font_size]', 10, 60, 1 );
	brand_range_slider( 'brand_settings[widget_title_font_size]', 6, 30, 1 );
	brand_range_slider( 'brand_settings[widget_content_font_size]', 6, 25, 1 );
	brand_range_slider( 'brand_settings[heading_1_font_size]', 15, 100, 1 );
	brand_range_slider( 'brand_settings[mobile_heading_1_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[heading_2_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[mobile_heading_2_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[heading_3_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[mobile_heading_3_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[heading_4_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[mobile_heading_4_font_size]', 10, 100, 1 );
	brand_range_slider( 'brand_settings[footer_font_size]', 10, 100, 1 );
  brand_range_slider( 'brand_settings[sidebar_width]', 10, 50, 1 );
	brand_range_slider( 'brand_settings[sidebar_separator_width]', 1, 20, 1 );
});
