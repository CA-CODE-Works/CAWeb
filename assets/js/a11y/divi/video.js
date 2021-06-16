jQuery(document).ready(function() {
	/*
    Divi Video Module Accessibility
    Retrieve all Divi Video Modules
    */
	var video_modules = $('div.et_pb_video');

    /*
    Divi Video Slider Module Accessibility
    Retrieve all Divi Video Modules
    */
    var video_slider_modules = $('div').filter(function(){ return this.className.match(/\bet_pb_video_slider_\d\b/); });

    // Run only if there is a Video Module on the current page
    if( video_modules.length  ){
        video_modules.each(function(index, element) {
            var frame = $(element).find('iframe');
            frame.attr('title', 'Divi Video Module IFrame ' + (index + 1));
            $(frame).removeAttr('frameborder');
            $(frame).attr('id', 'fitvid' + (index + 1));

            var src = $(frame).attr('src');
            $(frame).attr('src', src + '&amp;rel=0');

        });      
    }

    
    // Run only if there is a Video Slider Module Items on the current page
    if( video_slider_modules.length  ){
        video_slider_modules.each(function(index, element) {
            var slides = $(element).find('.et_pb_slide');

            slides.each(function(i, s){
                play_button = $(s).find('.et_pb_video_play');
                carousel_play = $(element).find('.et_pb_carousel_item.position_' + ( i + 1 ) ).find('.et_pb_video_play');
                
                $(play_button).addClass('no-underline');
                $(play_button).attr('title', 'Play Video ' + ( i + 1 ) );

                if( carousel_play.length ){
                    $(carousel_play).addClass('no-underline');
                    $(carousel_play).attr('title', 'Play Video ' + ( i + 1 ) );
                }
            })
        });      
    }
});