jQuery(document).ready(function() {
    /* 
    Divi Tab Module Accessibility 
    Retrieve all Divi Tab Modules
    */
   var tab_modules = $('div').filter(function(){ return this.className.match(/\bet_pb_tabs_\d\b/); });

    // Run only if there is a Tab Module on the current page
    if( tab_modules.length ){
        setTimeout(function(){
            tab_modules.each(function(index, element) {
                // Grab each tab control list
                var tab_list =  $(element).find('.et_pb_tabs_controls');
                
                // Lowercase the Tab Control Role
                $(tab_list).attr('role', 'tablist' );
    
            });  
        }, 100);

            
    }
});