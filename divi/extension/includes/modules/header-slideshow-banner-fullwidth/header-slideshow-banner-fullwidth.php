<?php
/**
 * CAWeb Fullwidth Header Slideshow Banner Module (Fullwidth)
 *
 * @package CAWeb Module Extension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Fullwidth Header Slideshow Banner Module Class (Fullwidth)
 */
class CAWeb_Module_Fullwidth_Header_Slideshow_Banner extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_fullwidth_banner';
	/**
	 * Visual Builder Support
	 *
	 * @var string Whether or not this module supports Divi's Visual Builder.
	 */
	public $vb_support = 'on';

	/**
	 * Module Initialization
	 *
	 * @return void
	 */
	public function init() {
		$this->name      = esc_html__( 'FullWidth Header Slideshow Banner', 'et_builder' );
		$this->fullwidth = true;

		$this->child_slug      = 'et_pb_ca_fullwidth_banner_item';
		$this->child_item_text = esc_html__( 'Slide', 'et_builder' );

		$this->main_css_element = '%%order_class%%.et_pb_slider';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'scroll_bar'  => esc_html__( 'Scroll Bar', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'scroll_bar' => esc_html__( 'Scroll Bar', 'et_builder' ),
					'text'       => array(
						'title'    => esc_html__( 'Text', 'et_builder' ),
						'priority' => 49,
					),
				),
			),
		);

		// Custom handler: Output JS for editor preview in page footer.
		add_action( 'wp_footer', array( $this, 'slideshow_banner_removal' ) );
	}

	/**
	 * Returns an array of all the Module Fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		$general_fields = array(
			'scroll_bar_text' => array(
				'label'           => esc_html__( 'Scroll Bar Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the text for the scroll bar.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'scroll_bar',
			),
		);

		$design_fields = array(
			'font_icon' => array(
				'label'               => esc_html__( 'Scroll Bar Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'default'             => '%%114%%',
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'description'         => esc_html__( 'Here you can select a Heading Icon', 'et_builder' ),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'scroll_bar',
			),
		);

		$advanced_fields = array();

		return array_merge( $general_fields, $design_fields, $advanced_fields );
	}
	/**
	 * Renders the Module on the frontend
	 *
	 * @param  mixed $unprocessed_props Module Props before processing.
	 * @param  mixed $content Module Content.
	 * @param  mixed $render_slug Module Slug Name.
	 * @return string
	 */
	public function render( $unprocessed_props, $content = null, $render_slug ) {
		$scroll_bar_text = $this->props['scroll_bar_text'];
		$scroll_bar_icon = $this->props['font_icon'];

		$this->add_classname( 'header-slideshow-banner' );

		global $et_pb_fullwidth_header_slider_item_num;

		$solo = 1 >= $et_pb_fullwidth_header_slider_item_num ? ' solo' : '';

		$this->add_classname( $solo );
		$this->add_classname( empty( $scroll_bar_text ) ? ' no-explore' : '' );

		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$scroll_bar_icon = $this->caweb_get_icon_span( $scroll_bar_icon );

		$scrollbar = ! empty( $scroll_bar_text ) ?
			sprintf( '<div class="explore-invite"><div class="text-center"><a href="#"><span class="explore-title">%1$s</span>%2$s</a></div></div>', $scroll_bar_text, $scroll_bar_icon ) : '';

		$content = $this->content;

		$output = sprintf( '<div id="et_pb_ca_fullwidth_banner"%1$s><div id="primary-carousel" class="carousel carousel-banner owl-carousel">%2$s</div>%3$s</div>', $class, $content, $scrollbar );

		return $output;
	}

	/**
	 * This is a non-standard function, it outputs JS code that manipulates the module placement to match the State Template.
	 *
	 * @return void
	 */
	public function slideshow_banner_removal() {
		$nonce    = wp_create_nonce( 'caweb_remove_slideshow_banner' );
		$verified = isset( $nonce ) && wp_verify_nonce( sanitize_key( $nonce ), 'caweb_remove_slideshow_banner' );

		global $post;

		if ( null === $post || ( isset( $_GET['et_fb'] ) && '1' === $_GET['et_fb'] ) ) {
			return;
		}

		$nonce    = wp_create_nonce( 'caweb_slideshow_banner_removal' );
		$verified = isset( $nonce ) && wp_verify_nonce( sanitize_key( $nonce ), 'caweb_slideshow_banner_removal' );
		$con      = is_object( $post ) ? $post->post_content : $post['post_content'];
		$module   = ! is_404() && ! empty( $con ) ? caweb_get_shortcode_from_content( $con, 'et_pb_ca_fullwidth_banner' ) : array();

		if ( empty( $module ) ) :
			?>
			<script>
				document.body.classList.remove('primary');
			</script>
		<?php else : ?>
			<script>
				(function( $ ) {
					"use strict";

					var section = $('#et_pb_ca_fullwidth_banner').parent();
					var banner = section.find('#et_pb_ca_fullwidth_banner');

					$(document).ready(function () {
						$('#main-content').prepend(banner);

						if( ! section.children().length )
							$(section).remove();

							// calculate top of screen on next repaint
						window.setTimeout(function () {
							// fill up the remaining heaight of this device
							if( 'auto' !== '<?php print esc_attr( $this->props['height'] ); ?>' )
								banner.css({ 'height': '<?php print esc_attr( $this->props['height'] ); ?>' });
							if( 'auto' !== '<?php print esc_attr( $this->props['min_height'] ); ?>' )
								banner.css({ 'min-height': '<?php print esc_attr( $this->props['min_height'] ); ?>' });
							if( 'none' !== '<?php print esc_attr( $this->props['max_height'] ); ?>' )
								banner.css({ 'max-height': '<?php print esc_attr( $this->props['max_height'] ); ?>' });
						}, 250);
					});

				})(jQuery);				
			</script>
			<?php
		endif;
	}

}
new CAWeb_Module_Fullwidth_Header_Slideshow_Banner();
?>
