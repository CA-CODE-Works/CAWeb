<?php
/**
 * CAWeb Section Carousel Module Class (Fullwidth)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Section Carousel Module Class (Fullwidth)
 */
class CAWeb_Module_Fullwidth_Section_Carousel extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_fullwidth_section_carousel';
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
		$this->name      = esc_html__( 'Fullwidth Section - Carousel', 'et_builder' );
		$this->fullwidth = true;

		$this->child_slug      = 'et_pb_ca_fullwidth_section_carousel_slide';
		$this->child_item_text = esc_html__( 'Slide', 'et_builder' );

		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'style' => esc_html__( 'Style', 'et_builder' ),
					'panel' => esc_html__( 'Panel', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'style' => esc_html__( 'Style', 'et_builder' ),
					'text'  => array(
						'title'    => esc_html__( 'Text', 'et_builder' ),
						'priority' => 49,
					),
				),
			),
		);

		// Custom handler: Output JS for editor preview in page footer.
		add_action( 'wp_footer', array( $this, 'carousel_fix' ), 20 );
	}

	/**
	 * Returns an array of all the Module Fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		$general_fields = array(
			'carousel_style' => array(
				'label'           => esc_html__( 'Style', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'content_fit' => esc_html__( 'Content Fit', 'et_builder' ),
					'image_fit'   => esc_html__( 'Image Fit', 'et_builder' ),
					'media'       => esc_html__( 'Media', 'et_builder' ),
				),
				'default'         => 'content_fit',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'slide_amount' => array(
				'label'           => esc_html__( 'Viewable Display Amount', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the amount of slides to display at one time.', 'et_builder' ),
				'default'         => 4,
				'show_if'         => array( 'carousel_style' => 'media' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'in_panel' => array(
				'label'           => esc_html__( 'Display in Panel', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'carousel_style' => 'media' ),
				'description'     => 'Choose whether to display this carousel inside of a panel',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'panel_title' => array(
				'label'           => esc_html__( 'Heading', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a Heading Title.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_layout' => array(
				'label'             => esc_html__( 'Style', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'default'            => esc_html__( 'Default', 'et_builder' ),
					'standout'           => esc_html__( 'Standout', 'et_builder' ),
					'standout highlight' => esc_html__( 'Standout Highlight', 'et_builder' ),
					'overstated'         => esc_html__( 'Overstated', 'et_builder' ),
					'understated'        => esc_html__( 'Understated', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose the style of panel to display', 'et_builder' ),
				'show_if'           => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'default'           => 'default',
				'tab_slug'          => 'general',
				'toggle_slug'       => 'panel',
			),
			'panel_show_button' => array(
				'label'           => esc_html__( 'Read More Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'description'     => esc_html__( 'Here you can select to display a button.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_text' => array(
				'label'           => esc_html__( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the Text for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_link' => array(
				'label'           => esc_html__( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the URL for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
		);

		$design_fields = array(
			'panel_heading_size' => array(
				'label'             => esc_html__( 'Heading Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h4',
				'show_if'           => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'description'       => esc_html__( 'Here you can choose the heading size for the panel title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'section_background_color' => array(
				'label'             => esc_html__( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom background color for the section.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'style',
			),
		);

		$advanced_fields = array();

		return array_merge( $general_fields, $design_fields, $advanced_fields );
	}

	/**
	 * Before rendering set the global $et_pb_ca_section_carousel_style
	 *
	 * @return void
	 */
	public function before_render() {
		global $et_pb_ca_section_carousel_style;

		$et_pb_ca_section_carousel_style = $this->props['carousel_style'];
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
		$carousel_style = $this->props['carousel_style'];
		$in_panel       = $this->props['in_panel'];
		$panel_layout   = $this->props['panel_layout'];

		$section_bg_color = $this->props['section_background_color'];

		$content = $this->content;

		$section_bg_color = ! empty( $section_bg_color ) ? sprintf( ' style="background: %1$s;" ', $section_bg_color ) : '';

		if ( 'media' === $carousel_style && 'on' === $in_panel ) {
			$this->add_classname( 'panel' );
			$this->add_classname( sprintf( 'panel-%1$s', $panel_layout ) );
			$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

			$output = sprintf( '<div%1$s%2$s>%3$s</div></div>', $this->module_id(), $class, $this->renderPanelCarousel( $section_bg_color ) );
		} else {
			$this->add_classname( $carousel_style );
			$this->add_classname( 'section' );
			$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

			$output = sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $this->module_id(), $class, $section_bg_color, $this->renderCarousel( $carousel_style ) );
		}

		return $output;
	}

	/**
	 * Renders the carousel with the appropriate style
	 *
	 * @param  mixed $style Carousel style, media or content.
	 * @return string
	 */
	public function renderCarousel( $style ) {
		$style = in_array( $style, array( 'media', 'content' ), true ) ? $style : 'content';

		return sprintf( '<div class="carousel carousel-%1$s owl-carousel">%2$s</div>', $style, $this->content );
	}

	/**
	 * Renders the carousel inside a Panel Module with the appropriate bg color.
	 *
	 * @param  mixed $section_bg_color Panel background color.
	 * @return string
	 */
	public function renderPanelCarousel( $section_bg_color ) {
		$panel_title        = $this->props['panel_title'];
		$panel_heading_size = $this->props['panel_heading_size'];
		$panel_show_button  = $this->props['panel_show_button'];
		$panel_button_text  = $this->props['panel_button_text'];
		$panel_button_link  = $this->props['panel_button_link'];

		$display_title  = '';
		$display_button = '';

		if ( 'on' === $panel_show_button && ! empty( $panel_button_link ) ) {
			$display_button = sprintf(
				'<div class="options"><a href="%1$s" class="btn btn-default" target="_blank">%2$s</a></div>',
				esc_url( $panel_button_link ),
				! empty( $panel_button_text ) ? $panel_button_text : 'Read More'
			);
		}

		if ( ! empty( $panel_title ) ) {
			$display_title = sprintf( '<div class="panel-heading"><%1$s>%2$s</%1$s>%3$s</div>', $panel_heading_size, $panel_title, $display_button );
		}

		return sprintf( '%1$s<div class="panel-body"%2$s>%3$s</div>', $display_title, $section_bg_color, $this->renderCarousel( 'media' ) );
	}

	/**
	 * This is a non-standard function, it outputs JS code to change items amount for carousel-media.
	 *
	 * @return void
	 */
	public function carousel_fix() {
		?>
		<script>
		$ = jQuery.noConflict();

		var media_carousels = $('div[class*="<?php print esc_attr( $this->slug ); ?>_"]:not(".item")');

		media_carousels.each(function(index, carousel) {
			if( $(carousel).hasClass('media') || $(carousel).hasClass('panel') ){
				$(carousel).find('.carousel-media').owlCarousel({
					responsive : true,
					responsive: {
						0: {
							items: 1,
							nav: true
						},
						400: {
							items: 1,
							nav: true
						},
						768: {
							items: undefined === carousel.slide_amount ? 4 : carousel.slide_amount,
							nav: true
						},
					},
					margin : 10,
					nav : true,
					dots : false,
					navText: [
						'<span class="ca-gov-icon-arrow-prev" aria-hidden="true"></span>',
						'<span class="ca-gov-icon-arrow-next" aria-hidden="true"></span>'
					],
				});
			}
		})
		</script>
		<?php
	}
}
new CAWeb_Module_Fullwidth_Section_Carousel();
