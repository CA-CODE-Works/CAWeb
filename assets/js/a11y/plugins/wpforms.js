jQuery(document).ready(function() {
	/*
	WPForms Accessibility 
	Retrieve radio field containers
	*/
	var wpforms_radio_fields = $('.wpforms-field.wpforms-field-radio')

	if( wpforms_radio_fields.length ){
		wpforms_radio_fields.each(function(index, element) {
			$(element).attr('role', 'radiogroup');
			$(element).attr('aria-label', 'WPForms Radio Group');
		});
	}
	
	/*
	WPForms Accessibility 
	Retrieve checkbox containers
	*/
	var wpforms_checkbox_fields = $('.wpforms-field.wpforms-field-checkbox')

	if( wpforms_checkbox_fields.length ){
		wpforms_checkbox_fields.each(function(index, element) {
			$(element).attr('role', 'group');
			$(element).attr('aria-label', 'WPForms Checkbox Group');
		});
	}

	/*
	WPForms Accessibility 
	Retrieve Submit button
	*/
	var wpforms_submit = $('.wpforms-submit[aria-live="assertive"]');

	if( wpforms_submit.length ){
		wpforms_submit.each(function(index, element) {
			$(element).attr('aria-atomic', 'true');
		});
	}
	
	/*
	WPForms Accessibility 
	Retrieve Date/Time Time Picker inputs
	*/
	var wpforms_time_pickers = $('input.wpforms-field-date-time-time');
	if( wpforms_time_pickers.length ){
		wpforms_time_pickers.each(function(index, element) {
			var label = $(element).parent().find('label');
			$(label).attr('for', $(element).attr('id') );
		});
	}

	/*
	WPForms Accessibility 
	Retrieve Date/Time Combo Picker inputs
	*/
	var wpforms_date_pickers = $('div:not(.wpforms-field) > input.wpforms-field-date-time-date');
	if( wpforms_date_pickers.length ){
		wpforms_date_pickers.each(function(index, element) {
			var field_id = $(element).attr('id');
			var l = $(element).parent().find('label');

			$(element).attr('id', field_id + '-date');
			$(l).attr('for', field_id + '-date');

			var label = $('div#' + field_id + '-container label[for="' + field_id + '"]')

			if( label.length ){
				label = $(label).html() + " ";
			}

			if( $('label[for="' + field_id + '-date"]').length ){
				var ld = $('label[for="' + field_id + '-date"]');
				$(ld).html(label + $(ld).html());
			}

			if( $('label[for="' + field_id + '-time"]').length ){
				var lt = $('label[for="' + field_id + '-time"]');
				$(lt).html(label + $(lt).html());
			}

		});
	}
});