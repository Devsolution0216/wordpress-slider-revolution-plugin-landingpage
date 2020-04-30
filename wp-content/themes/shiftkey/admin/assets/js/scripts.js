jQuery(document).ready(function($){

  "use strict";


  $('#shiftkey_settings_box').show();
  $('#shiftkey_onepage_sttings_boxes').insertAfter('#edit-slug-box').hide();
  $('#shiftkey_onepage_footer_sttings_boxes').hide();

  if($('#shiftkey_template_type').length > 0){
    
      $('#shiftkey_template_type').live('change', function(){
        if( $(this).val() == 'landing'  ){
          $('#shiftkey_settings_box').hide();
          $('#shiftkey_onepage_sttings_boxes').show();
          $('#shiftkey_onepage_footer_sttings_boxes').show();
        }else{
          $('#shiftkey_settings_box').show();
          $('#shiftkey_onepage_sttings_boxes').hide();
          $('#shiftkey_onepage_footer_sttings_boxes').hide();
        }       
      })

      $('#shiftkey_template_type').trigger('change');

    }

    $('.edit-menu-item-megamenustyle').on( 'change', function(){
        if( $(this).val() != '' ){
          $(this).closest('li').find('.megamenucolumn-wrap').show();
        }else{
          $(this).closest('li').find('.megamenucolumn-wrap').hide();
        }
    })

     

    $('.shiftkey-upload-button').live('click', function(e) {
      var $button = $(this),
      $val = $(this).parents('.shiftkey-upload-container').find('input:text'),
      file;
      e.preventDefault();
      e.stopPropagation();
      // If the frame already exists, reopen it

      if (typeof(file) !== 'undefined') file.close();
      // Create WP media frame.
      file = wp.media.frames.perch_media_frame_2 = wp.media({
        // Title of media manager frame
        title: 'Shiftkey Upload image',
        button: {
          //Button text
          text: 'Insert image url'
        },
        // Do not allow multiple files, if you want multiple, set true
        multiple: false
      });

      //callback for selected image
      file.on('select', function() {
        var attachment = file.state().get('selection').first().toJSON();
        $val.val(attachment.url).trigger('change');
        $button.closest('.shiftkey-upload-container').find('img').attr('src', attachment.url);
      });
      // Open modal
      file.open();
    });	

    

})/*end ready*/