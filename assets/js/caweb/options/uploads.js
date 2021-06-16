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
  
