(function() {
	
	window.VcSliderRevolution = vc.shortcode_view.extend({
		
		initialize: function() {
			
			return window.VcSliderRevolution.__super__.initialize.call(this);
			
		},
		render: function () {
			
			var params = this.model.get('params');
			RS_SC_WIZARD.rs_cur_vc_obj = this;
			
			if(vc.add_element_block_view.$el.is(':visible')) rs_vc_show_overlay(params);
			return window.VcSliderRevolution.__super__.render.call(this);
			
		},
		editElement: function() {
			
			var params = this.model.get('params');
			RS_SC_WIZARD.rs_cur_vc_obj = this;
			rs_vc_show_overlay(params);
			
		}
		
	});
	
	if(typeof(window.InlineShortcodeView) !== 'undefined') {
		
		var rs_show_frontend_overlay = false;
		jQuery(window).on('vc_build', function() {
			
			vc.add_element_block_view.$el.find('[data-element="rev_slider"]').click(function() {
				rs_show_frontend_overlay = true;
			});
			
		});
	
		window.InlineShortcodeView_rev_slider = window.InlineShortcodeView.extend({
			
			render: function() {
				
				var params = this.model.get('params');
				RS_SC_WIZARD.rs_cur_vc_obj = this;				
				
				if(rs_show_frontend_overlay) rs_vc_show_overlay(params);
				InlineShortcodeView_rev_slider.__super__.render.call(this);
				
				return this;
				
			},
			update: function(model) {
				
				rs_show_frontend_overlay = false;
				InlineShortcodeView_rev_slider.__super__.update.call(this, model);
				return this;
				
			},
			edit: function() {
				
				var params = this.model.get('params');
				RS_SC_WIZARD.rs_cur_vc_obj = this;
				
				rs_vc_show_overlay(params);
				return false;
				
			}
			
		});
		
	}
	
	function rs_vc_show_overlay(params) {

		jQuery('.wpb-element-edit-modal').hide(); //hide the normal VC window and use own (old vc version)
		jQuery('#vc_properties-panel').hide(); //hide the normal VC window and use own (new vc version)
		
		RS_SC_WIZARD.openTemplateLibrary('vc', params);
		
	}
	
})();




