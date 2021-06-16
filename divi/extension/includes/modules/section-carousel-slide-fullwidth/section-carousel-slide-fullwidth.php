<?php
/**
 * CAWeb Section Carousel Slide Module (Fullwidth)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Section Carousel Slide Module Class (Fullwidth)
 */
class CAWeb_Module_Fullwidth_Section_Carousel_Slide extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_fullwidth_section_carousel_slide';
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
		$this->name      = esc_html__( 'Fullwidth Carousel Slide', 'et_builder' );
		$this->fullwidth = true;

		$this->type                     = 'child';
		$this->child_title_var          = 'slide_title';
		$this->child_title_fallback_var = 'slide_title';

		$this->advanced_setting_title_text = esc_html__( 'New Carousel Slide', 'et_builder' );
		$this->settings_text               = esc_html__( 'Carousel Slide Settings', 'et_builder' );

		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'style'  => esc_html__( 'Style', 'et_builder' ),
					'header' => esc_html__( 'Header', 'et_builder' ),
					'body'   => esc_html__( 'Body', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text' => array(
						'title'    => esc_html__( 'Text', 'et_builder' ),
						'priority' => 49,
					),
				),
			),
		);
	}

	/**
	 * Returns an array of all the Module Fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		$general_fields = array(
			'slide_title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the title for the slide', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'slide_image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Background', 'et_builder' ),
				'description'        => esc_html__( 'If defined, this image will be used as the background for this slide. To remove a background image, simply delete the URL from the settings field.', 'et_builder' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'body',
			),
			'slide_show_more_button' => array(
				'label'           => esc_html__( 'Add More Link', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'         => array( 'slide_url' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'slide_url' => array(
				'label'           => esc_html__( 'Link URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the URL for the link.', 'et_builder' ),
				'depends_show_if' => 'on',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'slide_desc' => array(
				'label'           => esc_html__( 'Description', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the text for the slide content', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		$design_fields = array(
			'slide_title_size' => array(
				'label'             => esc_html__( 'Slide Title Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h2',
				'description'       => esc_html__( 'Here you can choose the heading size for the slide title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
		);

		$advanced_fields = array(
			'slide_alt_text' => array(
				'label'           => esc_html__( 'Image Alt Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Override the existing alternate text for the slide image.', 'et_builder' ),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'attributes',
			),
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'visibility',
			),
		);

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
		$slide_image            = $this->props['slide_image'];
		$slide_title            = $this->props['slide_title'];
		$slide_title_size       = $this->props['slide_title_size'];
		$slide_desc             = $this->props['slide_desc'];
		$slide_url              = $this->props['slide_url'];
		$slide_alt_text         = $this->props['slide_alt_text'];
		$slide_show_more_button = $this->props['slide_show_more_button'];

		global $et_pb_slider_item_num;
		global $et_pb_ca_section_carousel_style;

		$et_pb_slider_item_num++;

		$this->add_classname( 'item' );

		$slide_image_alt = sprintf( ' alt="%1$s" ', empty( $slide_alt_text ) ? caweb_get_attachment_post_meta( $slide_image, '_wp_attachment_image_alt' ) : $slide_alt_text );

		if ( 'media' === $et_pb_ca_section_carousel_style ) {
			$class       = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );
			$slide_title = ! empty( $slide_title ) ? $slide_title : $this->name;

			$button = 'on' === $slide_show_more_button && ! empty( $slide_url ) ? sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( $slide_url ), $slide_title ) : '';
			$button = ! empty( $button ) ? sprintf( '<div class="details text-center%1$s">%2$s</div>', empty( $slide_desc ) ? ' mt-3' : '', $button ) : '';

			$desc = ! empty( $slide_desc ) ? sprintf( '<div class="details mt-3">%1$s</div>', $slide_desc ) : '';

			$slide = ! empty( $slide_image ) ? sprintf( '<div class="preview-image"><img class="h-100" src="%1$s"%2$s/></div>%3$s', $slide_image, $slide_image_alt, $desc ) : '';

			$output = sprintf( '<div%1$s>%2$s%3$s</div>', $class, $slide, $button );
		} else {
			$this->add_classname( $et_pb_ca_section_carousel_style );
			$this->add_classname( 'backdrop' );
			$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

			$display_button = 'on' === $slide_show_more_button && ! empty( $slide_url ) && ! empty( $slide_title ) ? sprintf( '<br><a class="btn btn-primary" href="%1$s" target="_blank"><strong>More Information<span class="sr-only">More information about %2$s</span></strong></a>', esc_url( $slide_url ), $slide_title ) : '';

			$slide_title = ! empty( $slide_title ) ? "<$slide_title_size>$slide_title</$slide_title_size>" : '';

			$hide = empty( $slide_title ) && empty( $slide_desc ) && empty( $display_button ) ? ' hidden' : '';

			$content = sprintf( '<div class="content%1$s">%2$s%3$s%4$s</div>', 'content_fit' === $et_pb_ca_section_carousel_style ? $hide : '', $slide_title, $slide_desc, $display_button );

			$content_container = sprintf( '<div class="content-container%1$s">%2$s</div>', 'image_fit' === $et_pb_ca_section_carousel_style ? $hide : '', $content );

			$content_fit = 'content_fit' === $et_pb_ca_section_carousel_style ? sprintf( ' style="background-image: url(%1$s);"', $slide_image ) : '';

			$image_fit = 'image_fit' === $et_pb_ca_section_carousel_style ? sprintf( '<img src="%1$s"%2$s/>', $slide_image, $slide_image_alt ) : '';

			$output = sprintf( '<div%1$s%2$s>%3$s%4$s</div>', $class, $content_fit, $image_fit, $content_container );
		}

		return $output;
	}
}
new CAWeb_Module_Fullwidth_Section_Carousel_Slide();


