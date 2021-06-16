jQuery(document).ready(function() {
   /* 
   Retrieve all Divi Gallery Modules
   */
   var gallery_modules = $('div').filter(function(){ return this.className.match(/\bet_pb_gallery\b/); });

    // Run only if there is a Slider Module on the current page
    if( gallery_modules.length ){

        gallery_modules.each(function(index, element) {
            // Grab all gallery images
            var gallery_images = $(element).find('.et_pb_gallery_image img');
            gallery_images.each(function(i, g){
                // add the value of the anchors title to the alt text of the image
                $(g).attr('alt',$(g).parent().attr('title') );
            })
        });      

    }

});