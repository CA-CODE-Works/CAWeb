jQuery(document).ready(function() {
	/* 
	Google Calendar Accessibility 
	*/
	var google_calendar_elements = $('iframe[src^="https://calendar.google.com/calendar/embed"]');

	if( google_calendar_elements.length ){
		google_calendar_elements.each(function(index, element) {
			stripeIframeAttributes(element);
			title = google_calendar_elements.length > 1 ? 'Google Calendar Embed ' + ( index + 1): 'Google Calendar Embed';
			$(element).attr('title', title);
		});
	}
});