<?php
/**
 * CAWeb Module
 *
 * @package CAWebModuleExtension
 */

/**
 * CAWeb Module Class extends Divi's ET_Builder_Module Class
 *
 * @see Divi/includes/builder/class-et-builder-element.php
 */
class ET_Builder_CAWeb_Module extends ET_Builder_Module {
	/**
	 * CAWeb Google Maps Embed API Key
	 *
	 * @var string Google Maps Embed API Key.
	 */
	protected $caweb_google_maps_embed_api_key = 'AIzaSyCtq3i8ME-Ab_slI2D8te0Uh2PuAQVqZuE';

	/**
	 * Module Information
	 *
	 * @var array Information about this specific module.
	 */
	protected $module_credits = array(
		'module_uri' => 'https://caweb.cdt.ca.gov/',
		'author'     => 'CAWeb Publishing',
		'author_uri' => '',
	);

	/**
	 * Wrap module's rendered output with proper module wrapper. Ensuring module has consistent
	 * wrapper output which compatible with module attribute and background insertion.
	 *
	 * @since 3.1
	 *
	 * @param string $output      Module's rendered output.
	 * @param string $render_slug Slug of module that is used for rendering output.
	 *
	 * @return string
	 */
	//phpcs:disable
	protected function _render_module_wrapper( $output = '', $render_slug = '' ) {
	//phpcs:enable
		return $output;
	}

	/**
	 * Available module text sizes
	 *
	 * @param  array $exclude Text sizes to exclude.
	 * @return array
	 */
	public function caweb_get_text_sizes( $exclude = array() ) {
		$default_text_size = array(
			'p'  => 'Paragraph',
			'h1' => 'H1',
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
		);

		foreach ( $exclude as $i => $size ) {
			if ( isset( $default_text_size[ $size ] ) ) {
				unset( $default_text_size[ $size ] );
			}
		}

		return $default_text_size;
	}

	/**
	 * Returns address in CSV format
	 *
	 * @param  array|string $addr Address to format.
	 * @return string
	 */
	public function caweb_get_address( $addr ) {
		if ( empty( $addr ) ) {
			return;
		} elseif ( is_string( $addr ) ) {
			$addr = preg_split( '/,/', $addr );
		}

		$addr = array_filter( $addr );
		$addr = implode( ', ', $addr );

		return $addr;
	}

	/**
	 * Create a GoogleMap Place Link/Embedded IFrame
	 *
	 * @param  array|string $addr Address to format.
	 * @param  mixed        $embed Whether to create a link or embedded iframe.
	 * @param  mixed        $target The links target, default _blank.
	 * @param  mixed        $class Class for the link.
	 * @return string
	 */
	public function caweb_get_google_map_place_link( $addr, $embed = false, $target = '_blank', $class = '' ) {

		$addr = $this->caweb_get_address( $addr );

		$class = is_array( $class ) ? implode( ' ', $class ) : $class;
		$class = sprintf( ' class="%1$s"', $class );

		if ( $embed ) {
			$map_url = sprintf( 'https://www.google.com/maps/embed/v1/place?q=%1$s&zoom=10&key=%2$s', $addr, $this->caweb_google_maps_embed_api_key );

			return sprintf( '<iframe title="IFrame for Address %1$s" src="%2$s"></iframe>', $addr, $map_url );
		} else {
			return sprintf( '<a href="https://www.google.com/maps/place/%1$s" target="%2$s"%3$s>%1$s</a>', $addr, $target, $class );
		}
	}

	/**
	 * Parse Divi's Font Settings into array
	 *
	 * @param  string $settings Divi Font Setting.
	 * @return array
	 */
	public function parse_divi_font_settings( $settings ) {
		$fields = array( 'font', 'weight', 'italic', 'uppercase', 'underline', 'titlecase', 'strikethrough', 'linecolor', 'linestyle' );
		if ( ! is_array( $settings ) ) {
			$settings = explode( '|', $settings );
		}

		return count( $fields ) === count( $settings ) ? array_combine( $fields, $settings ) : $settings;
	}

	/**
	 * Create inline styles from Divi's Font Settings
	 *
	 * @param  string $font_settings Divi Font Setting.
	 * @return string
	 */
	public function create_inline_font_styles( $font_settings ) {
		$styles = '';
		if ( ! empty( $font_settings ) ) {
			$settings = $this->parse_divi_font_settings( $font_settings );

			if ( isset( $settings['font'] ) && ! empty( $settings['font'] ) ) {
				$styles .= ! empty( $settings['font'] ) ? sprintf( 'font-family: %1$s;', $settings['font'] ) : '';
			}
			if ( isset( $settings['weight'] ) && ! empty( $settings['weight'] ) ) {
				$styles .= ! empty( $settings['weight'] ) ? sprintf( 'font-weight: %1$s;', $settings['weight'] ) : '';
			}
			if ( isset( $settings['italic'] ) && ! empty( $settings['italic'] ) ) {
				$styles .= ! empty( $settings['italic'] ) ? 'font-style: italic;' : '';
			}
			if ( isset( $settings['uppercase'] ) && ! empty( $settings['uppercase'] ) ) {
				$styles .= ! empty( $settings['uppercase'] ) ? 'text-transform: uppercase;' : '';
			}
			if ( isset( $settings['titlecase'] ) && ! empty( $settings['titlecase'] ) ) {
				$styles .= ! empty( $settings['titlecase'] ) ? 'text-transform: capitalize;' : '';
			}
			if ( isset( $settings['underline'] ) && ! empty( $settings['underline'] ) ) {
				$styles .= ! empty( $settings['underline'] ) ? 'text-decoration: underline;' : '';
			}
			if ( isset( $settings['strikethrough'] ) && ! empty( $settings['strikethrough'] ) ) {
				$styles .= ! empty( $settings['strikethrough'] ) ? 'text-decoration: line-through;' : '';
			}
			if ( isset( $settings['linecolor'] ) && ! empty( $settings['linecolor'] ) ) {
				$styles .= ! empty( $settings['linecolor'] ) ? sprintf( 'text-decoration-color: %1$s;', $settings['linecolor'] ) : '';
			}
			if ( isset( $settings['linestyle'] ) && ! empty( $settings['linestyle'] ) ) {
				$styles .= ! empty( $settings['linestyle'] ) ? sprintf( 'text-decoration-style: %1$s;', $settings['linestyle'] ) : '';
			}
		}

		return $styles;
	}

	/**
	 * Validates if is a valid monetary value and return it if true.
	 *
	 * @param  mixed  $checkmoney Monetary value to validate.
	 * @param  string $pattern Monetary value pattern.
	 * @return boolean|string
	 */
	public function caweb_is_money( $checkmoney, $pattern = '%.2n' ) {
		if ( ! empty( $checkmoney ) ) {
			$checkmoney = is_string( $checkmoney ) ? str_replace( array( '$', ',' ), '', $checkmoney ) : $checkmoney;
			return sprintf( '$%1$.2f', number_format( $checkmoney, 2 ) );
		}

		return false;
	}

	/**
	 * Processes modules icon selection
	 *
	 * @param  string $icon Selected icon.
	 * @return string
	 */
	private function process_icon( $icon ) {
		if ( empty( $icon ) ) {
			return;
		}

		$icon = preg_replace( '/%%/', '', $icon );

		// get appropriate icon.
		$tmp = caweb_get_icon_list( -1, '', true );

		$icon = isset( $tmp[ $icon ] ) ? 'ca-gov-icon-' . $tmp[ $icon ] : '';
		return $icon;
	}

	/**
	 * Create icon span
	 *
	 * @param  string $icon Icon to render.
	 * @param  string $classes Classes for the span.
	 * @param  string $styles Styles for the span.
	 * @return string
	 */
	public function caweb_get_icon_span( $icon, $classes = '', $styles = '' ) {
		$icon = $this->process_icon( $icon );

		if ( empty( $icon ) ) {
			return;
		}

		$classes = is_array( $classes ) ? implode( ' ', $classes ) : $classes;
		$classes = ! empty( $classes ) ? " $classes" : '';

		$styles = is_array( $styles ) ? implode( ';', $styles ) : $styles;
		$styles = ! empty( $styles ) ? " style=\"$styles\"" : '';

		return sprintf( '<span class="%1$s%2$s"%3$s></span>', $icon, $classes, $styles );
	}

	/**
	 * Return posts based on parameters
	 *
	 * @param  array  $cats Categories associated with posts requested.
	 * @param  array  $tags Tags associated with posts requested.
	 * @param  int    $post_amount Amount of posts to return.
	 * @param  string $orderby Order posts by specific meta, default post_date.
	 * @param  string $order Order posts ascending/descending order, default DESC.
	 * @return array
	 */
	public function caweb_return_posts( $cats = array(), $tags = array(), $post_amount = -1, $orderby = 'post_date', $order = 'DESC' ) {
		$posts_array = array();

		$req_array = array();

		$args['category'] = ( ! empty( $cats ) ? ( is_array( $cats ) ? implode( ',', $cats ) : $cats ) : array() );

		$args += array(
			'posts_per_page'    => $post_amount,
			'orderby'           => $orderby,
			'order'             => $order,
			'post_type'         => 'post',
			'post_status'       => 'publish',
			'suppress_filters'  => true,
		);

		$posts_array = get_posts( $args );

		if ( ! empty( $tags ) ) {
			foreach ( $posts_array as $p => $i ) {
				// return posts tags.
				$tag_ids = wp_get_post_tags( $i->ID, array( 'fields' => 'ids' ) );

				if ( empty( $tag_ids ) ) {
					unset( $posts_array[ $p ] );
				} else {
					// iterate through the tags.
					$tags    = ! is_array( $tags ) ? explode( ',', $tags ) : $tags;
					$has_tag = false;
					foreach ( $tag_ids as $k ) {
						if ( in_array( (string)$k, $tags, true ) ) {
							$has_tag = true;
						}
					}
					if ( ! $has_tag ) {
						unset( $posts_array[ $p ] );
					}
				}
			}
		}
		return $posts_array;
	}

	/**
	 * CAWeb Get Post Thumbnail
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
	 *
	 * @param  int|WP_Post $post Post ID or WP_Post object. Default is global $post.
	 * @param  string      $size Image size to use. Accepts any valid image size, or an array of width and height values in pixels (in that order), default thumbnail.
	 * @param  string      $attr Query string or array of attributes.
	 * @param  array       $pixel_size Allows for overwriting thumbnail size.
	 * @return void
	 */
	public function caweb_get_the_post_thumbnail( $post = null, $size = 'thumbnail', $attr = '', $pixel_size = array() ) {
		if ( is_array( $size ) ) {
			if ( empty( $pixel_size ) && 2 === count( $size ) ) {
				$pixel_size = $size;
			}
			$size = 'thumbnail';
		}
		$thumbnail = get_the_post_thumbnail( $post, $size, $attr );

		// if there is no thumbnail return.
		if ( empty( $thumbnail ) ) {
			return;
		}
		// theres is a thumbnail, and the pixel sizes is empty or has more than 2 elements
		// return the thumbnail untouched.
		if ( empty( $pixel_size ) || 2 !== count( $pixel_size ) ) {
			return $thumbnail;
		}

		// remove the current width and height size attributes and
		// srcset attribute.
		$thumbnail = preg_replace( array( '/(width|height)=\"\d*\"\s/', '/(width|height):[\s\d\w]*;?/', '/(srcset)=\".*\"/' ), '', $thumbnail );

		$style = '';
		// remove style attribute.
		if ( preg_match( '/style=\"([\w\d\s]*)\"/', $thumbnail, $matches ) ) {
			$style     = $matches[1];
			$thumbnail = preg_replace( array( '/style=\"([\w\d\s]*)\"/' ), '', $thumbnail );
		}

		$new_img = sprintf( '<img style="width:%1$spx;height:%2$spx;%3$s" ', $pixel_size[0], $pixel_size[1], $style );

		$thumbnail = preg_replace( '/<img /', $new_img, $thumbnail );

		return $thumbnail;
	}

	/**
	 * Return an excerpt from the content of requested length.
	 *
	 * @param  string $con Content to retrieve to excerpt from.
	 * @param  int    $excerpt_length Desired excerpt character length.
	 * @param  int    $p Post ID, default -1.
	 * @return string
	 */
	public function caweb_get_excerpt( $con, $excerpt_length, $p = -1 ) {
		if ( empty( $con ) ) {
			return $con;
		}

		// Regex pattern to find the end of strong, p, span, a and br tags.
		$pattern = '/&lt;\/strong&gt;|&lt;\/p&gt;|&lt;\/span&gt;|&lt;\/a&gt;|&lt;br[\s]+\/&gt;/';

		// Split content by regex pattern.
		$con_array = preg_split( $pattern, htmlentities( wp_strip_all_tags( $con, '<strong><p><span><a><br>' ) ), -1 );
		// Store regex matches.
		preg_match_all( $pattern, htmlentities( wp_strip_all_tags( $con, '<strong><p><span><a><br>' ) ), $match_array, PREG_OFFSET_CAPTURE );

		$excerpt    = array();
		$word_count = 0;

		// Iterate thru content splits.
		foreach ( $con_array as $i => $line ) {
			// strip all tags in the line and return every word.
			$cleaned = explode( ' ', wp_strip_all_tags( html_entity_decode( $line ) ) );

			// if there was a match for the line save it and append.
			$matching_end  = '';
			$matching_end  = isset( $match_array[0][ $i ][0] ) && ! empty( $match_array[0][ $i ][0] ) ? $match_array[0][ $i ][0] : '<br>';
			$excerpt[ $i ] = $line . $matching_end;

			if ( ! empty( $line ) ) {
				$word_count += count( $cleaned );
			}

			if ( $excerpt_length < $word_count ) {
				do {
					$word_count--;

					if ( ! isset( $cleaned[ count( $cleaned ) - 1 ] ) ) {
						break;
					}

					$line = substr( $line, 0, strrpos( $line, ' ' ) );

					$cleaned = array_filter( explode( ' ', wp_strip_all_tags( html_entity_decode( $line ) ) ) );

					if ( $excerpt_length >= $word_count ) {
						$line .= '...';
					}

					$excerpt[ $i ] = $line . $matching_end;
				} while ( $excerpt_length < $word_count );

				break;
			}
		}

		$x = new DOMDocument();
		$x->loadHTML( sprintf( '<div class="post-%1$s-excerpt">%2$s</div>', $p, trim( implode( '', $excerpt ) ) ) );
		$element = $x->getElementById( "post-$p-excerpt" );

		return html_entity_decode( $x->saveHTML( $element ) );
	}
}


