<?php
/**
 * CAWeb TinyMCE
 *
 * @package CAWeb
 */

add_filter( 'tiny_mce_before_init', 'caweb_tiny_mce_before_init', 15 );
add_filter( 'mce_buttons', 'caweb_mce_buttons', 15 );
add_filter( 'mce_buttons_2', 'caweb_mce_buttons_2', 15 );


/**
 * CAWeb TinyMCE Settings
 *
 * @param  array $settings TinyMCE Settings.
 *
 * @return array
 */
function caweb_tiny_mce_settings( $settings = array() ) {
	$styles                               = array();
	$caweb_tiny_mce_init                  = caweb_tiny_mce_before_init( array() );
	$caweb_tiny_mce_init['style_formats'] = json_decode( $caweb_tiny_mce_init['style_formats'] );

	foreach ( $caweb_tiny_mce_init['style_formats'] as $i => $style ) {
		$styles[ str_replace( ' ', '', strtolower( $style->name ) ) ] = $style;
	}

	$admin_css = caweb_get_min_file( '/css/admin.css' );
	$color     = get_option( 'ca_site_color_scheme', 'oceanside' );
	$css       = array(
		includes_url( '/css/dashicons.min.css' ),
		includes_url( '/js/tinymce/skins/wordpress/wp-content.css' ),
	);

	$version     = caweb_template_version();
	$colorscheme = caweb_color_schemes( $version, 'filename', $color );
	$css[]       = caweb_get_min_file( "/css/cagov-v$version-$colorscheme.css" );

	$css[] = $admin_css;

	$defaults_settings = array(
		'media_buttons' => false,
		'quicktags'     => true,
		'tinymce'       => array(
			'content_css'       => implode( ',', $css ),
			'skin'              => 'lightgray',
			'elementpath'       => true,
			'entity_encoding'   => 'raw',
			'entities'          => '38, amp, 60, lt, 62, gt, 34, quot, 39, apos',
			'plugins'           => 'charmap,colorpicker,hr,lists,paste,tabfocus,textcolor,wordpress,wpautoresize,wpemoji,wpgallery,wplink,wptextpattern',
			'toolbar1'          => 'formatselect,bold,italic,underline,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,wp_more,wp_adv',
			'toolbar2'          => 'styleselect,strikethrough,hr,fontselect,fontsizeselect,forecolor,backcolor,pastetext,copy,subscript,superscript,charmap,outdent,indent,undo,redo,wp_help',
			'style_formats'     => $styles,
			'forced_root_block' => '',
			'force_br_newlines' => true,
			'force_p_newlines'  => false,
		),
	);

	return is_array( $settings ) ? array_merge( $defaults_settings, $settings ) : $defaults_settings;
}

/**
 * TinyMCE Editor
 * Attached callback to 'tiny_mce_before_init'
 *
 * @param  array $init_array Array of TinyMCE Settings.
 *
 * @return array
 */
function caweb_tiny_mce_before_init( $init_array ) {
	/*
	Define the style_formats array
	Each array child is a format with it's own settings
	*/
	$style_formats = array(
		array(
			'name'    => 'Featured Narrative',
			'title'   => 'Featured Narrative',
			'block'   => 'aside',
			'classes' => 'featured-narrative',
			'wrapper' => true,
		),
		array(
			'name'     => 'Overstated List',
			'title'    => 'Overstated List',
			'selector' => 'ul',
			'inline'   => 'ul',
			'classes'  => 'list-overstated',
			'wrapper'  => true,
			'styles'   => array(
				'list-style-type' => 'none',
			),
		),
		array(
			'name'     => 'Standout List',
			'title'    => 'Standout List',
			'selector' => 'ul',
			'inline'   => 'ul',
			'classes'  => 'list-standout',
			'wrapper'  => true,
			'styles'   => array(
				'list-style-type' => 'none',
			),
		),
		array(
			'name'     => 'Understated List',
			'title'    => 'Understated List',
			'selector' => 'ul',
			'inline'   => 'ul',
			'classes'  => 'list-understated',
			'wrapper'  => true,
			'styles'   => array(
				'list-style-type' => 'none',
			),
		),
	);

	/* Insert the array, JSON ENCODED, into 'style_formats' */
	$init_array['style_formats'] = wp_json_encode( $style_formats );

	/* TinyMCE default is 11pt but it doesnt appear in the font size box */
	$font_sizes                     = caweb_font_sizes( array(), true );
	$init_array['fontsize_formats'] = implode( ' ', $font_sizes );

	/* TinyMCE Toolbar Start off unhidden */
	$init_array['wordpress_adv_hidden'] = false;

	return $init_array;
}

/**
 * Add hidden MCE Buttons
 * The primary toolbar (always visible)
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
 * @param  array $buttons TinyMCE buttons.
 *
 * @return array
 */
function caweb_mce_buttons( $buttons ) {
	/**
	 * Add in a core button that's disabled by default
	 */
	$tmp = array( 'formatselect', 'bold', 'italic', 'underline' );
	array_splice( $buttons, 0, 3, $tmp );

	return $buttons;
}

/**
 * Add hidden MCE Buttons
 * The advanced toolbar (can be toggled on/off by user)
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_buttons,_mce_buttons_2,_mce_buttons_3,_mce_buttons_4
 * @param  array $buttons TinyMCE buttons.
 *
 * @return array
 */
function caweb_mce_buttons_2( $buttons ) {

	/**
	 * Add in a core button that's disabled by default
	 */

	$tmp = array(
		'styleselect',
		'strikethrough',
		'hr',
		'fontselect',
		'fontsizeselect',
		'forecolor',
		'backcolor',
		'pastetext',
		'copy',
		'subscript',
		'superscript',
	);
	array_splice( $buttons, 0, 5, $tmp );

	return $buttons;
}
