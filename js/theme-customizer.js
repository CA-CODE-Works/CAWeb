jQuery( document ).ready( function($) {
	wp.customize( 'caweb_alert_banner_0', function( value ) {
		value.bind( function( newval ) {
			console.log( $(this).attr('name') );
		});
	});
});
jQuery( document ).ready( function($) {
	// Utility Header Contact Us Link
	wp.customize( 'ca_contact_us_link', function( value ) {
		value.bind( function( newval ) {
			if( ! $('.utility-header:first .settings-links .utility-contact-us').length ){
				var settings_button = $('.utility-header:first .settings-links button[data-target="#siteSettings"]');
				var google_translate = $('.utility-header:first .settings-links #google_translate_element');
				var google_custom_translate = $('.utility-header:first .settings-links #caweb-gtrans-custom');

				if( google_translate.length ){
					$('<a class="hidden utility-contact-us">Contact Us</a>').insertBefore( $(google_translate) );
				}else if( google_custom_translate.length ){
					$('<a class="hidden utility-contact-us">Contact Us</a>').insertBefore( $(google_custom_translate) );
				}else{
					$('<a class="hidden utility-contact-us">Contact Us</a>').insertBefore( $(settings_button) );
				}
			}

			var contact_us_link = $('.utility-header:first .settings-links .utility-contact-us');

			if( ! newval.trim() ){
				contact_us_link.addClass('hidden');
			}else{
				contact_us_link.attr('href', encodeURI( newval.trim() ) );
				contact_us_link.removeClass('hidden');
			}
		});
	});
});
jQuery( document ).ready( function($) {
	// Search on Front Page
	wp.customize( 'ca_frontpage_search_enabled', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
			if( newval ){
				$('#head-search').css('top', '240px');
				$('#head-search').addClass('featured-search'); 
				$('#head-search').removeClass('active');
			}else{
				$('#head-search').removeClass('featured-search'); 
				$('#head-search').attr('style', ''); 
			}
		});
	});
});
jQuery( document ).ready( function($) {
	// Bind to Google Translate Custom Translate Page 
	wp.customize( 'ca_google_trans_page', function( value ) {
		value.bind( function( newval ) {
			if( newval.trim() ){ 
				$('#caweb-gtrans-custom').attr('href', encodeURI( newval.trim() ) );
				$('#caweb-gtrans-custom').removeClass('hidden');
			}else{
				$('#caweb-gtrans-custom').addClass('hidden');
			}
		});
	});

	// Bind to Google Translate Custom CAWeb_Customize_Icon_Control 
	wp.customize( 'ca_google_trans_icon', function( value ) {
		value.bind( function( newval ) {
			var icon = $('#caweb-gtrans-custom span');
			if( ! newval.trim() ){
				icon.addClass('hidden');
			}else{
				icon.attr( "class",  "ca-gov-icon-" + newval.trim);
			}
		});
	});

});
jQuery( document ).ready( function($) {
	// Organization Logo Brand
	wp.customize( 'header_ca_branding', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
			var org_logo = $('.header-organization-banner a img');
			
			if( org_logo.length ){
				org_logo.attr('src', newval );
			}
		});
	});
});

jQuery( document ).ready( function($) {
	// Menu Home Link
	wp.customize( 'ca_home_nav_link', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
			if(-1 == $('#nav_list li:first').hasClass('nav-item-home') ){
				$('#nav_list').prepend('<li class="nav-item nav-item-home hidden"><a href="/" class="first-level-link"><span class="ca-gov-icon-home"></span> Home</a></li>');
			}
			if( newval ){
				if( '/' == document.location.pathname ){
					alert( "This feature is not visible on the Front Page." );
				}else{
					$('#nav_list li:first').removeClass('hidden');
				}
			}else{
				$('#nav_list li:first').addClass('hidden');
			}
		});
	});
});
jQuery( document ).ready( function($) {
	// Sticky Navigation
	var current_padding = $('#main-content').css('padding-top');
	wp.customize( 'ca_sticky_navigation', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
			if( newval ){
				$('body').addClass('sticky_nav');
				$('#header').addClass('fixed');
				$('#main-content').css('padding-top', current_padding);
			}else{        
				$('body').removeClass('sticky_nav');
				$('#header').removeClass('fixed');
				$('#main-content').css('padding-top', 0);
			}
		});
	});
});
jQuery( document ).ready( function($) {
	// Utility Header Custom Link 1 Text
	wp.customize( 'ca_utility_link_1_name', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-1'), newval );
		});
	});

	// Utility Header Custom Link 1 URL
	wp.customize( 'ca_utility_link_1', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-1'), newval, 'href' );
		});
	});

	// Utility Header Custom Link 1 Target
	wp.customize( 'ca_utility_link_1_new_window', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-1'), newval, 'target' );
		});
	});

	// Utility Header Custom Link 2 Text
	wp.customize( 'ca_utility_link_2_name', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-2'), newval );
		});
	});

	// Utility Header Custom Link 2 URL
	wp.customize( 'ca_utility_link_2', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-2'), newval, 'href' );
		});
	});

	// Utility Header Custom Link 2 Target
	wp.customize( 'ca_utility_link_2_new_window', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-2'), newval, 'target' );
		});
	});
	
	// Utility Header Custom Link 3 Text
	wp.customize( 'ca_utility_link_3_name', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-3'), newval );
		});
	});

	// Utility Header Custom Link 3 Url
	wp.customize( 'ca_utility_link_3', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-3'), newval, 'href' );
		});
	});

	wp.customize( 'ca_utility_link_3_new_window', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data) 
			update_custom_link( $('.utility-header .settings-links .utility-custom-3'), newval, 'target' );
		});
	});

	function update_custom_link( link, value, attr = 'html' ){
		if( link.length ){
			if( 'href' == attr ){
				$(link).attr( 'href', encodeURI( value ) );
			}else if( 'target' == attr ){
				if( value ){
					$(link).attr( 'target', '_blank' );					
				}else{
					$(link).attr( 'target', '_self' );					
				}
			}else if( 'html' == attr){
				$(link).html( value );
			}
		}
	}

});
jQuery( document ).ready( function($) {
	// Utility Header Home Link
	wp.customize( 'ca_utility_home_icon', function( value ) {
		value.bind( function( newval ) {
			//Do stuff (newval variable contains your "new" setting data)
			if( ! $('.utility-header:first .social-media-links .utility-home-icon').length ){
				$('<a href="/" title="Home" class="hidden utility-home-icon ca-gov-icon-home"><span class="sr-only">Home</span></a>').insertAfter( $('.utility-header:first .social-media-links .header-cagov-logo') );
			}

			var home_link = $('.utility-header:first .social-media-links .utility-home-icon');
			
			if( newval ){
				home_link.removeClass('hidden');
			}else{
				home_link.addClass('hidden');
			}
		});
	});
});