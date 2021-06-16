<?php
/**
 * CAWeb Icon Font Library
 *
 * @package CAWeb
 */

add_filter( 'et_pb_font_icon_symbols', 'caweb_et_pb_font_icon_symbols' );

/**
 * Merger of Divi and CAWeb Icon Font Library
 * This filter is applied by Divi
 *
 * @see Divi includes/builder/functions.php Line 405
 * @version 4.0.7
 * @param  array $divi_symbols Array of Divi Symbols.
 *
 * @return array
 */
function caweb_et_pb_font_icon_symbols( $divi_symbols = array() ) {
	$symbols = array_values( caweb_get_icon_list() );

	return $symbols;
}

/**
 * This array is required and needs to be updated whenever new icons are added,
 * this ensures that icons appear in the list in the correct order
 *
 * @return array
 */
function caweb_icons() {
	return array( 'logo', 'home', 'menu', 'apps', 'search', 'chat', 'capitol', 'state', 'phone', 'email', 'contact-us', 'calendar', 'bear', 'chat-bubble', 'info-bubble', 'share-button', 'share-facebook', 'share-email', 'share-flickr', 'share-twitter', 'share-linkedin', 'share-googleplus', 'share-instagram', 'share-pinterest', 'share-vimeo', 'share-youtube', 'law-enforcement', 'justice-legal', 'at-sign', 'attachment', 'zipped-file', 'powerpoint', 'excel', 'word', 'pdf', 'share', 'facebook', 'linkedin', 'youtube', 'twitter', 'pinterest', 'vimeo', 'instagram', 'flickr', 'google-plus', 'microsoft', 'apple', 'android', 'computer', 'tablet', 'smartphone', 'roadways', 'travel-car', 'travel-air', 'truck-delivery', 'construction', 'bar-chart', 'pie-chart', 'graph', 'server', 'download', 'cloud-download', 'cloud-upload', 'shield', 'fire', 'binoculars', 'compass', 'sos', 'shopping-cart', 'video-camera', 'camera', 'green', 'loud-speaker', 'audio', 'print', 'medical', 'zoom-out', 'zoom-in', 'important', 'chat-bubbles', 'call', 'people', 'person', 'user-id', 'payment-card', 'skip-backwards', 'play', 'pause', 'skip-forward', 'mail', 'image', 'house', 'gear', 'tool', 'time', 'cal', 'check-list', 'document', 'clipboard', 'page', 'read-book', 'cc-copyright', 'ca-capitol', 'ca-state', 'favorite', 'rss', 'road-pin', 'online-services', 'link', 'magnify-glass', 'key', 'lock', 'info', 'arrow-up', 'arrow-down', 'arrow-left', 'arrow-right', 'carousel-prev', 'carousel-next', 'arrow-prev', 'arrow-next', 'menu-toggle-closed', 'menu-toggle-open', 'carousel-play', 'carousel-pause', 'search-right', 'graduate', 'briefcase', 'images', 'gears', 'tools', 'pencil', 'pencil-edit', 'science', 'film', 'table', 'flowchart', 'building', 'searching', 'wallet', 'tags', 'currency', 'idea', 'lightbulb', 'calculator', 'drive', 'globe', 'hourglass', 'mic', 'volume', 'music', 'folder', 'grid', 'archive', 'contacts', 'book', 'drawer', 'map', 'pushpin', 'location', 'quote-fill', 'question-fill', 'warning-triangle', 'warning-fill', 'check-fill', 'close-fill', 'plus-fill', 'minus-fill', 'caret-fill-right', 'caret-fill-left', 'caret-fill-down', 'caret-fill-up', 'caret-fill-two-right', 'caret-fill-two-left', 'caret-fill-two-down', 'caret-fill-two-up', 'arrow-fill-right', 'arrow-fill-left', 'arrow-fill-up', 'arrow-fill-down', 'arrow-fill-left-down', 'arrow-fill-right-down', 'arrow-fill-left-up', 'arrow-fill-right-up', 'triangle-line-right', 'triangle-line-left', 'triangle-line-up', 'triangle-line-down', 'caret-line-two-right', 'caret-line-two-left', 'caret-line-two-up', 'caret-line-two-down', 'caret-line-right', 'caret-line-left', 'caret-line-up', 'caret-line-down', 'important-line', 'info-line', 'check-line', 'question-line', 'close-line', 'plus-line', 'minus-line', 'question', 'minus-mark', 'plus-mark', 'collapse', 'expand', 'check-mark', 'close-mark', 'triangle-right', 'triangle-left', 'triangle-up', 'triangle-down', 'caret-two-right', 'caret-two-left', 'caret-two-down', 'caret-two-up', 'caret-right', 'caret-left', 'caret-up', 'caret-down', 'filter', 'caweb', 'arrow_up', 'arrow_down', 'arrow_left', 'arrow_right', 'arrow_left-up', 'arrow_right-up', 'arrow_right-down', 'arrow_left-down', 'arrow-up-down', 'arrow_up-down_alt', 'arrow_left-right_alt', 'arrow_left-right', 'arrow_expand_alt2', 'arrow_expand_alt', 'arrow_condense', 'arrow_expand', 'arrow_move', 'arrow_back', 'icon_zoom-out_alt', 'icon_zoom-in_alt', 'icon_box-empty', 'icon_box-selected', 'icon_box-checked', 'icon_circle-empty', 'icon_circle-slelected', 'icon_stop_alt2', 'icon_stop', 'icon_pause_alt2', 'icon_pause', 'icon_menu', 'icon_menu-square_alt2', 'icon_menu-circle_alt2', 'icon_ul', 'icon_ol', 'icon_adjust-horiz', 'icon_adjust-vert', 'icon_document_alt', 'icon_documents_alt', 'icon_pencil-edit_alt', 'icon_folder-alt', 'icon_folder-open_alt', 'icon_folder-add_alt', 'icon_error-circle_alt', 'icon_error-triangle_alt', 'icon_comment_alt', 'icon_chat_alt', 'icon_vol-mute_alt', 'icon_volume-low_alt', 'icon_volume-high_alt', 'icon_quotations', 'icon_quotations_alt2', 'icon_clock_alt', 'icon_lock_alt', 'icon_lock-open_alt', 'icon_key_alt', 'icon_cloud_alt', 'icon_cloud-upload_alt', 'icon_cloud-download_alt', 'icon_lightbulb_alt', 'icon_house_alt', 'icon_laptop', 'icon_camera_alt', 'icon_mail_alt', 'icon_cone_alt', 'icon_ribbon_alt', 'icon_bag_alt', 'icon_creditcard', 'icon_cart_alt', 'icon_paperclip', 'icon_tag_alt', 'icon_tags_alt', 'icon_trash_alt', 'icon_cursor_alt', 'icon_mic_alt', 'icon_compass_alt', 'icon_pin_alt', 'icon_pushpin_alt', 'icon_map_alt', 'icon_drawer_alt', 'icon_toolbox_alt', 'icon_book_alt', 'icon_calendar', 'icon_contacts_alt', 'icon_headphones', 'icon_refresh', 'icon_link_alt', 'icon_link', 'icon_loading', 'icon_blocked', 'icon_archive_alt', 'icon_heart_alt', 'icon_printer', 'icon_calulator', 'icon_building', 'icon_floppy', 'icon_drive', 'icon_search', 'icon_id', 'icon_id-2', 'icon_puzzle', 'icon_like', 'icon_dislike', 'icon_mug', 'icon_currency', 'icon_wallet', 'icon_pens', 'icon_easel', 'icon_flowchart', 'icon_datareport', 'icon_briefcase', 'icon_shield', 'icon_percent', 'icon_globe', 'icon_target', 'icon_balance', 'icon_star_alt', 'icon_star-half_alt', 'icon_star-half', 'icon_cog', 'icon_cogs', 'arrow_condense_alt', 'arrow_expand_alt3', 'icon_zoom-out', 'icon_zoom-in', 'icon_stop_alt', 'icon_menu-square_alt', 'icon_menu-circle_alt', 'icon_document', 'icon_documents', 'icon_pencil_alt', 'icon_folder', 'icon_folder-add', 'icon_folder_upload', 'icon_folder_download', 'icon_error-circle', 'icon_comment', 'icon_chat', 'icon_vol-mute', 'icon_volume-low', 'icon_clock', 'icon_lock', 'icon_lock-open', 'icon_key', 'icon_cloud', 'icon_cloud-upload', 'icon_cloud-download', 'icon_gift', 'icon_house', 'icon_mail', 'icon_cone', 'icon_ribbon', 'icon_bag', 'icon_cart', 'icon_tag', 'icon_trash', 'icon_cursor', 'icon_compass', 'icon_heart', 'icon_pause_alt', 'icon_phone', 'icon_upload', 'icon_download', 'icon_rook', 'icon_floppy_alt', 'icon_id_alt', 'icon_puzzle_alt', 'icon_like_alt', 'icon_dislike_alt', 'icon_mug_alt', 'icon_pens_alt', 'icon_briefcase_alt', 'icon_shield_alt', 'icon_percent_alt', 'icon_globe_alt', 'icon_clipboard', 'social_googleplus', 'social_tumblr', 'social_tumbleupon', 'social_wordpress', 'social_dribbble', 'social_deviantart', 'social_myspace', 'social_skype', 'social_picassa', 'social_googledrive', 'social_flickr', 'social_blogger', 'social_spotify', 'social_delicious', 'social_facebook_circle', 'social_twitter_circle', 'social_pinterest_circle', 'social_googleplus_circle', 'social_tumblr_circle', 'social_stumbleupon_circle', 'social_wordpress_circle', 'social_instagram_circle', 'social_dribbble_circle', 'social_vimeo_circle', 'social_linkedin_circle', 'social_rss_circle', 'social_deviantart_circle', 'social_share_circle', 'social_myspace_circle', 'social_skype_circle', 'social_youtube_circle', 'social_picassa_circle', 'social_googledrive_alt2', 'social_flickr_circle', 'social_blogger_circle', 'social_spotify_circle', 'social_delicious_circle', 'social_tumblr_square', 'social_stumbleupon_square', 'social_wordpress_square', 'social_instagram_square', 'social_dribbble_square', 'social_rss_square', 'social_deviantart_square', 'social_share_square', 'social_myspace_square', 'social_skype_square', 'social_picassa_square', 'social_googledrive_square', 'social_flickr_square', 'social_blogger_square', 'social_spotify_square', 'social_delicious_square', 'toggle', 'tabs', 'subscribe', 'slider', 'sidebar', 'share2', 'pricing-table', 'portfolio', 'number-counter', 'header', 'filtered-portfolio', 'divider', 'cta', 'countdown', 'circle-counter', 'blurb', 'bar-counters', 'audio2', 'accordion', 'icon_gift_alt', 'code', 'hours', 'hours-security', 'albums', 'brain', 'certificate', 'certificate-check', 'charge', 'charge-cycle', 'charge-units', 'city', 'clock', 'cloud-gear', 'cloud-services', 'cloud-sync', 'ear', 'ear-slash', 'eye', 'eye-slash', 'file', 'file-audio', 'file-certificate', 'file-check', 'file-code', 'file-csv', 'file-download', 'file-excel', 'file-export', 'file-import', 'file-invoice', 'file-medical', 'file-medical-alt', 'file-pdf', 'file-powerpoint', 'file-prescription', 'file-upload', 'file-video', 'file-word', 'file-zip', 'filter-solid', 'fingerprint', 'fingerprint-check', 'hand', 'hand-money', 'handshake', 'institute', 'medical-bubble', 'medical-care', 'medical-case', 'medical-clinic', 'medical-cross', 'medical-doctor', 'medical-heart', 'medical-pills', 'mobile', 'pro-services', 'puzzle', 'puzzle-piece', 'recycle', 'responsive', 'responsive-alt', 'security-network', 'security-system', 'shield-check', 'thumb-up', 'trophy', 'users', 'users-alt', 'users-dialog', 'users-interaction', 'video', 'beaker3', 'beaker4', 'beaker5', 'candle-alt', 'cal-bear', 'biohazard', 'malware', 'radiation', 'chemical-hazard', 'danger', 'do-not-sign', 'earthquake', 'quake-house', 'quake-hazard', 'electricity-hazard', 'flood', 'hazard', 'hurricane', 'sea-level-rise', 'severe-weather', 'stop-fire', 'stop-hand', 'tornado', 'tsunami', 'volcano', 'warning-circle', 'warning-square', 'tent', 'campfire', 'dam', 'download-cloud', 'upload-cloud', 'sea-level-rise-alt', 'tsunami-alt', 'collapse-all', 'sign-language', 'drag', 'agriculture', 'cannabis', 'angry', 'happy', 'visa', 'mastercard', 'amexcard', 'apple-pay', 'discovercard', 'paypal', 'chrome', 'firefox', 'ie', 'opera', 'safari', 'bell', 'bookmark', 'books', 'reader', 'palette', 'glass', 'heart', 'digging', 'gas-pump', 'idea-alt', 'medal', 'smoking', 'no-smoking', 'share-snapchat', 'snapchat', 'expand-all', 'accessibility', 'features', 'distance', 'coronavirus', 'coughing', 'cover', 'cubes', 'hand-heart', 'hand-watter', 'lab-tests', 'mask', 'no-coughing', 'no-handshake', 'no-virus', 'procurement', 'project', 'soap', 'stay-home', 'teleworking', 'testing', 'testing-alt', 'virus', 'viruses', 'wash' );
}

/**
 * CA.gov Icon Library List
 *
 * @param  int    $index Icon array index.
 * @param  string $name Icon name.
 * @param  bool   $keys Return Icon key values.
 *
 * @return array
 */
function caweb_get_icon_list( $index = -1, $name = '', $keys = false ) {
	global $wp_filesystem;
	$icons = array_flip( caweb_icons() );

	$svg   = CAWEB_ABSPATH . '/fonts/CaGov.svg';
	$con   = $wp_filesystem->get_contents( $svg );
	$xml   = new SimpleXMLElement( $con );
	$fonts = $xml->defs->font;
	unset( $fonts->glyph[0] );

	foreach ( $fonts->glyph as $g => $glyph ) {
		$glyph_name = (string) $glyph['glyph-name'];
		$code       = (string) $glyph['unicode'];

		if ( isset( $icons[ $glyph_name ] ) ) {
			$icons[ $glyph_name ] = htmlspecialchars( $code );
		}
	}

	if ( 0 < $index ) {
		return isset( array_values( $icons )[ $index ] ) ? array_values( $icons )[ $index ] : $index;
	}

	if ( ! empty( $name ) ) {
		return isset( $icons[ $name ] ) ? $icons[ $name ] : $name;
	}

	if ( $keys ) {
		return array_keys( $icons );
	}

	return $icons;
}
