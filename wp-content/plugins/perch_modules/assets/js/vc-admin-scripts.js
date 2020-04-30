;(function($) {
  "use strict";

  $(document).ready(function(){
    
  window.VcCustomElementView = vc.shortcode_view.extend( {
        elementTemplate: false,
        $wrapper: false,
        changeShortcodeParams: function ( model ) {
        var params;

        window.VcCustomElementView.__super__.changeShortcodeParams.call( this, model );
        params = _.extend( {}, model.get( 'params' ) );
        
        if ( ! this.elementTemplate ) {
          this.elementTemplate = this.$el.find( '.perch_vc_element_container' ).html();
        }
        if ( ! this.$wrapper ) {
          this.$wrapper = this.$el.find( '.wpb_element_wrapper' );
        }
        if ( _.isObject( params ) ) {
         
          var template = vc.template( this.elementTemplate, vc.templateOptions.custom );          

          this.$wrapper.find( '.perch_vc_element_container' ).html( template( { params: params } ) );
        }
      }
  });
      


 if( PERCH_MODULES.vc_preview == 'full' ){
    window.PerchVcElementPreview = vc.shortcode_view.extend( {
        elementTemplate: false,
        $wrapper: false,
        changeShortcodeParams: function ( model ) {
          var params;       

          window.PerchVcElementPreview.__super__.changeShortcodeParams.call( this, model );
          params = _.extend( {}, model.get( 'params' ) );

         
          
          if ( ! this.elementTemplate ) {
            this.elementTemplate = this.$el.find( '.perch_vc_element_container' ).html();
          }
          if ( ! this.$wrapper ) {
            this.$wrapper = this.$el.find( '.wpb_element_wrapper' );
          }
          if ( _.isObject( params ) ) {         
            var template = vc.template( this.elementTemplate, vc.templateOptions.custom );     
            if(PERCH_MODULES.vc_preview != 'full') var admin_params =  model.attributes.view.params;
            else var admin_params = '';
            var data = {
              'action': 'perch_vc_admin_view',
              'params': params,
              'element' : model.attributes.shortcode,
              'admin_params' : admin_params          
            };



            var $wraper = this.$wrapper;  
                
            $.post(PERCH_MODULES.ajaxurl, data, function(response) {
              $wraper.find( '.perch_vc_element_container' ).empty().append( response );
            });

            
          }
        }
    });
  }

  }); // end ready
    
})( jQuery, window, document );