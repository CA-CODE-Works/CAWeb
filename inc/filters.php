<?php
/**
 * CAWeb Theme Filters
 *
 * @package CAWeb
 */

/* WP Filters */
add_filter( 'body_class', 'caweb_body_class', 20, 2 );
add_filter( 'post_class', 'caweb_post_class', 15 );
add_filter( 'theme_page_templates', 'caweb_theme_page_templates', 15 );
add_filter( 'script_loader_tag', 'caweb_script_loader_tag', 10, 3 );
add_filter( 'map_meta_cap', 'caweb_add_unfiltered_html_capability', 1, 3 );
/*disable XML-RPC*/
add_filter( 'xmlrpc_enabled', '_return_false' );


/* Plugin Filters */
add_filter( 'wpforms_manage_cap', 'caweb_wpforms_custom_capability' );

/**
 * CAWeb Page Body Class
 *
 * Filters the list of CSS body class names for the current post or page.
 *
 * @link https://developer.wordpress.org/reference/hooks/body_class/
 * @param  array $wp_classes An array of body class names.
 * @param  array $extra_classes An array of additional class names added to the body.
 *
 * @category add_filter( 'body_class','caweb_body_class' , 20 , 2 );
 * @return array
 */
function caweb_body_class( $wp_classes, $extra_classes ) {
	global $post;

	/* List of the classes that need to be removed */
	$blacklist = array(
		'et_secondary_nav_dropdown_animation_fade',
		'et_primary_nav_dropdown_animation_fade',
		'et_fixed_nav',
		'et_show_nav',
		'et_right_sidebar',
	);

	/* List of extra classes that need to be added to the body */
	if ( isset( $post->ID ) ) {
		$divi              = function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( $post->ID ) || strpos( $post->post_content, 'et_pb_section' ) || strpos( $post->post_content, 'et_pb_fullwidth_section' );
		$sidebar_enabled   = ! is_page();
		$special_templates = is_tag() || is_archive() || is_category() || is_author();

		$whitelist = array(
			( $divi && ! $special_templates ? 'divi_builder' : 'non_divi_builder' ),
			( 'on' === get_post_meta( $post->ID, 'ca_custom_post_title_display', true ) ? 'title_displayed' : 'title_not_displayed' ),
			( is_active_sidebar( 'sidebar-1' ) && $sidebar_enabled ? 'sidebar_displayed' : 'sidebar_not_displayed' ),
		);
	}
	$whitelist[] = sprintf( 'v%1$s', caweb_template_version() );
	$whitelist[] = get_option( 'ca_sticky_navigation' ) ? 'sticky_nav' : '';

	/* Remove any classes in the blacklist from the wp_classes */
	$wp_classes = array_diff( $wp_classes, $blacklist );

	/* Return filtered wp class */
	return array_merge( $wp_classes, (array) $whitelist );
}

/**
 * CAWeb Post Body Class
 *
 * @link https://developer.wordpress.org/reference/hooks/post_class/
 * @param  array $classes An array of post class names.
 *
 * @return array
 */
function caweb_post_class( $classes ) {
	global $post;

	if ( has_post_thumbnail( $post->ID ) && '' === get_the_post_thumbnail_url( $post->ID ) ) {
		unset( $classes[ array_search( 'has-post-thumbnail', $classes, true ) ] );
	}

	return $classes;
}

/**
 * CAWeb Theme Page Templates
 * Filters list of page templates for a theme.
 *
 * @link https://developer.wordpress.org/reference/hooks/theme_page_templates/
 * @param  array $templates Array of page templates. Keys are filenames, values are translated names.
 *
 * @return array
 */
function caweb_theme_page_templates( $templates ) {
	/* Remove Divi Blank Page Template */
	unset( $templates['page-template-blank.php'] );

	return $templates;
}

/**
 * CAWeb Script Loader Tags
 * Filters the HTML script tag of an enqueued script.
 *
 * @param  string $tag The <script> tag for the enqueued script.
 * @param  string $handle The script's registered handle.
 * @param  string $src The script's source URL.
 *
 * @return string
 */
function caweb_script_loader_tag( $tag, $handle, $src ) {
	/* Defer some scripts */
	$js_scripts = array( 'cagov-modernizr-script', 'cagov-frontend-script', 'thickbox' );
	/* deferring jQuery breaks other scripts preg_match('/(jquery)[^\/]*\.js/', $tag) */
	if ( in_array( $handle, $js_scripts, true ) ) {
		$tag = str_replace( 'src', 'defer src', $tag );
	}

	return $tag;
}

/**
 * Enable unfiltered_html capability for Administrators.
 *
 * @param  array  $caps    The user's capabilities.
 * @param  string $cap     Capability name.
 * @param  int    $user_id The user ID.
 * @return array  $caps    The user's capabilities, with 'unfiltered_html' potentially added.
 */
function caweb_add_unfiltered_html_capability( $caps, $cap, $user_id ) {
	if ( 'unfiltered_html' === $cap && user_can( $user_id, 'administrator' ) ) {
		$caps = array( 'unfiltered_html' );
	}

	return $caps;
}

/**
 * Change WPForms capability requirement.
 *
 * @link https://wpforms.com/developers/wpforms_manage_cap/
 * @see https://codex.wordpress.org/Roles_and_Capabilities
 * @param string $cap Capability required for user role to access WPForms.
 *
 * @return string
 */
function caweb_wpforms_custom_capability( $cap ) {

	// unfiltered_html by default means Editors and up.
	return is_multisite() ? 'edit_posts' : 'unfiltered_html';
}

