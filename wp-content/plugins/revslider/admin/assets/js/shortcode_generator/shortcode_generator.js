/*
	RevSlider Shortcode Wizard
*/

;var RS_SC_WIZARD = {
	
	openTemplateLibrary: function(type, params) {		
		// 5.0 to 6.0 update patch
		if(typeof RVS.LIB.OBJ === 'undefined') return;
		
		RS_SC_WIZARD.type = type;
		if(!RS_SC_WIZARD.libraryInited) {
			
			RS_SC_WIZARD.libraryInited = true;
			RVS.F.initObjectLibrary(true); /* arg = hideUpdateButton */
			RVS.F.initOnOff(jQuery('#obj_addsliderasmodal').css('display', 'inline-block'));
			
			// sorting options added manually here to avoid loading event management framework
			jQuery('body').on('change', '#sel_olibrary_sorting', function() {
				
				var obj;
				if(this.value === 'datedesc') obj = {display: 'none'};
				else obj = {display: 'inline-block', opacity: '1', visibility: 'visible'};
					
				jQuery('#reset_objsorting').css(obj);
				if(this.dataset.evt!==undefined) RVS.DOC.trigger(this.dataset.evt, this.dataset.evtparam);
				
			}).on('change', '#ol_pagination', function(e) {
				
				if(this.dataset.evt!==undefined) RVS.DOC.trigger(
					
					this.dataset.evt,
					[e, this.value, this.dataset.evtparam]
					
				);				
			});			
		}
		
		var successObj = {modules: 'addRevSliderShortcode', event: 'selectRevSliderItem'};
		if(params && params.alias) successObj.eventparam = params.alias;
		
		jQuery('#obj_addsliderasmodal .tponoffwrap').addClass('off').find('input').removeAttr('checked').prop('checked', false);
		RVS.F.openObjectLibrary({types: ['modules'], filter: 'all', selected: ['modules'], success: successObj});
		
		var folder = RVS.F.getCookie('rs6_wizard_folder');
		if(folder && folder !== -1 && folder !== '-1') {
			if (RVS.LIB.OBJ !==undefined && RVS.LIB.OBJ.items!==undefined && RVS.LIB.OBJ.items.modules!==undefined)	RVS.F.changeOLIBToFolder(folder);		
		}
	},

	openSliderEditor : function(type,params) {	
		var alias = revslider_react.state.text;
		if (alias==="" || alias===undefined) return;
		if (alias!==undefined) alias = alias.split('alias="')[1];
		if (alias==="" || alias===undefined) return;
		if (alias!==undefined) alias = alias.split('"')[0];
		if (alias==="" || alias===undefined) return;
		if (alias!==undefined && alias!=="") window.open(RVS.ENV.admin_url+"&view=slide&alias="+alias);
	}
	
};

(function() {	
	jQuery(document).ready(function() {		
		if(typeof QTags !== 'undefined') {			
			var add_rs_button = true;
			if(typeof edButtons !== 'undefined') {
				for(var key in edButtons) {
					if(!edButtons.hasOwnProperty(key)) continue;
					if(edButtons[key].id == 'slider-revolution') {
						add_rs_button = false;
						break;
					}
				}
			}			
			if(add_rs_button){				
				QTags.addButton('slider-revolution', 'Slider Revolution', function() {				
					RS_SC_WIZARD.openTemplateLibrary('qtags');					
				});				
			}
		}
		
		if(typeof RVS.LIB.OBJ !== 'undefined' && RVS.LIB.OBJ && RVS.LIB.OBJ.items && RVS.LIB.OBJ.items.length) {
			RS_SC_WIZARD.defaultAlias = RVS.LIB.OBJ.items[0].alias;
		}
		
		jQuery('body').on('click', '#objectlibrary *[data-folderid]', function() {			
			RVS.F.setCookie("rs6_wizard_folder",this.dataset.folderid,360);
		});
		
	});
	
	function updateShortcode(slider_handle, content, data, modal) {
		
		switch(RS_SC_WIZARD.type) {
				
			case 'vc':
			
				var rs_vc_data = {alias: slider_handle};
				RS_SC_WIZARD.rs_cur_vc_obj.model.save('params', rs_vc_data);
			
			break;
			
			case 'tinymce':
			
				tinyMCE.activeEditor.selection.setContent(content);
			
			break;
			
			case 'elementor':
			
				RS_SC_WIZARD.suppress = true;
			
				var control = RS_SC_WIZARD.elementor_button.closest('#elementor-controls');
				control.find('input[data-setting="revslidertitle"]').val(data.title).trigger('input');
				control.find('input[data-setting="shortcode"]').val(content).trigger('input');
				
				setTimeout(function() {
					
					RS_SC_WIZARD.suppress = false;
					
				}, 500);
			
			break;
			
			case 'qtags':
			
				QTags.insertContent(content);
			
			break;
			
			default:
				
				if(window.revslider_react) {
					window.revslider_react.state.text = content; 
					window.revslider_react.state.modal = modal;
					window.revslider_react.state.sliderTitle = data.title;
					window.revslider_react.state.sliderImage = data.bg.src;

					window.revslider_react.props.setAttributes({sliderTitle: data.title, text: content, modal: modal, sliderImage: data.bg.src});
					window.revslider_react.forceUpdate();
				}
				
			// end default
		
		}
	
	}
	
	jQuery(document).on('addRevSliderShortcode', function(e, data) {
		
		var slider_handle = data.alias;
		if(slider_handle !== '-1'){
			
			var modal = jQuery('#obj_addsliderasmodal_input').is(':checked'),
				content = '[rev_slider alias="' + slider_handle + '"';
			
			if(modal) content += ' usage="modal"';
			content += '][/rev_slider]';
				
			updateShortcode(slider_handle, content, data, modal);
			
		}
		
	}).on('selectRevSliderItem', function() {		
		var folder = RVS.F.getCookie('rs6_wizard_folder');		
		if(folder && folder !== -1 && folder !== '-1') RVS.F.changeOLIBToFolder(folder);		
		
	});

	
})();










