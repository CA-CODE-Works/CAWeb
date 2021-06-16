/* Browse Library */
(function ($) {
  var frame;
  var el_name;

  $(function() {
    // Fetch available headers and apply jQuery.masonry
    // once the images have loaded.
    var $headers = $('.available-headers');

    if( $headers.length ){
      $headers.imagesLoaded(function() {
        $headers.masonry({
          itemSelector: '.default-header',
          isRTL: !!('undefined' != typeof isRtl && isRtl)
        });
      });
    }

    // Build the choose from library frame.
    $(document).on('click', 'div .library-link', function(event) {
      var $el = $(this);
      el_name = this.name;
      event.preventDefault();

      var types = $el.data('option');
      var uploader =  $el.data('uploader') ;
      var icon_check =  $el.data('icon-check') && $el.attr('data-icon-check') ;
      

      if (!!types && types.indexOf(',') > 0 )
        types = types.split(',');

      // Create the media frame.
      frame = wp.media.frames.customHeader = wp.media({
        // Set the title of the modal.
        title: $el.data('choose'),

        // Tell the modal to show only images.
        library: {
          type: types
        },

        uploader: uploader,
        // Customize the submit button.
        button: {
          // Set the text of the button.
          text: $el.data('update'),
          //text: $el.dataset.update,
          // Tell the button not to close the modal, since we're
          // going to refresh the page when the image is selected.
          close: true
        }
      });

      // When an image is selected, run a callback.
      frame.on('select', function() {
        // Grab the selected attachment.
        var attachment = frame.state().get('selection').first();
        var attachmentURL = attachment.attributes.url;
        var attachmentAlt = attachment.attributes.alt;
        var attachmentFileName = attachment.attributes.filename;
        
        var input_box = $('input[name="' + el_name + '"]:not([type="button"])');
        var preview_field = $('#' + el_name + '_img');
        var filename_box = $('input[type="text"][id="' + el_name + '_filename"]');
        var alt_text_box = $('input[type="text"][id="' + el_name + '_alt_text"]');

				if( "true" !== icon_check ){
            if( input_box.length  )
              input_box.val(attachmentURL);
            
						if( preview_field.length )
              preview_field.attr('src', attachmentURL);
              
						if( filename_box.length  )
              filename_box.val(attachmentFileName);
              
            if(  alt_text_box.length  )
              alt_text_box.val(attachmentAlt);

        }else{
          var data = {
            'action': 'caweb_fav_icon_check',
            'icon_url': attachmentURL,
          };

					jQuery.post(ajaxurl, data, function(response) {
						
						if(1 == response){
              if( input_box.length  )
                input_box.val(attachmentURL);
              
              if( preview_field.length )
                preview_field.attr('src', attachmentURL);
                
              if( filename_box.length  )
                filename_box.val(attachmentFileName);
						}else{
							alert("Invalid Icon Mime Type: " + attachmentFileName);
						}
					});
				}
      });

      frame.on('open', function() {
        if (!uploader) {
         var tabs = frame.el.getElementsByClassName('media-frame-router')[0].getElementsByClassName('media-router')[0].getElementsByClassName('media-menu-item');

					tabs[1].click();
          tabs[0].parentNode.removeChild(tabs[0]);

        }
      });

      frame.open();

    });

  });


}(jQuery));
