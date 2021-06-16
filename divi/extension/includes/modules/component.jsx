// External Dependencies
// eslint-disable-next-line
import React, {Component, Fragment} from 'react';
import NumberFormat from 'react-number-format';
import Moment from 'react-moment';
import moment from 'moment';

class CAWeb_Component extends Component {
	
	constructor(props) {
		super(props);
		this.caweb_google_maps_embed_api_key = 'AIzaSyCtq3i8ME-Ab_slI2D8te0Uh2PuAQVqZuE';
		this.icon_list = ["logo", "home", "menu", "apps", "search", "chat", "capitol", "state", "phone", "email", "contact-us", "calendar", "bear", "chat-bubble", "info-bubble", "share-button", "share-facebook", "share-email", "share-flickr", "share-twitter", "share-linkedin", "share-googleplus", "share-instagram", "share-pinterest", "share-vimeo", "share-youtube", "law-enforcement", "justice-legal", "at-sign", "attachment", "zipped-file", "powerpoint", "excel", "word", "pdf", "share", "facebook", "linkedin", "youtube", "twitter", "pinterest", "vimeo", "instagram", "flickr", "google-plus", "microsoft", "apple", "android", "computer", "tablet", "smartphone", "roadways", "travel-car", "travel-air", "truck-delivery", "construction", "bar-chart", "pie-chart", "graph", "server", "download", "cloud-download", "cloud-upload", "shield", "fire", "binoculars", "compass", "sos", "shopping-cart", "video-camera", "camera", "green", "loud-speaker", "audio", "print", "medical", "zoom-out", "zoom-in", "important", "chat-bubbles", "call", "people", "person", "user-id", "payment-card", "skip-backwards", "play", "pause", "skip-forward", "mail", "image", "house", "gear", "tool", "time", "cal", "check-list", "document", "clipboard", "page", "read-book", "cc-copyright", "ca-capitol", "ca-state", "favorite", "rss", "road-pin", "online-services", "link", "magnify-glass", "key", "lock", "info", "arrow-up", "arrow-down", "arrow-left", "arrow-right", "carousel-prev", "carousel-next", "arrow-prev", "arrow-next", "menu-toggle-closed", "menu-toggle-open", "carousel-play", "carousel-pause", "search-right", "graduate", "briefcase", "images", "gears", "tools", "pencil", "pencil-edit", "science", "film", "table", "flowchart", "building", "searching", "wallet", "tags", "currency", "idea", "lightbulb", "calculator", "drive", "globe", "hourglass", "mic", "volume", "music", "folder", "grid", "archive", "contacts", "book", "drawer", "map", "pushpin", "location", "quote-fill", "question-fill", "warning-triangle", "warning-fill", "check-fill", "close-fill", "plus-fill", "minus-fill", "caret-fill-right", "caret-fill-left", "caret-fill-down", "caret-fill-up", "caret-fill-two-right", "caret-fill-two-left", "caret-fill-two-down", "caret-fill-two-up", "arrow-fill-right", "arrow-fill-left", "arrow-fill-up", "arrow-fill-down", "arrow-fill-left-down", "arrow-fill-right-down", "arrow-fill-left-up", "arrow-fill-right-up", "triangle-line-right", "triangle-line-left", "triangle-line-up", "triangle-line-down", "caret-line-two-right", "caret-line-two-left", "caret-line-two-up", "caret-line-two-down", "caret-line-right", "caret-line-left", "caret-line-up", "caret-line-down", "important-line", "info-line", "check-line", "question-line", "close-line", "plus-line", "minus-line", "question", "minus-mark", "plus-mark", "collapse", "expand", "check-mark", "close-mark", "triangle-right", "triangle-left", "triangle-up", "triangle-down", "caret-two-right", "caret-two-left", "caret-two-down", "caret-two-up", "caret-right", "caret-left", "caret-up", "caret-down", "filter", "caweb", "arrow_up", "arrow_down", "arrow_left", "arrow_right", "arrow_left-up", "arrow_right-up", "arrow_right-down", "arrow_left-down", "arrow-up-down", "arrow_up-down_alt", "arrow_left-right_alt", "arrow_left-right", "arrow_expand_alt2", "arrow_expand_alt", "arrow_condense", "arrow_expand", "arrow_move", "arrow_back", "icon_zoom-out_alt", "icon_zoom-in_alt", "icon_box-empty", "icon_box-selected", "icon_box-checked", "icon_circle-empty", "icon_circle-slelected", "icon_stop_alt2", "icon_stop", "icon_pause_alt2", "icon_pause", "icon_menu", "icon_menu-square_alt2", "icon_menu-circle_alt2", "icon_ul", "icon_ol", "icon_adjust-horiz", "icon_adjust-vert", "icon_document_alt", "icon_documents_alt", "icon_pencil-edit_alt", "icon_folder-alt", "icon_folder-open_alt", "icon_folder-add_alt", "icon_error-circle_alt", "icon_error-triangle_alt", "icon_comment_alt", "icon_chat_alt", "icon_vol-mute_alt", "icon_volume-low_alt", "icon_volume-high_alt", "icon_quotations", "icon_quotations_alt2", "icon_clock_alt", "icon_lock_alt", "icon_lock-open_alt", "icon_key_alt", "icon_cloud_alt", "icon_cloud-upload_alt", "icon_cloud-download_alt", "icon_lightbulb_alt", "icon_house_alt", "icon_laptop", "icon_camera_alt", "icon_mail_alt", "icon_cone_alt", "icon_ribbon_alt", "icon_bag_alt", "icon_creditcard", "icon_cart_alt", "icon_paperclip", "icon_tag_alt", "icon_tags_alt", "icon_trash_alt", "icon_cursor_alt", "icon_mic_alt", "icon_compass_alt", "icon_pin_alt", "icon_pushpin_alt", "icon_map_alt", "icon_drawer_alt", "icon_toolbox_alt", "icon_book_alt", "icon_calendar", "icon_contacts_alt", "icon_headphones", "icon_refresh", "icon_link_alt", "icon_link", "icon_loading", "icon_blocked", "icon_archive_alt", "icon_heart_alt", "icon_printer", "icon_calulator", "icon_building", "icon_floppy", "icon_drive", "icon_search", "icon_id", "icon_id-2", "icon_puzzle", "icon_like", "icon_dislike", "icon_mug", "icon_currency", "icon_wallet", "icon_pens", "icon_easel", "icon_flowchart", "icon_datareport", "icon_briefcase", "icon_shield", "icon_percent", "icon_globe", "icon_target", "icon_balance", "icon_star_alt", "icon_star-half_alt", "icon_star-half", "icon_cog", "icon_cogs", "arrow_condense_alt", "arrow_expand_alt3", "icon_zoom-out", "icon_zoom-in", "icon_stop_alt", "icon_menu-square_alt", "icon_menu-circle_alt", "icon_document", "icon_documents", "icon_pencil_alt", "icon_folder", "icon_folder-add", "icon_folder_upload", "icon_folder_download", "icon_error-circle", "icon_comment", "icon_chat", "icon_vol-mute", "icon_volume-low", "icon_clock", "icon_lock", "icon_lock-open", "icon_key", "icon_cloud", "icon_cloud-upload", "icon_cloud-download", "icon_gift", "icon_house", "icon_mail", "icon_cone", "icon_ribbon", "icon_bag", "icon_cart", "icon_tag", "icon_trash", "icon_cursor", "icon_compass", "icon_heart", "icon_pause_alt", "icon_phone", "icon_upload", "icon_download", "icon_rook", "icon_floppy_alt", "icon_id_alt", "icon_puzzle_alt", "icon_like_alt", "icon_dislike_alt", "icon_mug_alt", "icon_pens_alt", "icon_briefcase_alt", "icon_shield_alt", "icon_percent_alt", "icon_globe_alt", "icon_clipboard", "social_googleplus", "social_tumblr", "social_tumbleupon", "social_wordpress", "social_dribbble", "social_deviantart", "social_myspace", "social_skype", "social_picassa", "social_googledrive", "social_flickr", "social_blogger", "social_spotify", "social_delicious", "social_facebook_circle", "social_twitter_circle", "social_pinterest_circle", "social_googleplus_circle", "social_tumblr_circle", "social_stumbleupon_circle", "social_wordpress_circle", "social_instagram_circle", "social_dribbble_circle", "social_vimeo_circle", "social_linkedin_circle", "social_rss_circle", "social_deviantart_circle", "social_share_circle", "social_myspace_circle", "social_skype_circle", "social_youtube_circle", "social_picassa_circle", "social_googledrive_alt2", "social_flickr_circle", "social_blogger_circle", "social_spotify_circle", "social_delicious_circle", "social_tumblr_square", "social_stumbleupon_square", "social_wordpress_square", "social_instagram_square", "social_dribbble_square", "social_rss_square", "social_deviantart_square", "social_share_square", "social_myspace_square", "social_skype_square", "social_picassa_square", "social_googledrive_square", "social_flickr_square", "social_blogger_square", "social_spotify_square", "social_delicious_square", "toggle", "tabs", "subscribe", "slider", "sidebar", "share2", "pricing-table", "portfolio", "number-counter", "header", "filtered-portfolio", "divider", "cta", "countdown", "circle-counter", "blurb", "bar-counters", "audio2", "accordion", "icon_gift_alt", "code", "hours", "hours-security", "albums", "brain", "certificate", "certificate-check", "charge", "charge-cycle", "charge-units", "city", "clock", "cloud-gear", "cloud-services", "cloud-sync", "ear", "ear-slash", "eye", "eye-slash", "file", "file-audio", "file-certificate", "file-check", "file-code", "file-csv", "file-download", "file-excel", "file-export", "file-import", "file-invoice", "file-medical", "file-medical-alt", "file-pdf", "file-powerpoint", "file-prescription", "file-upload", "file-video", "file-word", "file-zip", "filter-solid", "fingerprint", "fingerprint-check", "hand", "hand-money", "handshake", "institute", "medical-bubble", "medical-care", "medical-case", "medical-clinic", "medical-cross", "medical-doctor", "medical-heart", "medical-pills", "mobile", "pro-services", "puzzle", "puzzle-piece", "recycle", "responsive", "responsive-alt", "security-network", "security-system", "shield-check", "thumb-up", "trophy", "users", "users-alt", "users-dialog", "users-interaction", "video", "beaker3", "beaker4", "beaker5", "candle-alt", "cal-bear", "biohazard", "malware", "radiation", "chemical-hazard", "danger", "do-not-sign", "earthquake", "quake-house", "quake-hazard", "electricity-hazard", "flood", "hazard", "hurricane", "sea-level-rise", "severe-weather", "stop-fire", "stop-hand", "tornado", "tsunami", "volcano", "warning-circle", "warning-square", "tent", "campfire", "dam", "download-cloud", "upload-cloud", "sea-level-rise-alt", "tsunami-alt", "collapse-all", "sign-language", "drag", "agriculture", "cannabis", "angry", "happy", "visa", "mastercard", "amexcard", "apple-pay", "discovercard", "paypal", "chrome", "firefox", "ie", "opera", "safari", "bell", "bookmark", "books", "reader", "palette", "glass", "heart", "digging", "gas-pump", "idea-alt", "medal", "smoking", "no-smoking", "share-snapchat", "snapchat", "expand-all", "accessibility", "features", "distance", "coronavirus", "coughing", "cover", "cubes", "hand-heart", "hand-watter", "lab-tests", "mask", "no-coughing", "no-handshake", "no-virus", "procurement", "project", "soap", "stay-home", "teleworking", "testing", "testing-alt", "virus", "viruses", "wash"];
		
	}
	
	isEmpty( val ){
		// if value is undefinded, or 
		// if value is an array and has a 0 length, or
		// if value is a string and is empty after being trimmed
		if( undefined === val ||
			( Array.isArray(val) && ! val.length ) ||
			( typeof val === 'string' && "" === val.trim() )
			){
				return true;
		}
		return false;
	}
	
	// Validates if the $checkmoney parameter is a valid monetary value
	isMoney(checkmoney, pattern = '%.2n') {
		if ( ! this.isEmpty(checkmoney)) {
			checkmoney = typeof val === 'string' ? checkmoney.replace('$', '') : checkmoney;
			checkmoney = typeof val === 'string' ? checkmoney.replace(',', '') : checkmoney;

			return(
				<NumberFormat
					value={checkmoney}
					format={pattern}
					prefix="$"
					isNumericString={true}
					thousandSeparator={true}
					decimalSeparator="."
					allowLeadingZeros={false}
					displayType={'text'}
				/>
			)
		}

		return false;
	}

	getAllFuncs(obj) {
		var props = [];
		do {
			props = props.concat(Object.getOwnPropertyNames(obj));
		} while (obj === Object.getPrototypeOf(obj));
	
		return props.sort().filter(function(e, i, arr) { 
			if (e!==arr[i+1] && typeof obj[e] === 'function'){
				return true;
			} 
			return false;
		 });

	}

	esc_url(u){
		if (!u.match(/^[a-zA-Z]+:\/\//))
		{
			u = 'http://' + u;
		}

		return u;
	}

	caweb_return_address(addr){

		if( this.isEmpty(addr) ){
			return;
		}else if(typeof addr === 'string'){
			addr = addr.split(',');
		}

		addr = addr.filter(w => w !== '');
		
		return addr.join(', ');
	}
	
	caweb_get_google_map_place_link(addr, embed, classList = ''){
		addr = this.caweb_return_address(addr);

		if( this.isEmpty(addr) ){
			return;
		}

		if( embed ){
			return (
			<Fragment>
				<iframe title="Map" src={'https://www.google.com/maps/embed/v1/place?q=' + addr + '&zoom=10&key=' + this.caweb_google_maps_embed_api_key}></iframe>
			</Fragment>);
		}else{
			return (
				<Fragment>
				<a href={"https://www.google.com/maps/place/"+ addr} target="_blank" className={classList}>{addr}</a>
				</Fragment>
			);
		}
	}
	
	caweb_get_icon_span( font, styles = {}, classes = ''){
		var icon_index = font.replace(new RegExp(/%/, 'g'), '');
		var icon_name = this.icon_list[icon_index];

		return (
			<Fragment>
			<span style={styles} className={"ca-gov-icon-" + icon_name + classes}></span>
			</Fragment> 
		);		
	}

	/**
	 *  PHP formatting using https://wordpress.org/support/article/formatting-date-and-time/
	 *  React formatting using https://momentjs.com/docs/#/parsing/string-format/
	 * 
	 */
	caweb_format_date( d, pattern = ''){

		/*
		 Patterns are inputted in PHP format, 
		 and must be replaced with their respective React format.
		*/
		if( ! moment(d).isValid() ){
			return d;
		}
		// Day of Month.
		// This replace is temporary so that it doesn't get overwritten by the weekday replacement
		pattern = pattern.replace(/d/g, 'zz');
		pattern = pattern.replace(/j/g, 'z');

		// Weekday.
		pattern = pattern.replace(/l/g, 'dddd');
		pattern = pattern.replace(/D/g, 'ddd');

		// This replaces the temporary month replacement to what it should be
		pattern = pattern.replace(/zz/g, 'DD');
		pattern = pattern.replace(/z/g, 'D');

		// Month.
		pattern = pattern.replace(/M/g, 'MMM');
		pattern = pattern.replace(/F/g, 'MMMM');
		pattern = pattern.replace(/m/g, 'MM');
		pattern = pattern.replace(/n/g, 'M');

		// Time.
		pattern = pattern.replace(/g/g, 'h');
		pattern = pattern.replace(/i/g, 'mm');
		pattern = pattern.replace(/s/g, 'ss');

		// Year.
		pattern = pattern.replace(/Y/g, 'gggg');
		pattern = pattern.replace(/y/g, 'gg');

		return(<Moment format={pattern} >{d}</Moment>);
	}
	
}

export default CAWeb_Component;

