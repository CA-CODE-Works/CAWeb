<?php
/**
 * CAWeb WP Ajax
 *
 * @see https://codex.wordpress.org/AJAX_in_Plugins
 * @package CAWeb
 */

add_action( 'wp_ajax_caweb_fav_icon_check', 'caweb_fav_icon_checker' );
add_action( 'wp_ajax_caweb_icon_menu', 'caweb_icon_menu_func' );
add_action( 'wp_ajax_nopriv_caweb_icon_menu', 'caweb_icon_menu_func' );
add_action( 'wp_ajax_create_doc_sitemap', 'caweb_doc_create_xml' );

/**
 * Check the Binary Signature of a file, currently only icons
 *
 * @see https://mimesniff.spec.whatwg.org/#image-type-pattern-matching-algorithm Living Standard on Mime Sniffing.
 * @see http://asecuritysite.com/forensics/ico File checker.
 *
 * @return void
 */
function caweb_fav_icon_checker() {
	$url = isset( $_POST['icon_url'] ) ? sanitize_text_field( wp_unslash( $_POST['icon_url'] ) ) : '';

	$arr_context_options = array(
		'sslverify' => false,
	);

	$handle = wp_remote_retrieve_body( wp_remote_get( $url, $arr_context_options ) );
	$handle = rawurlencode( $handle );
	$handle = explode( '%', $handle );
	$handle = array_filter( $handle );
	$handle = array_splice( $handle, 0, 4 );
	$handle = implode( '', $handle );

	// ico = '00000100'.
	// png = '89PNG0D0A1A'.
	$mime_patterns = array( '00000100', '89PNG0D0A1A' );

	if ( in_array( $handle, $mime_patterns, true ) ) {
		print true;
		wp_die(); /* this is required to terminate immediately and return a proper response */
	}

	print false;
	wp_die(); /* this is required to terminate immediately and return a proper response */
}

/**
 * Return CAWeb Icon Menu
 *
 * @return void
 */
function caweb_icon_menu_func() {
	$input  = isset( $_POST['name'] ) ? $_POST['name'] : '';
	$sel    = isset( $_POST['select'] ) ? $_POST['select'] : '';
	$header = isset( $_POST['header'] ) ? $_POST['header'] : false;

	print wp_kses(
		caweb_icon_menu(
			array(
				'select' => $sel,
				'name'   => $input,
				'header' => $header,
			)
		),
		caweb_allowed_html( array(), true )
	);
	wp_die(); // this is required to terminate immediately and return a proper response.

}

/**
 * Create Document SiteMap
 *
 * @return void
 */
function caweb_doc_create_xml() {
	$site_id   = get_current_blog_id();
	$directory = wp_upload_dir();

	if ( 1 === $site_id ) {
		$wp_posts_table = 'wp_posts';
	} else {
		$wp_posts_table = 'wp_' . $site_id . '_posts';
	}

	global $wpdb;

	$count = 0;

	$results = $wpdb->get_results( "SELECT `guid` FROM {$wp_posts_table} WHERE `post_mime_type` IN ('application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')", OBJECT );

	$dom               = new DOMDocument( '1.0', 'UTF-8' );
	$dom->formatOutput = true;

	$urlset = $dom->createElement( 'urlset' );
	$urlset->setAttribute( 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9' );
	$urlset->setAttribute( 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance' );
	$urlset->setAttribute( 'xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd' );

	foreach ( $results as $result ) {
		$url = $dom->createElement( 'url' );
		$urlset->appendChild( $url );
		$url->appendChild( $dom->createElement( 'loc', $result->guid ) );
		$count++;
	}
	$dom->appendChild( $urlset );

	$output = $dom->saveXML();

	$file = $directory['basedir'] . '/pdf-word-sitemap.xml';

	$dom->save( $file );

	$href = $directory['baseurl'] . '/pdf-word-sitemap.xml';

	print "Sitemap created with <strong>$count</strong> entries. File location: <a href=\"$href\" target=\"_blank\">SiteMap</a>";
	wp_die(); // this is required to terminate immediately and return a proper response.
}
