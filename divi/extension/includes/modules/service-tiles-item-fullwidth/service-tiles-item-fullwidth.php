<?php
/**
 * CAWeb Service Tiles Item (Fullwidth)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Service Tiles Item (Fullwidth)
 */
class CAWeb_Module_Fullwidth_Service_Tiles_Item extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_fullwidth_service_tiles_item';
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
		$this->name      = esc_html__( 'FullWidth Service Tile Item', 'et_builder' );
		$this->fullwidth = true;

		$this->type                     = 'child';
		$this->child_title_var          = 'item_title';
		$this->child_title_fallback_var = 'item_title';

		$this->fields_defaults             = array(
			'tile_link' => array( 'off', 'add_default_setting' ),
		);
		$this->advanced_setting_title_text = esc_html__( 'New Service Tile', 'et_builder' );
		$this->settings_text               = esc_html__( 'Service Tile Settings', 'et_builder' );
		$this->main_css_element            = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'header' => esc_html__( 'Header', 'et_builder' ),
					'style'  => esc_html__( 'Style', 'et_builder' ),
					'body'   => esc_html__( 'Body', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(),
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
		$general_fields = array(
			'item_title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the title for the tile', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'tile_size' => array(
				'label'             => esc_html__( 'Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'quarter' => esc_html__( 'Quarter', 'et_builder' ),
					'half'    => esc_html__( 'Half', 'et_builder' ),
					'full'    => esc_html__( 'Full', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose the size of the tile', 'et_builder' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'style',
			),
			'item_image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Background Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Background', 'et_builder' ),
				'description'        => esc_html__( 'If defined, this image will be used as the background for this tile. To remove a background image, simply delete the URL from the settings field.', 'et_builder' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'body',
			),
			'tile_link' => array(
				'label'           => esc_html__( 'Link to URL', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'         => array( 'tile_url', 'content' ),
				'default'         => 'off',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'tile_url' => array(
				'label'           => esc_html__( 'URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the url for the tile.', 'et_builder' ),
				'show_if'         => array( 'tile_link' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'content' => array(
				'label'           => esc_html__( 'Tile Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'description'     => esc_html__( 'Define the text for the tile content', 'et_builder' ),
				'depends_show_if' => 'off',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		$design_fields = array();

		$advanced_fields = array(
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'toggle_slug'     => 'classes',
				'option_class'    => 'et_pb_custom_css_regular',
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
	 * @return void
	 */
	public function render( $unprocessed_props, $content = null, $render_slug ) {
		global $caweb_tile_count, $caweb_tiles;

		$this->add_classname( 'service-tile-panel' );

		$caweb_tiles[] = array(
			'item_title'   => $this->props['item_title'],
			'item_image'   => $this->props['item_image'],
			'tile_size'    => $this->props['tile_size'],
			'tile_url'     => ! empty( $this->props['tile_url'] ) ? esc_url( $this->props['tile_url'] ) : '',
			'tile_link'    => $this->props['tile_link'],
			'module_id'    => $this->module_id(),
			'module_class' => sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) ),
			'content'      => $this->content,
		);

		$caweb_tile_count++;
	}
}
new CAWeb_Module_Fullwidth_Service_Tiles_Item();


