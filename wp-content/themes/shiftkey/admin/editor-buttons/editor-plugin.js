/**
 * Define all the formatting buttons with the HTML code they set.
 */		
var perchButtons=[	

		{
			id:'dropcap',
			image:'dropcap.png',
			title:'Dropcap',
			generateHtml:function(ed){
				var selected_text = ed.selection.getContent();
                var return_text = '';
                return '<span class="dropcap">' + selected_text + '</span>';
			}
		},
		{
			id:'colortext',
			image:'color-text.png',
			title:'Color Text',
			generateHtml:function(ed){
				var selected_text = ed.selection.getContent();
                var return_text = '';
                return '<span class="primary-color">' + selected_text + '</span>';
			}
		},
		{
			id:'colorbg',
			image:'color-bg.png',
			title:'Color Background Text',
			generateHtml:function(ed){
				var selected_text = ed.selection.getContent();
                var return_text = '';
                return '<span class="primary-bg white-color">' + selected_text + '</span>';
			}
		},
		{
			id:'leadtext',
			image:'lead.png',
			title:'Lead Text',
			generateHtml:function(ed){
				var selected_text = ed.selection.getContent();
                var return_text = '';
                return '<span class="lead">' + selected_text + '</span>';
			}
		},
		{
			id:'highlights',
			image:'highlight.png',
			title:'Highlight Text',
			generateHtml:function(ed){
				var selected_text = ed.selection.getContent();
                var return_text = '';
                return '<strong class="underline-yellow text-uppercase theme-color">' + selected_text + '</strong>';
			}
		},
		{
			id:'perchbreak',
			image:'cpanel-btn-break.png',
			title:'tt Insert Break <br>',
			allowSelection:true,
			generateHtml:function(ed){
				return '<br class="clear" />';
			}
		},
];

/**
 * Contains the main formatting buttons functionality.
*/

perchButtonManager={
	dialog:null,
	idprefix:'perch-shortcode-',
	ie:false,
	opera:false,	

	/**
	 * Init the formatting button functionality.
	 */
	init:function(){		

		var length=perchButtons.length;
		for(var i=0; i<length; i++){	
			var btn = perchButtons[i];
			perchButtonManager.loadButton(btn);		
		}		

		if ( jQuery.browser.msie ) {
			perchButtonManager.ie=true;
		}	

		if (jQuery.browser.opera){
			perchButtonManager.opera=true;
		}

	},	

	/**
	 * Loads a button and sets the functionality that is executed when the button has been clicked.
	 */

	loadButton:function(btn){		

		tinymce.create('tinymce.plugins.'+btn.id, {
	        init : function(ed, url) {
			        ed.addButton(btn.id, {
	                title : btn.title,
	                image : url+'/buttons/'+btn.image,
	                onclick : function() {		        	

			           var selection = ed.selection.getContent();
	                   if(btn.allowSelection && selection && btn.fields){						

	                	   //there are inputs to fill in, show a dialog to fill the required data
	                	   perchButtonManager.showDialog(btn, ed);
	                   }else if(btn.allowSelection && selection){						

	                	   //modification via selection is allowed for this button and some text has been selected
							selection = btn.generateHtml(selection);
							ed.selection.setContent(selection);

	                   }else if(btn.fields){
	                	   //there are inputs to fill in, show a dialog to fill the required data
	                	   perchButtonManager.showDialog(btn, ed);
	                   }else if(btn.list){
	                	   ed.dom.remove('perchcaret');
		           		    ed.execCommand('mceInsertContent', false, '&nbsp;');		           			

	                	    //this is a list
	                	    var list, dom = ed.dom, sel = ed.selection;	                	    

		               		// Check for existing list element
		               		list = dom.getParent(sel.getNode(), 'ul');		               		

		               		// Switch/add list type if needed
		               		ed.execCommand('InsertUnorderedList');		               		

		               		// Append styles to new list element
		               		list = dom.getParent(sel.getNode(), 'ul');		               		

		               		if (list) {
		               			dom.addClass(list, btn.list);
		               		}
	                   }else{
	                	   //no data is required for this button, insert the generated HTML
	                	   ed.execCommand('mceInsertContent', true, btn.generateHtml(ed));
	                   }  		

	                }
	            });
	        }
	    });		

	    tinymce.PluginManager.add(btn.id, tinymce.plugins[btn.id]);
	},
};

/**
 * Init the formatting functionality.
 */
(function() {
	/**
	 * Add the shortcodes downdown.
	 */
	tinymce.PluginManager.add( 'bpbutton', function ( editor ) {
		var ed = tinymce.activeEditor;
		editor.addButton( 'bpbutton', {
			text: 'Button',
			icon: 'link',
			onclick: function () {
				editor.windowManager.open({
					title: 'Shiftkey Button',
					body: [
						{
							type:  'textbox',
							name:  'title',
							label: 'Title',
							value: 'Link Title'
						},
						{
							type:  'textbox',
							name:  'url',
							label: 'URL',
							value: '#'
						},
						{
							type:  'listbox',
							name:  'size',
							label: 'Size',
							values: [
								{
									text: 'Normal',
									value: 'btn-md'
								},
								{
									text: 'Small',
									value: 'btn-sm'
								},
								{
									text: 'Extra Small',
									value: 'btn-xs'
								},
								{
									text: 'Large',
									value: 'btn-lg'
								},
							]
						},
						{
							type:  'listbox',
							name:  'color',
							label: 'Background',
							values: [
								{
									text: 'Primary',
									value: 'btn-primary'
								},
								{
									text: 'Primary outline',
									value: 'btn-primary-tra'
								},
								{
									text: 'Dark',
									value: 'btn-dark'
								},
								{
									text: 'Dark outline',
									value: 'btn-dark-tra'
								},								
							]
						}
					],
					onsubmit: function ( e ) {	
						var cls = (e.data.size != '')? ' '+e.data.size : '';
						cls += (e.data.color != '')? ' '+e.data.color : '';
						editor.insertContent( '<a href="'+e.data.url+'" title="'+e.data.title+'" class="btn btn-arrow '+cls+'"><span>'+e.data.title+' <i class="fas fa-angle-double-right"></i></span></a>' );						

					}
				});
			}
		});
	});
	perchButtonManager.init();
})();