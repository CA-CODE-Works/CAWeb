<?php
/**
 * CAWeb Panel Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Panel Module Class (Standard)
 */
class CAWeb_Module_Panel extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_panel';
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
		$this->name             = esc_html__( 'Panel', 'et_builder' );
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
					'header' => esc_html__( 'Header', 'et_builder' ),
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
			'panel_layout' => array(
				'label'             => esc_html__( 'Style', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'none'               => esc_html__( 'None', 'et_builder' ),
					'default'            => esc_html__( 'Default', 'et_builder' ),
					'standout'           => esc_html__( 'Standout', 'et_builder' ),
					'standout highlight' => esc_html__( 'Standout Highlight', 'et_builder' ),
					'overstated'         => esc_html__( 'Overstated', 'et_builder' ),
					'understated'        => esc_html__( 'Understated', 'et_builder' ),
				),
				'default'           => 'default',
				'description'       => esc_html__( 'Here you can choose the style of panel to display', 'et_builder' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'style',
			),
			'title' => array(
				'label'           => esc_html__( 'Heading', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a Heading Title.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'show_button' => array(
				'label'           => esc_html__( 'Read More Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'     => esc_html__( 'Here you can select to display a button.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'button_link' => array(
				'label'           => esc_html__( 'Button Link', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the URL for the button.', 'et_builder' ),
				'show_if'         => array( 'show_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'button_text' => array(
				'label'           => esc_html__( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the text for the button. (Max characters: 16)', 'et_builder' ),
				'show_if'         => array( 'show_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
				'attributes'      => array( 'maxlength' => 16 ),
			),
			'button_target' => array(
				'label'           => esc_html__( 'Open in New Tab', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'on',
				'description'     => esc_html__( 'Open link in a new tab.', 'et_builder' ),
				'show_if'         => array( 'show_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can create the content that will be used within the module.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'admin_label',
			),
		);

		$design_fields = array(
			'heading_align' => array(
				'label'             => esc_html__( 'Heading Alignment', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'left'   => esc_html__( 'Left', 'et_builder' ),
					'center' => esc_html__( 'Center', 'et_builder' ),
					'right'  => esc_html__( 'Right', 'et_builder' ),
				),
				'default'           => 'left',
				'description'       => esc_html__( 'Here you can choose the alignment for the panel heading', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'heading_size' => array(
				'label'             => esc_html__( 'Heading Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h4',
				'description'       => esc_html__( 'Here you can choose the heading size for the panel title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'heading_text_color' => array(
				'label'             => esc_html__( 'Heading Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom heading color for the title.', 'et_builder' ),
				'show_if'           => array( 'panel_layout' => 'none' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'use_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'description'     => 'Choose whether to display an icon before the Heading',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'header',
			),
			'font_icon' => array(
				'label'               => esc_html__( 'Heading Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'default'             => '%-1%',
				'show_if'             => array( 'use_icon' => 'on' ),
				'description'         => esc_html__( 'Here you can select a Heading Icon', 'et_builder' ),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'header',
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
		$panel_layout       = $this->props['panel_layout'];
		$use_icon           = $this->props['use_icon'];
		$icon               = $this->props['font_icon'];
		$title              = $this->props['title'];
		$heading_align      = $this->props['heading_align'];
		$heading_text_color = $this->props['heading_text_color'];
		$show_button        = $this->props['show_button'];
		$button_link        = $this->props['button_link'];
		$button_text        = $this->props['button_text'];
		$button_target      = $this->props['button_target'];

		$this->add_classname( 'panel' );
		$this->add_classname( sprintf( 'panel-%1$s', $panel_layout ) );

		if ( 'none' === $panel_layout ) {
			$this->add_classname( 'overflow-visible' );
			$this->add_classname( 'border-0' );
		}

		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$content = $this->content;

		$display_title = '';

		if ( ! empty( $title ) ) {
			$heading_size = $this->props['heading_size'];

			$display_options = '';
			$display_icon    = 'on' === $use_icon ? $this->caweb_get_icon_span( $icon, 'pr-1', 'vertical-align:sub;' ) : '';

			if ( 'on' === $show_button && ! empty( $button_link ) ) {
				$button_text     = empty( $button_text ) ? 'Read More' : $button_text;
				$button_link     = esc_url( $button_link );
				$button_target   = 'on' === $button_target ? '_blank' : '_self';
				$option_classes  = 'right' === $heading_align ? ' pl-2' : '';
				$option_classes .= ! empty( $display_icon ) ? ' mt-2' : '';

				$display_options = sprintf(
					'<div class="options%1$s"><a href="%2$s" class="btn btn-default" target="%3$s">%4$s</a></div>',
					$option_classes,
					$button_link,
					$button_target,
					$button_text
				);
			}

			$heading_text_color = 'none' === $panel_layout && ! empty( $heading_text_color ) ?
				sprintf( ' style="color: %1$s;"', $heading_text_color ) : '';

			$display_title = sprintf(
				'<div class="panel-heading text-%2$s"><%1$s class="pb-0 pt-2" %3$s>%4$s%5$s</%1$s>%6$s</div>',
				$heading_size,
				$heading_align,
				$heading_text_color,
				$display_icon,
				$title,
				$display_options
			);
		}

		$output = sprintf(
			'<div%1$s%2$s>%3$s<div class="panel-body">%4$s</div></div> <!-- .et_pb_panel -->',
			$this->module_id(),
			$class,
			$display_title,
			$content
		);

		return $output;
	}
}
new CAWeb_Module_Panel();
