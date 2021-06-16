<?php
/**
 * CAWeb Location Module Class (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Location Module Class (Standard)
 */
class CAWeb_Module_Location extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_location_widget';
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
		$this->name             = esc_html__( 'Location', 'caweb-divi-modules' );
		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'style'  => esc_html__( 'Style', 'caweb-divi-modules' ),
					'header' => esc_html__( 'Header', 'caweb-divi-modules' ),
					'body'   => esc_html__( 'Body', 'caweb-divi-modules' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'style' => esc_html__( 'Style', 'caweb-divi-modules' ),
					'text'  => array(
						'title'    => esc_html__( 'Text', 'caweb-divi-modules' ),
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
			'location_layout' => array(
				'label'             => esc_html__( 'Style', 'caweb-divi-modules' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'contact' => esc_html__( 'Contact', 'caweb-divi-modules' ),
					'mini'    => esc_html__( 'Mini', 'caweb-divi-modules' ),
					'banner'  => esc_html__( 'Banner', 'caweb-divi-modules' ),
				),
				'description'       => esc_html__( 'Here you can choose the style in which to display the location', 'caweb-divi-modules' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'style',
			),
			'featured_image' => array(
				'label'              => esc_html__( 'Set Featured Image', 'caweb-divi-modules' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'caweb-divi-modules' ),
				'choose_text'        => esc_attr__( 'Choose a Background Image', 'caweb-divi-modules' ),
				'update_text'        => esc_attr__( 'Set As Background', 'caweb-divi-modules' ),
				'description'        => esc_html__( 'If defined, this image will be used as the background for this location. To remove a background image, simply delete the URL from the settings field.', 'caweb-divi-modules' ),
				'show_if'            => array( 'location_layout' => 'banner' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'style',
			),
			'name' => array(
				'label'           => esc_html__( 'Location Name', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a name for the location.', 'caweb-divi-modules' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'desc' => array(
				'label'           => esc_html__( 'Location Description', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a description of the location.', 'caweb-divi-modules' ),
				'show_if'         => array( 'location_layout' => 'banner' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'addr' => array(
				'label'           => esc_html__( 'Address', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an address.', 'caweb-divi-modules' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'city' => array(
				'label'           => esc_html__( 'City', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a city.', 'caweb-divi-modules' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'state' => array(
				'label'           => esc_html__( 'State', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a state.', 'caweb-divi-modules' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'zip' => array(
				'label'           => esc_html__( 'Zip', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an zip.', 'caweb-divi-modules' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'show_contact' => array(
				'label'           => esc_html__( 'Contact information', 'caweb-divi-modules' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'caweb-divi-modules' ),
					'on'  => esc_html__( 'Yes', 'caweb-divi-modules' ),
				),
				'show_if'         => array( 'location_layout' => 'contact' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'phone' => array(
				'label'           => esc_html__( 'Phone Number', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a phone number.', 'caweb-divi-modules' ),
				'show_if'         => array( 'show_contact' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'fax' => array(
				'label'           => esc_html__( 'Fax Number', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a fax number.', 'caweb-divi-modules' ),
				'show_if'         => array( 'show_contact' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'show_button' => array(
				'label'           => esc_html__( 'Button', 'caweb-divi-modules' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'caweb-divi-modules' ),
					'on'  => esc_html__( 'Yes', 'caweb-divi-modules' ),
				),
				'show_if_not'     => array( 'location_layout' => 'mini' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'location_link' => array(
				'label'           => esc_html__( 'Location URL', 'caweb-divi-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the URL for the location.', 'caweb-divi-modules' ),
				'show_if'         => array( 'show_button' => 'on' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'caweb-divi-modules' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'caweb-divi-modules' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'admin_label',
			),
		);

		$design_fields = array(
			'show_icon' => array(
				'label'           => esc_html__( 'Use Icon', 'caweb-divi-modules' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'caweb-divi-modules' ),
					'on'  => esc_html__( 'Yes', 'caweb-divi-modules' ),
				),
				'show_if_not'     => array( 'location_layout' => 'banner' ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'style',
			),
			'font_icon' => array(
				'label'               => esc_html__( 'Icon', 'caweb-divi-modules' ),
				'type'                => 'text',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'renderer'            => 'select_icon',
				'renderer_with_field' => true,
				'default'             => '%-1%',
				'description'         => esc_html__( 'Select an icon.', 'caweb-divi-modules' ),
				'show_if'             => array( 'show_icon' => 'on' ),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'style',
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
		$location_layout = $this->props['location_layout'];

		$this->add_classname( 'location' );
		$this->add_classname( $location_layout );

		if ( 'contact' === $location_layout ) {
			$output = $this->contactLocation();
		} elseif ( 'mini' === $location_layout ) {
			$output = $this->miniLocation();
		} else {
			$output = $this->bannerLocation();
		}

		return $output;
	}

	/**
	 * Renders Location (contact)
	 *
	 * @return string
	 */
	public function contactLocation() {
		$show_contact  = $this->props['show_contact'];
		$phone         = $this->props['phone'];
		$fax           = $this->props['fax'];
		$show_button   = $this->props['show_button'];
		$location_link = $this->props['location_link'];
		$name          = $this->props['name'];
		$addr          = $this->props['addr'];
		$city          = $this->props['city'];
		$state         = $this->props['state'];
		$zip           = $this->props['zip'];
		$show_icon     = $this->props['show_icon'];
		$icon          = $this->props['font_icon'];

		$display_other  = '';
		$display_button = '';
		$display_icon   = '';
		$map_link       = $this->caweb_get_google_map_place_link( array( $addr, $city, $state, $zip ) );

		if ( 'on' === $show_contact ) {
			$phone         = ! empty( $phone ) ? "General Information: {$phone}<br />" : '';
			$fax           = ! empty( $fax ) ? "FAX: {$fax}" : '';
			$display_other = sprintf( '<p class="other">%1$s%2$s</p>', $phone, $fax );
		}

		if ( 'on' === $show_button && ! empty( $location_link ) ) {
			$display_button = sprintf( '<a href="%1$s" class="btn" target="_blank">More</a>', $location_link );
		}

		if ( ! empty( $name ) ) {
			$map_link = "$name<br />$map_link";
		}

		if ( 'on' === $show_icon ) {
			$display_icon = $this->caweb_get_icon_span( $icon, 'mr-3' );
		}

		return sprintf(
			'<div%1$s class="%2$s">%3$s<div class="contact"><p class="address">%4$s</p>%5$s%6$s</div></div>',
			$this->module_id(),
			$this->module_classname( $this->slug ),
			$display_icon,
			$map_link,
			$display_other,
			$display_button
		);
	}

	/**
	 * Renders Location (mini)
	 *
	 * @return string
	 */
	public function miniLocation() {
		$name          = $this->props['name'];
		$location_link = $this->props['location_link'];
		$addr          = $this->props['addr'];
		$city          = $this->props['city'];
		$state         = $this->props['state'];
		$zip           = $this->props['zip'];
		$show_icon     = $this->props['show_icon'];
		$icon          = $this->props['font_icon'];

		$map_link      = $this->caweb_get_google_map_place_link( array( $addr, $city, $state, $zip ) );
		$location_link = ! empty( $location_link ) ? esc_url( $location_link ) : '';
		$display_icon  = '';
		$contact_class = '';

		if ( 'on' === $show_icon ) {
			$display_icon = $this->caweb_get_icon_span( $icon );
		} else {
			$contact_class = ' ml-0';
		}

		if ( ! empty( $map_link ) ) {
			$map_link = sprintf( '<div class="address">%1$s</div>', $map_link );
		}

		return sprintf(
			'<div%1$s class="%2$s">%3$s<div class="contact%4$s"><div class="title"><a href="%5$s" target="_blank">%6$s</a></div>%7$s</div></div>',
			$this->module_id(),
			$this->module_classname( $this->slug ),
			$display_icon,
			$contact_class,
			$location_link,
			$name,
			$map_link
		);
	}

	/**
	 * Renders Location (banner)
	 *
	 * @return string
	 */
	public function bannerLocation() {
		$name           = $this->props['name'];
		$show_button    = $this->props['show_button'];
		$location_link  = $this->props['location_link'];
		$featured_image = $this->props['featured_image'];
		$desc           = $this->props['desc'];
		$addr           = $this->props['addr'];
		$city           = $this->props['city'];
		$state          = $this->props['state'];
		$zip            = $this->props['zip'];

		$display_button = '';
		$map_link       = $this->caweb_get_google_map_place_link( array( $addr, $city, $state, $zip ), false, '_blank', array( 'm-l-md', 'd-inline-block' ) );

		if ( 'on' === $show_button && ! empty( $location_link ) ) {
			$display_button = sprintf( '<a href="%1$s" class="btn" target="_blank">View More Details</a>', $location_link );
		}

		if ( ! empty( $featured_image ) ) {
			$alt_text       = caweb_get_attachment_post_meta( $featured_image, '_wp_attachment_image_alt' );
			$featured_image = sprintf( '<img src="%1$s" alt="%2$s" class="w-100"/>', $featured_image, ! empty( $alt_text ) ? $alt_text : ' ' );
		}

		if ( ! empty( $desc ) ) {
			$desc = sprintf( '<div class="title">Description</div><div class="description pb-2">%1$s</div>', $desc );
		}

		if ( ! empty( $map_link ) ) {
			$map_link = sprintf( ' <span class="ca-gov-icon-road-pin"></span>%1$s', $map_link );
		}

		return sprintf(
			'<div%1$s class="%2$s"><div class="thumbnail">%3$s</div><div class="contact"><div class="title">%4$s</div><div class="address">%5$s</div></div><div class="summary">%6$s%7$s</div></div>',
			$this->module_id(),
			$this->module_classname( $this->slug ),
			$featured_image,
			$name,
			$map_link,
			$desc,
			$display_button
		);
	}
}
new CAWeb_Module_Location();


