<?php
/**
 * CAWeb Section Footer Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Section Footer Module Class (Standard)
 */
class CAWeb_Module_Section_Footer extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_section_footer';
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
		$this->name = esc_html__( 'Section - Footer', 'et_builder' );

		$this->child_slug      = 'et_pb_ca_section_footer_group';
		$this->child_item_text = esc_html__( 'Group', 'et_builder' );

		$this->main_css_element = '%%order_class%%.et_pb_ca_section_footer';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(),
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
			'custom_css' => array(
				'toggles' => array(),
			),
		);
	}

	/**
	 * Returns an array of all the Module Fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		$general_fields = array();

		$design_fields = array(
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
		$section_bg_color = $this->props['section_background_color'];

		$content = $this->content;

		$this->add_classname( 'section' );
		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$section_bg_color = ! empty( $section_bg_color ) ? sprintf( ' style="background: %1$s" ', $section_bg_color ) : '';

		$output = sprintf( '<div%1$s%2$s%3$s>%4$s</div>', $this->module_id(), $class, $section_bg_color, $content );

		return $output;
	}
}
new CAWeb_Module_Section_Footer();
