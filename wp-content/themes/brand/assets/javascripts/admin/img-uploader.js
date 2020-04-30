jQuery(function($){

  // Set all variables to be used in scope
  /*var frame,
      metaBox = $('#brand_header_box'), // Your meta box id here
      addImgLink = metaBox.find('.upload-custom-img'),
      delImgLink = metaBox.find( '.delete-custom-img'),
      imgContainer = metaBox.find( '.custom-img-container'),
      imgIdInput = metaBox.find( '.custom-img-id' ); */

  // ADD IMAGE LINK
  $('body').on( 'click', '.upload-custom-img', function( event ){

    var frame,
        metaBox = $(this).parent().parent(), // Your meta box id here
        addImgLink = metaBox.find('.upload-custom-img'),
        delImgLink = metaBox.find( '.delete-custom-img'),
        imgContainer = metaBox.find( '.custom-img-container'),
        imgIdInput = metaBox.find( '.custom-img-id' );

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }

    // Create a new media frame
    frame = wp.media({
      title: brand_img_uploader_obj.media_frame_title,
      button: {
        text: brand_img_uploader_obj.media_frame_btn
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });


    // When an image is selected in the media frame...
    frame.on( 'select', function() {

      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:150px;"/>' );

      // Send the attachment id to our hidden input
      imgIdInput.val( attachment.id );

      // Hide the add image link
      addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    frame.open();
  });


  // DELETE IMAGE LINK
  $('body').on( 'click', '.delete-custom-img', function( event ){

    var frame,
        metaBox = $(this).parent().parent(), // Your meta box id here
        addImgLink = metaBox.find('.upload-custom-img'),
        delImgLink = metaBox.find( '.delete-custom-img'),
        imgContainer = metaBox.find( '.custom-img-container'),
        imgIdInput = metaBox.find( '.custom-img-id' );

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });

});
