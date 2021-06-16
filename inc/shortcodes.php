<?php
/**
 * CAWeb Shortcodes
 *
 * @link https://codex.wordpress.org/Shortcode_API
 * @package CAWeb
 */

add_shortcode( 'caweb_google_translate', 'caweb_google_translate_func' );
add_shortcode( 'caweb_icon_menu', 'caweb_icon_menu' );

//phpcs:disable
/*
The following shortcodes are not yet ready
add_shortcode('caweb_panel', 'caweb_panel_func');
add_shortcode('caweb_section', 'caweb_section_func');
add_shortcode('caweb_carousel', 'caweb_carousel_func');
add_shortcode('caweb_slide_item', 'caweb_slide_func');
*/
//phpcs:enable

/**
 * Renders CAWeb Google Translator
 *
 * @return html
 */
function caweb_google_translate_func() {
	return '<div id="google_translate_element" class="custom-translate"></div>';
}

/**
 * Renders CAWeb Icon Menu
 *
 * @param  array $atts Array of Settings for the Icon Menu.
 *
 * @return html
 */
function caweb_icon_menu( $atts ) {
	/* Available Props */
	$selected     = isset( $atts['select'] ) ? $atts['select'] : '';
	$input        = isset( $atts['name'] ) ? $atts['name'] : '';
	$header_class = isset( $atts['header_class'] ) ? is_array( $atts['header_class'] ) ? implode( ' ', $atts['header_class'] ) : $atts['header_class'] : '';
	$header_class = ! empty( $header_class ) ? " class=\"$header_class\"" : '';
	$label        = isset( $atts['header'] ) && $atts['header'] ? sprintf( ' <label%1$s%2$s>%3$s</label>', ! empty( $input ) ? " for=\"$input\"" : '', $header_class, $atts['header'] ) : '';

	$header = sprintf( '<div class="caweb-icon-menu-header my-2"><span class="dashicons dashicons-image-rotate align-middle mb-1 resetIcon"></span>%1$s</div>', $label );
	$input  = ! empty( $input ) ? sprintf( '<input type="hidden" id="%1$s" name="%1$s" value="%2$s" >', $input, $selected ) : '';

	$icons     = caweb_get_icon_list( -1, '', true );
	$icon_list = '';
	foreach ( $icons as $i ) {
		$icon_list .= sprintf( '<li class="list-group-item float-left ca-gov-icon-%1$s%2$s" title="%1$s"></li>', $i, $selected === $i ? ' active' : '' );
	}

	return sprintf( '%1$s<ul class="caweb-icon-menu">%2$s%3$s</ul>', $header, $input, $icon_list );

}

/**
 * CAWeb Panel
 *
 * @param  array       $atts Array of Settings for the Panel.
 *                     $atts['layout'] = various panel designs none, default, standout, standout highlight, overstated, and understated.
 *                     $atts['heading'] = Heading for the Panel.
 *                     $atts['heading_icon'] = Panel Icon for the Heading, can be numerical index or name of icon from caweb_get_icon_list().
 *                     $atts['button_url'] = Adds a button url to the Panel Heading
 *                     $atts['button_text'] = 'Read More' button text unless set.
 * @param  string|html $content Content to render inside Panel body.
 *
 * @return html
 */
function caweb_panel_func( $atts, $content = '' ) {
	$layouts = array( 'none', 'default', 'standout', 'standout highlight', 'understated', 'overstated' );

	$id = ( ! empty( $atts['id'] ) ? sprintf( ' id="%1$s" ', str_replace( ' ', '-', $atts['id'] ) ) : '' );

	$style = ( isset( $atts['style'] ) ? sprintf( ' style="%1$s" ', $atts['style'] ) : '' );

	$classes = 'caweb_shortcode panel ';

	if ( ! empty( $atts['class'] ) ) {
		$classes .= sprintf( '%1$s ', $atts['class'] );
	}

	if ( isset( $atts['layout'] ) ) {
		$classes .= sprintf( 'panel-%1$s ', ! in_array( $atts['layout'], $layouts, true ) ? 'none' : $atts['layout'] );
	} elseif ( ! preg_match( '/panel-\w+/', $classes ) ) {
		$classes .= 'panel-none ';
	}

	$class = sprintf( ' class="%1$s" ', $classes );

	$button = '';

	if ( isset( $atts['button_url'] ) ) {
		$button_text = isset( $atts['button_text'] ) ? $atts['button_text'] : 'Read More';
		$button      = sprintf( '<div class="options"><a href="%1$s" class="btn btn-default">%2$s</a></div>', esc_url( $atts['button_url'] ), $button_text );
	}

	$heading_size = ! isset( $atts['layout'] ) || 'none' === $atts['layout'] ? 'h1' : 'h2';
	$heading_icon = isset( $atts['heading_icon'] ) ? "<span class=\"ca-gov-icon-${atts['heading_icon']}\"></span>" : '';
	$heading      = isset( $atts['heading'] ) ?
		sprintf( '<div class="panel-heading"><%1$s>%2$s%3$s</%1$s>%4$s</div>', $heading_size, $heading_icon, $atts['heading'], $button ) : '';

	if ( ! empty( $content ) ) {
		$content = sprintf( '<div class="panel-body"><p>%1$s</p></div>', do_shortcode( $content ) );
	}

	return sprintf( '<div%1$s%2$s%3$s>%4$s%5$s</div>', $id, $class, $style, $heading, $content );
}

/**
 * CAWeb Section
 *
 * @param  array       $atts Array of Settings for the Section.
 *                     $atts['layout'] = section variations none, default, standout, standout highlight, overstated, and understated.
 * @param  string|html $content Content to render inside Section body.
 *
 * @return html
 */
function caweb_section_func( $atts, $content = '' ) {
	$id      = ( isset( $atts['id'] ) ? sprintf( ' id="%1$s" ', $atts['id'] ) : '' );
	$classes = ( isset( $atts['class'] ) ? sprintf( 'section %1$s ', $atts['class'] ) : 'section ' );
	$style   = ( isset( $atts['style'] ) ? sprintf( ' style="%1$s" ', $atts['style'] ) : '' );
	if ( isset( $atts['layout'] ) ) {
		$classes .= sprintf( 'section-%1$s ', $atts['layout'] );
	} else {
		$classes .= 'section-default ';
	}
	$class = sprintf( ' class="%1$s" ', $classes );
	if ( ! empty( $content ) ) {
		$content = sprintf( '<div class="container">%1$s</div>', do_shortcode( $content ) );
	}

	return sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $id, $class, $style, $content );
}

/**
 * CAWeb Carousel
 *
 * @param  array       $atts Array of Settings for the Carousel.
 * @param  string|html $content Content to render inside Carousel body.
 *
 * @return html
 */
function caweb_carousel_func( $atts, $content = '' ) {
	$id      = ( isset( $atts['id'] ) ? sprintf( ' id="%1$s" ', $atts['id'] ) : '' );
	$classes = ( isset( $atts['class'] ) ? sprintf( 'carousel owl-carousel %1$s ', $atts['class'] ) : 'carousel owl-carousel ' );
	$style   = ( isset( $atts['style'] ) ? sprintf( ' style="%1$s" ', $atts['style'] ) : '' );
	if ( isset( $atts['layout'] ) ) {
		$classes .= sprintf( 'carousel-%1$s ', $atts['layout'] );
	} else {
		$classes .= 'carousel-content ';
	}
	$class = sprintf( ' class="%1$s" ', $classes );
	if ( ! empty( $content ) ) {
		$content = do_shortcode( $content );
	}

	return sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $id, $class, $style, $content );
}

/**
 * CAWeb Slide
 *
 * @param  array       $atts Array of Settings for the Slide.
 * @param  string|html $content Content to render inside Slide body.
 *
 * @return html
 */
function caweb_slide_func( $atts, $content = '' ) {
	$id      = ( isset( $atts['id'] ) ? sprintf( ' id="%1$s" ', $atts['id'] ) : '' );
	$classes = ( isset( $atts['class'] ) ? sprintf( 'item %1$s ', $atts['class'] ) : 'item ' );
	$class   = sprintf( ' class="%1$s" ', $classes );
	if ( isset( $atts['layout'] ) ) {
		$slide_style = $atts['layout'];
	} else {
		$slide_style = 'content';
	}
	$src = ( isset( $atts['src'] ) ? $atts['src'] : '' );
	switch ( $slide_style ) {
		case 'content':
			$style = ( isset( $atts['style'] ) ?
			sprintf( ' style="%1$s background-image: url(%2$s);" ', $atts['style'], $src ) :
			sprintf( ' style="background-image: url(%1$s);" ', $src ) );

			break;
	}
	if ( ! empty( $content ) ) {
		$content = sprintf( '<div class="content-container"><div class="content">%1$s</div></div>', do_shortcode( $content ) );
	}

	return sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $id, $class, $style, $content );
}

