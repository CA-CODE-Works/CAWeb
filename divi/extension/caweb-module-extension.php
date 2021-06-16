<?php
/**
 * CAWeb Divi Module Extension Plugin
 *
 * @source https://github.com/elegantthemes/create-divi-extension
 * @link https://www.elegantthemes.com/documentation/developers/create-divi-extension/
 * @link https://www.elegantthemes.com/documentation/developers/how-to-create-a-divi-builder-module/
 * @link https://www.elegantthemes.com/documentation/developers/how-to-create-a-custom-field-for-a-divi-builder-module/
 *
 * @package CAWebModuleExtension
 *
 * @wordpress-plugin
 * Plugin Name: CAWeb Divi Module Extension
 * Plugin URI:
 * Description: CAWeb Custom Divi Modules
 * Version:     1.0.0
 * Author:      Danny Guzman
 * Author URI:
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: caweb-divi-module-extension
 * Domain Path: /languages
 *
 * CAWeb Divi Module Extension is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * CAWeb Divi Module Extension is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CAWeb Divi Module Extension. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

define( 'CAWEB_EXT_DIR', str_replace( '\\', '/', __DIR__ . '/' ) );
define( 'CAWEB_EXT_URL', site_url( preg_replace( '/(.*)\/wp-content/', '/wp-content', CAWEB_EXT_DIR ) ) );

if ( ! function_exists( 'caweb_initialize_extension' ) ) :
	/**
	 * Creates the CAWeb Module extension's main class instance.
	 *
	 * @category add_action( 'divi_extensions_init', 'caweb_initialize_extension' );
	 * @since 1.0.0
	 */
	function caweb_initialize_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-caweb-module-extension.php';
	}
	add_action( 'divi_extensions_init', 'caweb_initialize_extension' );


	/**
	 * Module Shortcode Output Fix
	 *
	 * Fixes various Divi Module Outputs
	 *
	 * @category add_filter( 'et_module_shortcode_output', 'caweb_module_shortcode_output_fix', 10, 3 );
	 * @param  string             $output Divi Module shortcode output.
	 * @param  string             $render_slug Divi Module render_slug.
	 * @param  ET_Builder_Element $module The Divi Builder Element Object.
	 * @return string
	 */
	function caweb_module_shortcode_output_fix( $output, $render_slug, $module ) {
		$module = (array) $module;

		switch ( $render_slug ) {
			// Fix Divi Image Output.
			case 'et_pb_image':
			case 'et_pb_fullwidth_image':
				$src   = $module['props']['src'];
				$alt   = '';
				$title = '';

				// if no alt assigned get the alt text from the Media Library.
				if ( preg_match( '/alt=""/', $output ) && ! empty( $src ) ) {
					$alt    = caweb_get_attachment_post_meta( $src, '_wp_attachment_image_alt' );
					$output = preg_replace( '/alt=""/', sprintf( 'alt="%1$s"', $alt ), $output );
				}

				// if no title assigned get the title text from the Media Library.
				if ( preg_match( '/title=""/', $output ) && ! empty( $src ) ) {
					// phpcs:disable -- Slow meta query ok.
					$query = array(
						'post_type'  => 'attachment',
						'fields'     => 'ids',
						'meta_query' => array(
							array(
								'key'     => '_wp_attached_file',
								'value'   => basename( $src ),
								'compare' => 'LIKE',
							),
						),
					);
					// phpcs:enable

					$attachment    = get_posts( $query );
					$attachment_id = ! empty( $attachment ) ? $attachment[0] : '';

					if ( ! empty( $attachment_id ) ) {
						$title  = get_the_title( $attachment_id );
						$output = preg_replace( '/title=""/', sprintf( 'title="%1$s"', $title ), $output );
					}
				}

				// if there is an anchor tag present.
				if ( preg_match( '/<a href/', $output ) && ( ! empty( $alt ) || ! empty( $title ) ) ) {
					$anchor = ! empty( $alt ) ? $alt : $title;
					$output = preg_replace( '/<a href/', sprintf( '<a title="%1$s" href', $anchor ), $output );
				}

				break;
		}

		return $output;
	}
	add_filter( 'et_module_shortcode_output', 'caweb_module_shortcode_output_fix', 10, 3 );
endif;
