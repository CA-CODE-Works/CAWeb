<?php
/**
 * CAWeb Theme Helper Functions
 *
 * @package CAWeb
 */

/**
 * Returns the Site Wide Template Version Setting
 *
 * @since 1.5.4 Template Version 5 has been deprecated and all customers moved to 5.5.
 * @return int
 */
function caweb_template_version() {
	$version       = get_option( 'ca_site_version', CAWEB_MINIMUM_SUPPORTED_TEMPLATE_VERSION );
	$theme_version = wp_get_theme()->get( 'Version' );

	if ( '1.5.4' <= $theme_version && '5.5' > $version ) {
		return 5.5;
	}

	return $version;
}

/**
 * Returns all CAWeb State Template Versions
 *
 * @param boolean $include_beta Include beta versions.
 * @return array
 */
function caweb_template_versions( $include_beta = true ) {
	$tmp               = CAWEB_SUPPORTED_TEMPLATE_VERSIONS;
	$template_versions = array();

	if ( $include_beta ) {
		$tmp = array_merge( $tmp, CAWEB_BETA_TEMPLATE_VERSIONS );
	}

	sort( $tmp );

	foreach ( $tmp as $t ) {
		$template_versions[ "$t" ] = "Version $t";
	}

	return $template_versions;
}

/**
 * Returns whether or not the Site Wide Template Version is a Beta
 *
 * @return boolean
 */
function caweb_is_beta_version() {
	return in_array( caweb_template_version(), CAWEB_BETA_TEMPLATE_VERSIONS, true );
}

/**
 * Returns array of CAWeb Menu Theme Locations
 *
 * @return array
 */
function caweb_nav_menu_theme_locations() {
	return array(
		'header-menu' => 'Header Menu',
		'footer-menu' => 'Footer Menu',
	);
}

/**
 * Load Minified Version of a file
 *
 * @param  string $f File to load.
 * @param  mixed  $ext Extension of file, default css.
 *
 * @return string
 */
function caweb_get_min_file( $f, $ext = 'css' ) {
	/* if a minified version exists load it */
	if ( file_exists( CAWEB_ABSPATH . str_replace( ".$ext", ".min.$ext", $f ) ) ) {
		return CAWEB_URI . str_replace( ".$ext", ".min.$ext", $f );
	} else {
		return CAWEB_URI . $f;
	}
}

/**
 * CA.gov Template Colors
 *
 * @return array
 */
function caweb_template_colors() {
	$color['oceanside'] = array(
		'highlight' => '#FDB81E',
		'primary'   => '#046B99',
		'standout'  => '#323A45',
		's1'        => '#E1F2F7',
	);

	$color['orangecounty'] = array(
		'highlight' => '#FBAD23',
		'primary'   => '#A15801',
		'standout'  => '#483723',
		's1'        => '#F1EDE4',
	);

	$color['pasorobles']   = array(
		'highlight' => '#FBAD23',
		'primary'   => '#9A0000',
		'standout'  => '#313131',
		's1'        => '#F5F5F5',
	);
	$color['santabarbara'] = array(
		'highlight' => '#FF9B53',
		'primary'   => '#60617D',
		'standout'  => '#664945',
		's1'        => '#FFEBD7',
	);
	$color['sierra']       = array(
		'highlight' => '#FBAD23',
		'primary'   => '#447766',
		'standout'  => '#194949',
		's1'        => '#EFFAF6',
	);
	$color['mono']         = array(
		'highlight' => '#FFCE2B',
		'primary'   => '#545351',
		'standout'  => '#191919',
		's1'        => '#F4F3EF',
	);
	$color['trinity']      = array(
		'highlight' => '#C19E73',
		'primary'   => '#446A7C',
		'standout'  => '#21272A',
		's1'        => '#F9F8F8',
	);
	$color['eureka']       = array(
		'highlight' => '#D9B295',
		'primary'   => '#3E4B4D',
		'standout'  => '#21272A',
		's1'        => '#F9F8F8',
	);
	$color['sacramento']   = array(
		'highlight' => '#7BB0DA',
		'primary'   => '#153554',
		'standout'  => '#730000',
		's1'        => '#E1ECF7',
	);

	return $color;
}

/**
 * Retrieve CAWeb Color Schemes
 *
 * @param  int    $version State Template Version.
 * @param  string $field Whether to return filename, displayname or both.
 * @param  string $color Retrieve information on a specific colorscheme.
 *
 * @return array
 */
function caweb_color_schemes( $version = 0, $field = '', $color = '' ) {
	$css_dir = sprintf( '%1$s/assets/css/cagov', CAWEB_ABSPATH );
	$pattern = '/.*\/([\w\s]*)\.css/';

	$schemes = array();

	/*
	Get glob of colorschemes based on version,
	if no version provided return all colors from all versions
	*/
	switch ( $version ) {
		case 5.5:
			$tmp = glob( sprintf( '%1$s/version5.5/colorscheme/*.css', $css_dir ) );
			break;
		default:
			$tmp = glob( sprintf( '%1$s/version%2$s*/colorscheme/*.css', $css_dir, CAWEB_MINIMUM_SUPPORTED_TEMPLATE_VERSION ) );
			break;
	}

	/*
	Iterate thru each colorscheme
	*/
	foreach ( $tmp as $css_file ) {
		$filename    = preg_replace( $pattern, '\1', $css_file );
		$displayname = ucwords( strtolower( $filename ) );

		$schemekey = strtolower( str_replace( ' ', '', $displayname ) );

		switch ( $field ) {
			case 'filename':
				$schemes[ $schemekey ] = $filename;

				break;
			case 'displayname':
				$schemes[ $schemekey ] = $displayname;

				break;
			default:
				$schemes[ $schemekey ] = array(
					'filename'    => $filename,
					'displayname' => $displayname,
				);

				break;

		}

		if ( ! empty( $color ) && $color === $schemekey && isset( $schemes[ $color ] ) ) {
			return $schemes[ $color ];
		}
	}

	ksort( $schemes );

	return $schemes;
}

/**
 * CAWeb Font Sizes
 *
 * @param  array $exclude font sizes to exclude.
 * @param  mixed $values return values.
 *
 * @return array
 */
function caweb_font_sizes( $exclude = array(), $values = false ) {
	$sizes = array(
		8  => '8pt',
		9  => '9pt',
		10 => '10pt',
		11 => '11pt',
		12 => '12pt',
		13 => '13pt',
		14 => '14pt',
		15 => '15pt',
		16 => '16pt',
		17 => '17pt',
		18 => '18pt',
		19 => '19pt',
		20 => '20pt',
		21 => '21pt',
		22 => '22pt',
		23 => '23pt',
		24 => '24pt',
		25 => '25pt',
		26 => '26pt',
		27 => '27pt',
		28 => '28pt',
		29 => '29pt',
		30 => '30pt',
		31 => '31pt',
		32 => '32pt',
		33 => '33pt',
		34 => '34pt',
		35 => '35pt',
		36 => '36pt',
	);

	foreach ( $exclude as $i => $size ) {
		if ( isset( $sizes[ $size ] ) ) {
			unset( $sizes[ $size ] );
		}
	}

	return $values ? array_values( $sizes ) : $sizes;
}

/**
 * Return CAWeb Attachment Meta Field for given urls
 *
 * @param  string|array $image_url Attachment URL. Can be string or an array of URLS.
 * @param  string       $meta_key Meta Field to return. If empty all fields are returned.
 *
 * @return string|array
 */
function caweb_get_attachment_post_meta( $image_url, $meta_key = '' ) {

	if ( empty( $image_url ) ) {
		return '';
	} elseif ( is_string( $image_url ) || is_array( $image_url ) ) {
		$query = array(
			'post_type'  => 'attachment',
			'fields'     => 'ids',
		);

		$image_urls = is_string( $image_url ) ? array( $image_url ) : $image_url;
		$imgs       = array();

		foreach ( $image_urls as $i => $img ) {
			// phpcs:disable -- Slow meta query ok.
			$query['meta_query'] = array(
				array(
					'key'     => '_wp_attached_file',
					'value'   => basename( $img ),
					'compare' => 'LIKE',
				),
			);
			// phpcs:enable
			$ids = get_posts( $query );

			if ( ! empty( $ids ) ) {
				$imgs[] = get_post_meta( $ids[0], $meta_key, true );
			}
		}

		if ( empty( $imgs ) ) {
			return 0;
		} else {
			return 1 < count( $imgs ) ? $imgs : $imgs[0];
		}
	}
}

if ( ! function_exists( 'caweb_get_shortcode_from_content' ) ) {
	/**
	 * Retrieve specific shortcode tag from given content
	 *
	 * @param  string $con Post Content.
	 * @param  string $tag Shortcode tag to retrieve.
	 * @param  bool   $all_matches Whether to retrieve all matches or just the first.
	 *
	 * @return array
	 */
	function caweb_get_shortcode_from_content( $con = '', $tag = '', $all_matches = false ) {
		if ( empty( $con ) || empty( $tag ) ) {
			return array();
		}
		$results = array();
		$objects = array();

		$tag = is_array( $tag ) ? implode( '|', $tag ) : $tag;

		/* Get Shortcode Tags from Con and save it to $results */
		$pattern = sprintf( '/\[(%1$s)[\d\s\w\S]+?\[\/\1\]|\[(%1$s)[\d\s\w\S]+? \/\]/', $tag );
		preg_match_all( $pattern, $con, $results );
		/* if there are no matches return an empty array */
		if ( empty( $results ) ) {
			return array();
		}
		/* if there are results save only the matches */
		$matches = $results[0];

		/* iterate thru each match */
		foreach ( $matches as $m => $match ) {
			$obj  = array();
			$attr = array();

			/*
			Matching tag can either be self-closing or not.
			Non self-closing matching tags are results[1].
			Self-closing matching tags are results[2].
			If non self-closing tag is empty assume self-closing
			*/
			$matching_tag = ! empty( $results[1][ $m ] ) ? $results[1][ $m ] : $results[2][ $m ];

			/*
			If the shortcode is a self closing tag, then it contains content in between its Shortcode Tags
			Get content from shortcode
			*/
			preg_match( sprintf( '/"\][\s\S]*\[\/(%1$s)/', $matching_tag ), $match, $obj['content'] );

			if ( ! empty( $obj['content'] ) ) {
				/* substring the attributes, removing the content from the match */
				$match          = substr( $match, 1, strpos( $match, $obj['content'][0] ) );
				$obj['content'] = substr( $obj['content'][0], 2, strlen( $obj['content'][0] ) - strlen( $matching_tag ) - 4 );
				/* If the shortcode is not a self closing tag, then it only contains one Shortcode Tag */
			} else {
				$obj['content'] = '';
			}

			/* Get Attributes from Shortcode */
			preg_match_all( '/\w*="[\w\s\d$:(),@?\'=+%!#\/\.\[\]\{\}-]*/', $match, $attr );
			foreach ( $attr[0] as $a ) {
				preg_match( '/\w*/', $a, $key );
				$obj[ $key[0] ] = urldecode( substr( $a, strlen( $key[0] ) + 2 ) );
			}

			$objects[] = (object) $obj;
		}

		if ( $all_matches ) {
			return $objects;
		}

		return ! empty( $objects ) ? $objects[0] : array();
	}
}

/**
 * Returns all child nav_menu_items under a specific parent
 *
 * @source https://wpsmith.net/2011/how-to-get-all-the-children-of-a-specific-nav-menu-item/
 * @param  int   $parent_id The parent nav_menu_item ID.
 * @param  array $nav_menu_items Array of Nav Menu Objects.
 * @param  bool  $depth Gives all children or direct children only.
 *
 * @return array
 */
function caweb_get_nav_menu_item_children( $parent_id, $nav_menu_items, $depth = true ) {
	$nav_menu_item_list = array();

	foreach ( (array) $nav_menu_items as $nav_menu_item ) {
		if ( (int) $nav_menu_item->menu_item_parent === (int) $parent_id ) {
			$nav_menu_item_list[] = $nav_menu_item;
			if ( $depth ) {
				$children = caweb_get_nav_menu_item_children( $nav_menu_item->ID, $nav_menu_items );
				if ( $children ) {
					$nav_menu_item_list = array_merge( $nav_menu_item_list, $children );
				}
			}
		}
	}

	return $nav_menu_item_list;
}

/**
 * Get User Profile Color
 *
 * @return string
 */
function caweb_get_user_color() {
	global $_wp_admin_css_colors;

	$admin_color = get_user_option( 'admin_color' );

	return $_wp_admin_css_colors[ $admin_color ];
}


/**
 * CAWeb Allowed HTML for wp_kses
 *
 * @link https://codex.wordpress.org/Function_Reference/wp_kses

 * @param  array   $exclude HTML tags to exclude.
 * @param  boolean $form Whether or not to include form fields.
 * @return array
 */
function caweb_allowed_html( $exclude = array(), $form = false ) {
	$attr = array(
		'id'    => array(),
		'class' => array(),
		'style' => array(),
		'role'  => array(),
	);

	$anchors = array(
		'href'   => array(),
		'title'  => array(),
		'target' => array(),
	);

	$imgs = array(
		'src' => array(),
		'alt' => array(),
	);

	$aria = array(
		'aria-label'      => array(),
		'aria-labelledby' => array(),
		'aria-expanded'   => array(),
		'aria-haspopup'   => array(),
	);

	// Some of these are used by Bootstrap 4 Toggle Plugin.
	// https://gitbrent.github.io/bootstrap4-toggle/#api.
	$data = array(
		'data-toggle'   => array(),
		'data-target'   => array(),
		'data-on'       => array(),
		'data-off'      => array(),
		'data-onstyle'  => array(),
		'data-offstyle' => array(),
		'data-size'     => array(),
		'data-style'    => array(),
		'data-width'    => array(),
		'data-height'   => array(),
	);

	$tags = array(
		'div'    => array_merge( $attr, $aria, $data ),
		'nav'    => array_merge( $attr, $aria, $data ),
		'header' => array_merge( $attr, $aria, $data ),
		'footer' => array_merge( $attr, $aria, $data ),
		'p'      => $attr,
		'br'     => array(),
		'span'   => $attr,
		'a'      => array_merge( $attr, $anchors, $aria, $data ),
		'button' => array_merge( $attr, $aria, $data ),
		'img'    => array_merge( $attr, $imgs ),
		'strong' => $attr,
		'bold'   => $attr,
		'i'      => $attr,
		'h1'     => $attr,
		'h2'     => $attr,
		'h3'     => $attr,
		'h4'     => $attr,
		'h5'     => $attr,
		'h6'     => $attr,
		'ol'     => $attr,
		'ul'     => $attr,
		'li'     => $attr,
		'style'  => array(),
		'script' => array(),
	);

	// Whether to include form fields or not.
	if ( $form ) {
		$form_attrs = array(
			'action'     => array(),
			'method'     => array(),
			'enctype'    => array(),
			'novalidate' => array(),
		);

		$input_attrs = array(
			'for'      => array(),
			'type'     => array(),
			'name'     => array(),
			'value'    => array(),
			'title'    => array(),
			'checked'  => array(),
			'selected' => array(),
			'required' => array(),
			'pattern'  => array(),
		);

		$form_tags = array(
			'form'     => array_merge( $attr, $form_attrs ),
			'label'    => array_merge( $attr, $input_attrs, $aria, $data ),
			'input'    => array_merge( $attr, $input_attrs, $aria, $data ),
			'li'       => array_merge( $attr, $input_attrs, $aria, $data ),
			'select'   => array_merge( $attr, $input_attrs, $aria, $data ),
			'option'   => array_merge( $attr, $input_attrs, $aria, $data ),
		);

		$tags = array_merge( $tags, $form_tags );
	}

	add_filter( 'safe_style_css', 'caweb_safe_style_css' );

	return array_diff_key( $tags, array_flip( $exclude ) );
}

/**
 * Safe Style CSS
 *
 * @see https://developer.wordpress.org/reference/functions/safecss_filter_attr/
 *
 * @param  array $styles A string of CSS rules.
 * @return array
 */
function caweb_safe_style_css( $styles ) {
	$styles[] = 'list-style-position';

	return $styles;
}
