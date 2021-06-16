<?php
/**
 * CAWeb Section Footer Group Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Section Footer Group Module Class (Standard)
 */
class CAWeb_Module_Footer_Group extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_section_footer_group';
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
		$this->name = esc_html__( 'Footer Group', 'et_builder' );

		$this->type                     = 'child';
		$this->child_title_var          = 'group_title';
		$this->child_title_fallback_var = 'group_title';

		$this->advanced_setting_title_text = esc_html__( 'New Footer Group', 'et_builder' );
		$this->settings_text               = esc_html__( 'Footer Group Settings', 'et_builder' );

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
		$general_fields = array(
			'group_title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the title for the group section.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'group_show_more_button' => array(
				'label'           => esc_html__( 'Read More Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'         => array( 'group_url' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'group_url' => array(
				'label'           => esc_html__( 'Read More URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the url for the Read More Button.', 'et_builder' ),
				'show_if'         => array( 'group_show_more_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'display_link_as_button' => array(
				'label'           => esc_html__( 'Links as Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		for ( $i = 1; $i <= 10; $i++ ) {
			$show = sprintf( 'group_link%1$s_show', $i );
			$text = sprintf( 'group_link_text%1$s', $i );
			$url  = sprintf( 'group_link_url%1$s', $i );

			$general_fields[ $show ] = array(
				'label'           => sprintf( 'Link %1$s', $i ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'         => array( $text, $url ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			);
			$general_fields[ $text ] = array(
				'label'           => sprintf( 'Link %1$s Text', $i ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the text for the link.', 'et_builder' ),
				'show_if'         => array( $show => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			);
			$general_fields[ $url ]  = array(
				'label'           => sprintf( 'Link %1$s URL', $i ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the URL for the destination.', 'et_builder' ),
				'show_if'         => array( $show => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			);
		}

		$design_fields = array(
			'heading_size' => array(
				'label'             => esc_html__( 'Heading Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h4',
				'description'       => esc_html__( 'Here you can choose the heading size for the group title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'heading_color' => array(
				'label'             => esc_html__( 'Heading Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom heading color for the title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'header',
			),
			'text_color' => array(
				'label'             => esc_html__( 'Text Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'description'       => esc_html__( 'Here you can define a custom text color for the list items.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'style',
			),
			'group_icon_button' => array(
				'label'           => esc_html__( 'List Icon', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'         => array( 'font_icon' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'style',
			),
			'font_icon' => array(
				'label'               => esc_html__( 'Group Icon', 'et_builder' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'default'             => '%-1%',
				'description'         => esc_html__( 'Define the icon for the group section.', 'et_builder' ),
				'show_if'             => array( 'group_icon_button' => 'on' ),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'style',
			),
		);

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
		$heading_size           = $this->props['heading_size'];
		$heading_color          = $this->props['heading_color'];
		$group_show_more_button = $this->props['group_show_more_button'];
		$group_url              = $this->props['group_url'];
		$group_title            = $this->props['group_title'];

		$this->add_classname( 'quarter' );

		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$heading_color = ! empty( $heading_color ) ? sprintf( ' style="color: %1$s" ', $heading_color ) : '';

		$display_more_button = '';

		if ( 'on' === $group_show_more_button ) {
			$display_more_button = sprintf( '<a href="%1$s" class="btn btn-primary" target="_blank">Read More<span class="sr-only">Read More about %2$s</span></a>', esc_url( $group_url ), $group_title );
		}

		$output = sprintf(
			'<div%1$s%2$s><%3$s%4$s>%5$s</%3$s>%6$s</ul>%7$s</div>',
			$this->module_id(),
			$class,
			$heading_size,
			$heading_color,
			$group_title,
			$this->renderGroupList(),
			$display_more_button
		);

		return $output;
	}

	/**
	 * Render the group list
	 *
	 * @return string
	 */
	public function renderGroupList() {
		$group_icon             = $this->props['font_icon'];
		$group_icon_button      = $this->props['group_icon_button'];
		$text_color             = $this->props['text_color'];
		$display_link_as_button = $this->props['display_link_as_button'];

		// List Color Styles.
		$text_color = ! empty( $text_color ) ? "color: $text_color" : '';
		$icon       = 'on' === $group_icon_button ? $this->caweb_get_icon_span( $group_icon, 'mr-1', $text_color ) : '';

		$link_as_button = 'on' === $display_link_as_button ? ' class="btn btn-default btn-xs"' : '';

		$group_links = '';

		$text_color = ! empty( $text_color ) ? " style=\"$text_color\"" : '';
		for ( $i = 1; $i <= 10; $i++ ) {
			$group_link_show = $this->props[ sprintf( 'group_link%1$s_show', $i ) ];
			$group_link_text = $this->props[ sprintf( 'group_link_text%1$s', $i ) ];
			$group_link_url  = $this->props[ sprintf( 'group_link_url%1$s', $i ) ];

			if ( 'on' === $group_link_show ) {
				$group_links .= sprintf(
					'<li class="mb-2"><a href="%1$s"%2$s%3$s target="_blank" title="Section Footer Group %5$s">%4$s%5$s</a></li>',
					esc_url( $group_link_url ),
					$link_as_button,
					$text_color,
					$icon,
					$group_link_text
				);
			}
		}

		return sprintf( '<ul class="list-unstyled p-0">%1$s</ul>', $group_links );
	}
}
new CAWeb_Module_Footer_Group();
