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
