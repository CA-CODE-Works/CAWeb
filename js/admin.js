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

/* CAWeb Alert Option Javascript */
jQuery(document).ready(function($) {
	
	if( $( "#caweb-alert-banners" ).length ){
		$( "#caweb-alert-banners" ).sortable();
		$( "#caweb-alert-banners" ).disableSelection()
	}

	$('.remove-alert').click(function(e){ removeAlertFunc(this);});
	$('#add-alert').click( function(e){ e.preventDefault(); addAlert();});

	var removeAlertFunc = function (s){
		var r = confirm("Are you sure you want to remove this alert? This can not be undone.");
	  
		if (r == true) {
			changeMade = true;
			$(s).parent().parent().parent().remove();
		}
	  
	}

	function addAlert(){
		var list = $('#caweb-alert-banners');
		var alertCount = $(list).children('li').length + 1;
		var li = document.createElement('LI');
		var row = document.createElement('DIV');

		// Attributes
		$(li).addClass('pl-2');
		$(row).addClass('form-row');

		// Append 
		$(row).append(addAlertAnchor(alertCount));
		$(row).append('<!-- Alert Options -->');
		$(row).append(addAlertControls(alertCount));
		$(row).append('<!-- Alert Banner Fields -->');
		$(row).append(addAlertFields(alertCount));

		$(li).append('<!-- Alert Banner Row -->');
		$(li).append(row);
		$(list).append(li);

		// Initialize 3rd Party Plugins after DOMs have been added
		wp.editor.initialize("alertmessage-" + alertCount, caweb_admin_args.tinymce_settings);
		

		changeMade = true;
	}
	
	function addAlertAnchor( c ){
		var headerAnchor = document.createElement('A');
		var header = document.createElement('H2');
		var headerToggle = document.createElement('SPAN');

		$(headerAnchor).addClass('d-block text-decoration-none');
		$(headerAnchor).attr('href', '#alert-banner-' + c);
		$(headerAnchor).attr('aria-expanded', 'true');
		$(headerAnchor).attr('aria-controls', 'alert-banner-' + c);
		$(headerAnchor).attr('data-toggle', 'collapse');

		$(header).addClass('d-inline');
		$(header).html('Label');
		
		$(headerToggle).addClass('text-secondary ca-gov-icon-');

		$(header).append(headerToggle);
		
		$(headerAnchor).append(header);

		return headerAnchor;
	}

	function addAlertControls( c ){
		var alertOptions = document.createElement('DIV');
		var alertStatus = document.createElement('INPUT');
		var removeAlert = document.createElement('BUTTON');
				
		$(alertStatus).attr('type', 'checkbox');
		$(alertStatus).attr('checked', 'true');
		$(alertStatus).attr('data-toggle', 'toggle');
		$(alertStatus).attr('name', 'alert-status-' + c);
		$(alertStatus).attr('id', 'alert-status-' + c);
		$(alertStatus).addClass('form-control');


		$(removeAlert).addClass('btn btn-danger remove-alert ml-1');
		$(removeAlert).html('Remove');
		removeAlert.addEventListener('click', function(e){ removeAlertFunc(this) } )
		
		$(alertOptions).append(alertStatus);
		$(alertOptions).append(removeAlert);

		$(alertStatus).bootstrapToggle({
			onstyle: 'success',
		  });

		return alertOptions;
	}

	function addAlertFields( c ){
		var alertFields = document.createElement('DIV');

		$(alertFields).attr('id', 'alert-banner-' + c);
		$(alertFields).addClass('form-row col-sm-12 p-2 collapse show');

		$(alertFields).append('<!-- Alert Banner Title -->');
		$(alertFields).append(alertTitleField(c));
		$(alertFields).append('<!-- Alert Banner Message -->');
		$(alertFields).append(alertMessageField(c));
		$(alertFields).append('<!-- Alert Banner Settings -->');
		$(alertFields).append(alertSettings(c));

		return alertFields;
	}

	function alertTitleField( c ){
		var alertTitle = document.createElement('DIV');
		var alertTitleLabel = document.createElement('LABEL');
		var alertTitleSmall = document.createElement('SMALL');
		var alertTitleInput = document.createElement('INPUT');
		
		$(alertTitle).addClass('form-group col-sm-7');
		
		$(alertTitleLabel).attr('for', 'alert-header-' + c);
		$(alertTitleLabel).addClass('mb-0');
		$(alertTitleLabel).html('<strong>Title</strong>');

		$(alertTitleSmall).html('Enter header text for the alert.');
		$(alertTitleSmall).addClass('text-muted d-block mb-2');

		$(alertTitleInput).addClass('form-control');
		$(alertTitleInput).attr('type', 'text');
		$(alertTitleInput).val('Label');
		$(alertTitleInput).attr('placeholder', 'Label');
		$(alertTitleInput).attr('id', 'alert-header-' + c);
		$(alertTitleInput).attr('name', 'alert-header-' + c);

		$(alertTitle).append(alertTitleLabel);
		$(alertTitle).append(alertTitleSmall);
		$(alertTitle).append(alertTitleInput);

		return alertTitle;
	}

	function alertMessageField( c ){
		var alertMsg = document.createElement('DIV');
		var alertMsgLabel = document.createElement('LABEL');
		var alertMsgSmall = document.createElement('SMALL');
		var alertMsgTextarea = document.createElement('TEXTAREA');

		$(alertMsg).addClass('form-group col-sm-12');

		$(alertMsgLabel).attr('for', 'alert-message-' + c);
		$(alertMsgLabel).html('<strong>Message</strong>');
		
		$(alertMsgSmall).addClass('text-muted d-block mb-2');
		$(alertMsgSmall).html('Enter message for the alert');

		$(alertMsgTextarea).attr('name', 'alert-message-' + c);
		$(alertMsgTextarea).attr('id', 'alertmessage-' + c);
		$(alertMsgTextarea).addClass('wp-editor-area');
		$(alertMsgTextarea).html('Enter Alert text here...');

		$(alertMsg).append(alertMsgLabel);
		$(alertMsg).append(alertMsgSmall);
		$(alertMsg).append(alertMsgTextarea);

		return alertMsg;
	}

	function alertSettings( c ){
		var alertSettingsDiv = document.createElement('DIV');
		
		$(alertSettingsDiv).addClass('form-group col-sm-12');


		$(alertSettingsDiv).append('<!-- Display On -->');
		$(alertSettingsDiv).append(addDisplayOnField(c));
		$(alertSettingsDiv).append('<!-- Banner Color -->');
		$(alertSettingsDiv).append(addBannerColorField(c));
		$(alertSettingsDiv).append('<!-- Read More -->');
		$(alertSettingsDiv).append(addReadMoreFields(c));
		$(alertSettingsDiv).append(addReadMoreSettings(c));
		$(alertSettingsDiv).append('<!-- Banner Icon -->');
		$(alertSettingsDiv).append(addIconField(c));
		
		return alertSettingsDiv;
	}

	function addDisplayOnField( c ){
		var displayOnGroup = document.createElement('DIV');
		var displayOnLabel = document.createElement('LABEL');
		var displayOnSmall = document.createElement('SMALL');
		var displayOnHomeGroup = document.createElement('DIV');
		var displayOnHomeGroupInput = document.createElement('INPUT');
		var displayOnHomeGroupLabel = document.createElement('LABEL');
		var displayOnAllGroup = document.createElement('DIV');
		var displayOnAllGroupInput = document.createElement('INPUT');
		var displayOnAllGroupLabel = document.createElement('LABEL');

		$(displayOnGroup).addClass('form-group col-sm pl-0');
		$(displayOnGroup).attr('role', 'radiogroup');
		$(displayOnGroup).attr('aria-label', 'Alert Display On Options');

		$(displayOnLabel).addClass('d-block mb-0');
		$(displayOnLabel).html('<strong>Display on</strong>');

		$(displayOnSmall).addClass('text-muted d-block mb-2');
		$(displayOnSmall).html('Select whether alert should display on home page or on all pages.');

		$(displayOnHomeGroup).addClass('form-check form-check-inline');

		$(displayOnHomeGroupInput).attr('id', 'alert-display-home-' + c);
		$(displayOnHomeGroupInput).attr('name', 'alert-display-' + c);
		$(displayOnHomeGroupInput).attr('type', 'radio');
		$(displayOnHomeGroupInput).val('home');
		$(displayOnHomeGroupInput).attr('checked', 'true');
		$(displayOnHomeGroupInput).addClass('form-check-input');

		$(displayOnHomeGroupLabel).addClass('form-check-label');
		$(displayOnHomeGroupLabel).attr('for', 'alert-display-home-' + c);
		$(displayOnHomeGroupLabel).html('Home Page Only');

		$(displayOnAllGroup).addClass('form-check form-check-inline');
		
		$(displayOnAllGroupInput).attr('id', 'alert-display-all-' + c);
		$(displayOnAllGroupInput).attr('name', 'alert-display-' + c);
		$(displayOnAllGroupInput).attr('type', 'radio');
		$(displayOnAllGroupInput).val('all');
		$(displayOnAllGroupInput).addClass('form-check-input');

		$(displayOnAllGroupLabel).addClass('form-check-label');
		$(displayOnAllGroupLabel).attr('for', 'alert-display-all-' + c);
		$(displayOnAllGroupLabel).html('All Pages');

		$(displayOnHomeGroup).append(displayOnHomeGroupInput);
		$(displayOnHomeGroup).append(displayOnHomeGroupLabel);

		$(displayOnAllGroup).append(displayOnAllGroupInput);
		$(displayOnAllGroup).append(displayOnAllGroupLabel);

		$(displayOnGroup).append(displayOnLabel);
		$(displayOnGroup).append(displayOnSmall);
		$(displayOnGroup).append(displayOnHomeGroup);
		$(displayOnGroup).append(displayOnAllGroup);

		return displayOnGroup;
	}

	function addBannerColorField( c ){
		var bannerColorGroup = document.createElement('DIV');
		var bannerColorLabel = document.createElement('LABEL');
		var bannerColorSmall = document.createElement('SMALL');
		var bannerColorInput = document.createElement('INPUT');

		
		$(bannerColorGroup).addClass('form-group col-sm pl-0');

		$(bannerColorLabel).attr('for', 'alert-banner-color-' + c);
		$(bannerColorLabel).addClass('d-block mb-0');
		$(bannerColorLabel).html('<strong>Banner Color</strong>');

		$(bannerColorSmall).addClass('text-muted d-block mb-2');
		$(bannerColorSmall).html('Select a color for the alert banner.');

		$(bannerColorInput).attr('id', 'alert-banner-color-' + c);
		$(bannerColorInput).attr('name', 'alert-banner-color-' + c);
		$(bannerColorInput).attr('type', 'color');
		$(bannerColorInput).addClass('form-control-sm');

		var color_scheme_picker = $('#ca_site_color_scheme')[0];
		var color = color_scheme_picker.options[color_scheme_picker.selectedIndex].value;

		$(bannerColorInput).val(caweb_admin_args.caweb_colors[color]['highlight']);

		$(bannerColorGroup).append(bannerColorLabel);
		$(bannerColorGroup).append(bannerColorSmall);
		$(bannerColorGroup).append(bannerColorInput);

		return bannerColorGroup;
	}

	function addReadMoreFields( c ){
		var readMoreGroup = document.createElement('DIV');
		var readMoreLabel = document.createElement('LABEL');

		var readMoreAnchor = document.createElement('A');
		
		var readMoreInput = document.createElement('INPUT');
		
		$(readMoreGroup).addClass('form-group pl-0');
		
		$(readMoreLabel).addClass('d-block mb-0');
		$(readMoreLabel).html('<strong>Read More Button</strong>');

		$(readMoreAnchor).attr('data-toggle', 'collapse');
		$(readMoreAnchor).attr('href', '#alert-banner-read-more-' + c);
		$(readMoreAnchor).attr('aria-expanded', 'true');
		$(readMoreAnchor).addClass('shadow-none');

		$(readMoreInput).attr('type', 'checkbox');
		$(readMoreInput).attr('checked', 'true');
		$(readMoreInput).attr('name', 'alert-banner-read-more-' + c);
		$(readMoreInput).attr('id', 'alert-banner-read-more-' + c);
		$(readMoreInput).addClass('form-control');

		$(readMoreAnchor).append(readMoreInput);

		$(readMoreGroup).append(readMoreLabel);
		$(readMoreGroup).append(readMoreAnchor);

		$(readMoreInput).bootstrapToggle();

		return readMoreGroup;
	}

	function addReadMoreSettings( c ){
		var readMoreSettings = document.createElement('DIV');
		var readMoreTextGroup = document.createElement('DIV');
		var readMoreTextLabel = document.createElement('LABEL');
		var readMoreTextInput = document.createElement('INPUT');
		var readMoreTextSmall = document.createElement('SMALL');
		var readMoreURLGroup = document.createElement('DIV');
		var readMoreURLInput = document.createElement('INPUT');
		var readMoreURLLabel = document.createElement('LABEL');
		var readMoreTargetGroup = document.createElement('DIV');
		var readMoreTargetInput = document.createElement('INPUT');
		var readMoreTargetLabel = document.createElement('LABEL');


		$(readMoreSettings).attr('id', 'alert-banner-read-more-' + c )
		$(readMoreSettings).addClass('collapse show');

		// Read More Text Group
		$(readMoreTextGroup).addClass('form-group col-sm-6 pl-0');

		$(readMoreTextLabel).addClass('d-block mb-0');
		$(readMoreTextLabel).html('<strong>Read More Button Text</strong>');

		$(readMoreTextInput).attr('type', 'text');
		$(readMoreTextInput).attr('name', 'alert-read-more-text-' + c);
		$(readMoreTextInput).attr('id', 'alert-read-more-text-' + c);
		$(readMoreTextInput).attr('maxlength', 16);
		$(readMoreTextInput).addClass('form-control');

		$(readMoreTextSmall).addClass('text-muted');
		$(readMoreTextSmall).html('(Max Characters: 16)');

		// Read More URL Group
		$(readMoreURLGroup).addClass('form-group col-sm-6 pl-0 d-inline-block');

		$(readMoreURLLabel).addClass('d-block mb-0');
		$(readMoreURLLabel).html('<strong>Read More Button Url</strong>');

		$(readMoreURLInput).attr('type', 'text');
		$(readMoreURLInput).attr('name', 'alert-read-more-url-' + c);
		$(readMoreURLInput).attr('id', 'alert-read-more-url-' + c);
		$(readMoreURLInput).addClass('form-control');
		
		// Read More Target Group
		$(readMoreTargetGroup).addClass('form-group col-sm-4 pl-0 d-inline-block align-top');

		$(readMoreTargetLabel).addClass('d-block mb-0');
		$(readMoreTargetLabel).html('<strong>Open link in New Tab</strong>')

		$(readMoreTargetInput).attr('type', 'checkbox');
		$(readMoreTargetInput).attr('checked', 'true');
		$(readMoreTargetInput).attr('data-toggle', 'toggle');
		$(readMoreTargetInput).attr('name', 'alert-read-more-target-' + c);
		$(readMoreTargetInput).attr('id', 'alert-read-more-target-' + c);
		$(readMoreTargetInput).addClass('form-control');

		$(readMoreTextGroup).append(readMoreTextLabel);
		$(readMoreTextGroup).append(readMoreTextInput);
		$(readMoreTextGroup).append(readMoreTextSmall);

		$(readMoreURLGroup).append(readMoreURLLabel);
		$(readMoreURLGroup).append(readMoreURLInput);
		
		$(readMoreTargetGroup).append(readMoreTargetLabel);
		$(readMoreTargetGroup).append(readMoreTargetInput);

		$(readMoreSettings).append(readMoreTextGroup);
		$(readMoreSettings).append(readMoreURLGroup);
		$(readMoreSettings).append(readMoreTargetGroup);
		
		$(readMoreTargetInput).bootstrapToggle({
			on: 'Yes',
			off: 'No'
		});

		return readMoreSettings;
	}

	function addIconField( c ){
		var alertIconGroup = document.createElement('DIV');

		$(alertIconGroup).addClass('form-group col-sm-12 d-inline-block pl-0');

		var data = {
			'action': 'caweb_icon_menu',
			'name': 'alert-icon-' + c,
			'select': 'important',
			'header': 'Icon'
		};

		$.post(ajaxurl, data, function(response) {
			$(alertIconGroup).html(response);
		});

		return alertIconGroup;
	}
});

/* CAWeb Icon Menu Javascript */
jQuery(document).ready(function($) {
	$(document).on('click', '.caweb-icon-menu li', function(e){cawebIconSelected(this);});
	$(document).on('click', '.caweb-icon-menu-header .resetIcon', function(e){ resetIconSelect($(this).parent().next());});

	function cawebIconSelected(iconLi){
		resetIconSelect($(iconLi).parent());
		$(iconLi).addClass('active');

		var i = $(iconLi).parent().find('input');

		if (i.length){
			$(i).val($(iconLi).attr('title'));
		}
	}

	function resetIconSelect(iconList){
		var icon_list = $(iconList).find('LI');
		
		for(o = 0; o < icon_list.length - 1; o++){
			$(icon_list[o]).removeClass('active');
		}

		var i = $(iconList).find('input');

		if (i.length){
			$(i).val('');
		}
	}
	
});
  

// Toggle CSS Colorscheme Options
jQuery( document ).ready( function($) {
	$('select[id$="ca_site_version"]').on("change", correct_colorscheme_visibility );

	function correct_colorscheme_visibility(){
		var color_scheme_picker = $('select[id$="ca_site_color_scheme"]');
		var current_color = color_scheme_picker.val();
		var new_colors = caweb_admin_args.caweb_colorschemes[$(this).val()];

		color_scheme_picker.empty();

		$.each(new_colors, function(i, ele){
			var o = document.createElement( 'OPTION' );

			$(o).val( i );
			$(o).html( ele.displayname );

			if( i === current_color ){
				$(o).attr('selected', 'selected');
			}

			color_scheme_picker.append( o );
		});

	}

});
/* CAWeb Uploads Option */
jQuery(document).ready(function($) {
	
  /*
    Custom CSS/JS
  */
 
  if( $( "#uploaded-css, #uploaded-js" ).length ){
	$( "#uploaded-css, #uploaded-js" ).sortable();
	$( "#uploaded-css, #uploaded-js" ).disableSelection();
  }

  // Remove Uploaded CSS/JS
  $('.remove-css, .remove-js').click(function(e){
    e.preventDefault();
    var r = confirm("Are you sure you want to remove " + this.title + "? This can not be undone.");
  
    if (r == true) {
      changeMade = true;
      this.parentNode.remove();
    }
  });

  // Add New CSS
$('#add-css, #add-js').click(function(e){
	e.preventDefault();
	var ext =  $(this).attr('id').replace('add-', '');
	var ulID = '#uploaded-' + ext;

	addExternal($(ulID), ext);
	changeMade = true;
});

function addExternal(ext_list, ext){
  var li = document.createElement('LI');
  var fileUpload = document.createElement('INPUT');
  var rem = document.createElement('a');

  li.classList = "list-group-item";

  // File Upload
  $(fileUpload).attr('type', "file");
  $(fileUpload).attr('name', "caweb_external_" + ext + "[]");
  $(fileUpload).attr('accept', "." + ext);
  $(fileUpload).attr('data-section', "custom-" + ext);
  $(fileUpload).addClass("form-control-file border-bottom border-warning pl-2 d-inline-block w-75");

  fileUpload.addEventListener('change', function () {
    var name = this.value.substring(this.value.lastIndexOf("\\") + 1);
    var extension = name.lastIndexOf(".") > 0 ?
            name.substring(name.lastIndexOf(".") + 1).toLowerCase() : "";
  
    if( "" === extension || ext !== extension){
      alert(name + " isn't a valid " + ext + " extension and was not uploaded.");
      $(this).parent().remove();
    }else{
      rem.title = "remove " + name;
    }
  
  });
  
  // Remove Newly Added Item
  rem.classList = "dashicons dashicons-dismiss text-danger align-middle";
  rem.addEventListener('click', function (e) {
    e.preventDefault();
    var r = "" !== this.title ? confirm("Are you sure you want to " + this.title + "? This can not be undone.") : true;
  
   if (r == true) {
      changeMade = true;
      $(this).parent().remove();
    }
  });

  $(li).append(rem);
  $(li).append(fileUpload);

  $(ext_list).append(li);

}
});
  

/* nav-menus.php Javascript  */
jQuery(document).ready(function($) {
  "use strict";
  /* Alt Text Check */
  $(document).on('click', 'input[name="save_menu"]', function(e){
	  var nav_menu_alt_texts = $('.media-image:not(.hidden) input[name$="_caweb_nav_media_image_alt_text"]');


    nav_menu_alt_texts.each(function(i,ele) {
		  if( "" === $(ele).val().trim() ){
        var menu_id = $(ele).attr('id').substring(0, $(ele).attr('id').indexOf("_") );
			  var title = $("#edit-menu-item-title-" + menu_id).val();
			  alert(title + " Navigation Media Image Alt Text can not be blank.")
			  e.preventDefault();
		  }
	  });

  });

  /* Unit Size Selector */
  $(document).on('change', 'div .unit-size-selector', function(){
    var menu_id = $(this).attr('id').substr($(this).attr('id').lastIndexOf('-') + 1);
    var icon_selector = $('#menu-item-settings-' + menu_id + ' .icon-selector');
    var media_image = $('#menu-item-settings-' + menu_id + ' .media-image');
    var desc = $('#menu-item-settings-' + menu_id + ' .field-description');
    var unit_size = $(this).val();

    if( 'unit1' === unit_size){
        // Hide Description
        $(desc).addClass('hidden-field');
    }else{
        // Display Description
        $(desc).removeClass('hidden-field');
    }

    if('unit3' === unit_size ){
        // Display Navigation Media Image
        $(media_image).removeClass('hidden');

        // Hide Icon Selector
        $(icon_selector).addClass('hidden');

    }else{
        // Hide Navigation Media Image
        $(media_image).addClass('hidden');
        
        // Display Icon Selector
        $(icon_selector).removeClass('hidden');
    }
    
  });

  /* New Row */
  $(document).on('click', 'div .new-row', function(){
    var menu_id = $(this).attr('id').substr(0, $(this).attr('id').indexOf('_') );
    var border = $('#menu-item-settings-' + menu_id + ' .flexmega-border');

    if( $(this).is(':checked') ){
      $(border).removeClass('hidden');
    }else{
      $(border).addClass('hidden');
    }
  });

  /* Item Menu Editing */
  $(document).on('DOMSubtreeModified', '#menu-to-edit', function() {
    
    // Grab array of all pending menu items
     var list_items = $('.pending a.item-edit');
     
     list_items.each(function(i, e){
      // Add menu selection to menu edit
      $(e).on('click', menu_selection);
     });
  });

  $('.item-edit').click(menu_selection);
  
  function menu_selection(){
    var menu_id = $(this).attr('id').substr($(this).attr('id').lastIndexOf('-') + 1);
    var menu_li = $('#menu-item-' + menu_id);
    var icon_selector = $('#menu-item-settings-' + menu_id + ' .icon-selector');
    var media_image = $('#menu-item-settings-' + menu_id + ' .media-image');
    var desc = $('#menu-item-settings-' + menu_id + ' .field-description');
    var mega_menu_images = $('#menu-item-settings-' + menu_id + ' .mega-menu-images');
    var unit_selector = $('#menu-item-settings-' + menu_id + ' .unit-size-selector');
    var unit_size = $(unit_selector).val();
    var flex_border = $('#menu-item-settings-' + menu_id + ' .flexmega-border');
    var flex_row = $('#menu-item-settings-' + menu_id + ' .flexmega-row');

    /*
    if the menu item is a top level menu item
    depth = 0
    */
    if( $(menu_li).hasClass('menu-item-depth-0') ){
      // Show Mega Menu Options
      $(mega_menu_images).removeClass('hidden');

      // Show Icon Selector
      $(icon_selector).removeClass('hidden');

      // Hide Nav Media Images, Unit Size Selector, Description, FlexBorder
      $(media_image).addClass('hidden');
      $(unit_selector).parent().addClass('hidden');
      $(desc).addClass('hidden-field');
      $(flex_border).addClass('hidden');
      $(flex_row).addClass('hidden');
      
    }else{
      // Hide Mega Menu Options
      $(mega_menu_images).addClass('hidden');
     
      // Show Unit Selector
      $(unit_selector).parent().removeClass('hidden');

      // Show Row and FlexBorder
      $(flex_row).removeClass('hidden');

      if( $(flex_row).find('input').is(':checked') ){
        $(flex_border).removeClass('hidden');
      }else{
        $(flex_border).addClass('hidden');
      }

      if( 'unit1' !== unit_size ){
        // Hide Description
        $(desc).addClass('hidden-field');
      }else{
        // Show Description
        $(desc).removeClass('hidden-field');
      }

      
      if( 'unit3' === unit_size ){
        // Hide Icon Selector
        $(icon_selector).addClass('hidden');

        // Show Nav Media Images
        $(media_image).removeClass('hidden');
      }else{
        // Show Icon Selector
        $(icon_selector).removeClass('hidden');

        // Hide Nav Media Images
        $(media_image).addClass('hidden');
      }

      
    }
  }
});

/* CAWeb Options Javascript */
jQuery(document).ready(function($) {
  "use strict";
  var changeMade = false;

  $(window).on('beforeunload', function(){
	  if( changeMade && "nav-menus.php" !== caweb_admin_args.changeCheck)
			  return 'Are you sure you want to leave?';

  });

  $('#caweb-options-form select,#caweb-options-form input').on( 'change', function(){  changeMade = true;  });
  $('#caweb-options-form input').on('input', function(){  changeMade = true;  });
  $('#caweb-options-form input[type="button"],#caweb-options-form button:not(.doc-sitemap)').on('click', function(){  changeMade = true;  });

  $('#caweb-options-form').submit(function(e){ 
	  e.preventDefault();
		var upload_files = $('input[name="caweb_external_css[]"], input[name="caweb_external_js[]"]');	
		var empty_file = false;

		$(upload_files).each(function(i){
			if( "" === $(this).val() && ! empty_file ){
				empty_file = true;
				var section_id = '#' + $(this).attr('data-section');

				$(section_id).collapse('show');

				alert( "Uploaded " + $(this).attr('data-section').replace('-', ' ') + " has no file chosen." );
			}
		});
		
		if( ! empty_file ){
			changeMade = false; 
			this.submit(); 
		}
	
	});

  $('.menu-list li a').on('click', function(e){
    $(this).parent().parent().find('li').each(function(i, ele){
      $(ele).removeClass('selected');
    })

    $(this).parent().addClass('selected');
    $('input[name="tab_selected"]').val($(this).attr('href').replace('#', ''));
  });

  // Reset Fav Icon
  $('#resetFavIcon').click(function() {
    var ico = caweb_admin_args.defaultFavIcon;
    var icoName = ico.substring( ico.lastIndexOf('/') + 1 );

    $('input[type="text"][name="ca_fav_ico"]').val(icoName);
    $('input[type="hidden"][name="ca_fav_ico"]').val(ico);
    $('#ca_fav_ico_img').attr('src', ico);

    changeMade = true;
  });

  // If no Search Engine ID hide Search on Front Page Option
  $('#ca_google_search_id').on('input',function(e){
    var front_search_option = $('label[for="ca_frontpage_search_enabled"]').parent();

    // if theres no Google Search ID
    if( !this.value.trim() ){
      front_search_option.addClass('invisible');
    }else{
      front_search_option.removeClass('invisible');
    }
  });

  // Display warning if Legacy Browser Support Enabled
  $('#ca_x_ua_compatibility').on('change',function(e){
    var isChecked = this.checked;
    var respSpan = $(this).parent().next();
  
    if(isChecked){
      respSpan.html('IE 11 browser compatibility enabled. Warning: creates accessibility errors when using IE browsers.')
    }else{
      respSpan.html('');
    }	
  });

  // If Google Tag Manager Preview approved, disable Analytics iD
  $('#ca_google_tag_manager_approved').on('change', function(e){
      if( this.checked ){
        $('#ca_google_analytic_id').attr('readonly', true);
        $('#ca_google_analytic_id').parent().addClass('hidden');
      }else{
        $('#ca_google_analytic_id').attr('readonly', false);
        $('#ca_google_analytic_id').parent().removeClass('hidden');
      }
  });
  // If no Tag Manager ID unapprove Preview
  $('#ca_google_tag_manager_id').on('input',function(e){
    // if theres no Tage Manager ID
    if( !this.value.trim() ){
		$('#ca_google_tag_manager_approved').bootstrapToggle('off');
    }
  });
  // If Google Translate is set to Custom, show extra options
  $('input[name^="ca_google_trans_enabled"]').click(function(){
    if( 'ca_google_trans_enabled_custom' !== $(this).attr('id') ){
      $('#ca_google_trans_enabled_custom_extras').collapse('hide');
    }else{
      $('#ca_google_trans_enabled_custom_extras').collapse('show');
    }
  });

  // Generate Document Sitemap
  $('button.doc-sitemap').click(function(e){
    e.preventDefault();
    var data = {
      'action': 'create_doc_sitemap',
    };

    $.post(ajaxurl, data, function(response) {
      $('.doc-sitemap-update').html(response);
    });
  });

});
