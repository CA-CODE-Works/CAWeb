jQuery(document).ready(function() {
	/*
	Divi Toggle Module Accessibility
	Retrieve all Divi Toggle Modules
   */
	var toggle_modules = $('div.et_pb_toggle');

	// Run only if there is a Toggle Module on the current page
	if( toggle_modules.length  ){
		toggle_modules.each(function(index, element) {
			var expanded = $(element).hasClass('et_pb_toggle_open') ?  'true' : 'false' ;

			$(element).attr('tabindex', 0);
			$(element).attr('role', 'button');
			$(element).attr('aria-expanded', expanded);

			// Events
			$(element).on('click keydown', function(e){
				// Shows or hides content in accordion when Enter or Space key is pressed
				if (e.type === 'keydown') {
					var toggleKeys = [13, 32]; // key codes for enter and space, respectively
					var toggleKeyPressed = toggleKeys.includes(e.which);

					if (toggleKeyPressed) {
						setTimeout( function(){
							$(element).toggleClass('et_pb_toggle_open');
							$(element).toggleClass('et_pb_toggle_close');

							if ($(element).hasClass('et_pb_toggle_open')) {
								$(element).find('.et_pb_toggle_content').css('display', 'block');
							} else {
								$(element).find('.et_pb_toggle_content').css('display', 'none')
							}
						}, 500);
					}

					// Prevents spacebar from scrolling page to the bottom
					if (e.which === 32) {
						e.preventDefault();
					}
				}

				// Modifies value for aria-expanded attribute
				// when toggle is clicked or Enter/Space key is pressed
				if (e.type === 'click' || toggleKeyPressed) {
					setTimeout( function(){
						var expanded = $(element).hasClass('et_pb_toggle_open') ?  'true' : 'false' ;
						$(element).attr('aria-expanded', expanded);
					}, 1000 );
				}
			});
		});
	}
});
