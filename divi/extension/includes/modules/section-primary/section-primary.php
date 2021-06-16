<?php
/**
 * CAWeb Section Primary Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Section Primary Module Class (Standard)
 */
class CAWeb_Module_Section_Primary extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_section_primary';
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
		$this->name             = esc_html__( 'Section - Primary', 'et_builder' );
		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'header' => esc_html__( 'Header', 'et_builder' ),
					'body'   => esc_html__( 'Body', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'header' => esc_html__( 'Header', 'et_builder' ),
					'style'  => esc_html__( 'Style', 'et_builder' ),
					'text'   => array(
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
			'section_heading' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the title for the section.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'featured_image_button' => array(
				'label'           => esc_html__( 'Featured Image', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'         => 'on',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'left_right_button' => array(
				'label'           => esc_html__( 'Image Position', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'Left', 'et_builder' ),
					'on'  => esc_html__( 'Right', 'et_builder' ),
				),
				'show_if'         => array( 'featured_image_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'section_image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Gallery Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Background', 'et_builder' ),
				'description'        => esc_html__( 'If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field.', 'et_builder' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'body',
				'show_if'            => array( 'featured_image_button' => 'on' ),
			),
			'slide_image_button' => array(
				'label'           => esc_html__( 'Fade Image from Left', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
				'show_if'         => array( 'featured_image_button' => 'on' ),
			),
			'show_more_button' => array(
				'label'           => esc_html__( 'More Information Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'         => 'off',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'section_link' => array(
				'label'           => esc_html__( 'Link URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'URL destination for the button.', 'et_builder' ),
				'show_if'         => array( 'show_more_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can create the content that will be used within the module.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		$design_fields = array(
			'heading_text_color' => array(
				'label'             => esc_html__( 'Heading Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom heading color for the title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'heading_align' => array(
				'label'           => esc_html__( 'Heading Alignment', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'left'   => esc_html__( 'Left', 'et_builder' ),
					'center' => esc_html__( 'Center', 'et_builder' ),
					'right'  => esc_html__( 'Right', 'et_builder' ),
				),
				'show_if'         => array( 'featured_image_button' => 'off' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'header',
			),
			'heading_size' => array(
				'label'             => esc_html__( 'Header Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h2',
				'description'       => esc_html__( 'Here you can choose the size for the panel header', 'et_builder' ),
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
	 * Renders the Module on the frontend
	 *
	 * @param  mixed $unprocessed_props Module Props before processing.
	 * @param  mixed $content Module Content.
	 * @param  mixed $render_slug Module Slug Name.
	 * @return string
	 */
	public function render( $unprocessed_props, $content = null, $render_slug ) {
		$featured_image_button    = $this->props['featured_image_button'];
		$show_more_button         = $this->props['show_more_button'];
		$section_background_color = $this->props['section_background_color'];

		$content = $this->content;

		$this->add_classname( 'section' );
		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$section_bg_color = ! empty( $section_background_color ) ?
			sprintf( ' style="background: %1$s;"', $section_background_color ) : '';

		$header         = $this->renderHeader( $featured_image_button );
		$display_button = 'on' === $show_more_button ? $this->renderMoreButton() : '';

		if ( 'on' === $featured_image_button ) {
			$body = sprintf(
				'%1$s<div class="col-md-15">%2$s%3$s%4$s</div>',
				$this->renderFeaturedImage(),
				$header,
				$content,
				$display_button
			);

		} else {
			$body = sprintf( '<div>%1$s%2$s%3$s</div>', $header, $content, $display_button );
		}

		$output = sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $this->module_id(), $class, $section_bg_color, $body );

		return $output;
	}

	/**
	 * Renders the Header
	 *
	 * @param  mixed $featured_image_button Whether to show featured image or not.
	 * @return string
	 */
	public function renderHeader( $featured_image_button = 'on' ) {
		$heading_size       = $this->props['heading_size'];
		$heading_text_color = $this->props['heading_text_color'];
		$heading_align      = $this->props['heading_align'];
		$section_heading    = $this->props['section_heading'];

		$heading_style = ! empty( $heading_text_color ) ? sprintf( ' style="color: %1$s;" ', $heading_text_color ) : '';
		$heading_class = 'off' === $featured_image_button ? sprintf( ' class="text-%1$s"', $heading_align ) : '';
		return "<$heading_size$heading_style$heading_class>$section_heading</$heading_size>";
	}

	/**
	 * Renders the More Button
	 *
	 * @return string
	 */
	public function renderMoreButton() {
		$section_link    = $this->props['section_link'];
		$section_heading = $this->props['section_heading'];

		if ( empty( $section_link ) ) {
			return;
		}

		return sprintf( '<div><a href="%1$s" class="btn btn-default" target="_blank">More Information<span class="sr-only">More information about %2$s</span></a></div>', esc_url( $section_link ), $section_heading );
	}

	/**
	 * Renders the Featured Image
	 *
	 * @return string
	 */
	public function renderFeaturedImage() {
		$slide_image_button = $this->props['slide_image_button'];
		$image_pos          = $this->props['left_right_button'];
		$section_image      = $this->props['section_image'];

		$class  = 'on' === $slide_image_button ? ' animate-fadeInLeft' : '';
		$class .= 'on' === $image_pos ? ' pull-right pr-0' : ' pull-left pl-0';

		$alt_text      = caweb_get_attachment_post_meta( $section_image, '_wp_attachment_image_alt' );
		$section_image = sprintf( '<img src="%1$s" class="img-responsive w-100" alt="%2$s" />', $section_image, $alt_text );

		return sprintf( '<div class="col-md-4 col-md-offset-0%1$s">%2$s</div>', $class, $section_image );
	}

}
new CAWeb_Module_Section_Primary();
