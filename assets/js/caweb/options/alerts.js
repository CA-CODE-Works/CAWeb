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
