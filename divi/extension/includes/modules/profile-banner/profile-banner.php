<?php
/**
 * CAWeb Profile Banner Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Profile Banner Module Class (Standard)
 */
class CAWeb_Module_Profile_Banner extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_profile_banner';
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
		$this->name = esc_html__( 'Profile Banner', 'et_builder' );

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
					'body' => esc_html__( 'Body', 'et_builder' ),
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
			'name' => array(
				'label'           => esc_html__( 'Profile Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the name of the profile.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'job_title' => array(
				'label'           => esc_html__( 'Job Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the job title.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'header',
			),
			'profile_link' => array(
				'label'           => esc_html__( 'Profile Link', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the text for the profile link.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
				'default'         => 'Profile Link',
			),
			'url' => array(
				'label'           => esc_html__( 'Profile URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'default'         => '#',
				'description'     => esc_html__( 'Input the website of the profile.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'portrait_url' => array(
				'label'              => esc_html__( 'Portrait Image URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'et_builder' ),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'body',
			),
			'portrait_alt' => array(
				'label'           => esc_html__( 'Portrait Image Alt Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the alt text for the portrait image.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		$design_fields = array(
			'round_image' => array(
				'label'              => esc_html__( 'Round Image', 'et_builder' ),
				'type'               => 'yes_no_button',
				'option_category'    => 'configuration',
				'options'            => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'description'        => esc_html__( 'Switch to yes if you want round images in the profile banner.', 'et_builder' ),
				'tab_slug'           => 'advanced',
				'toggle_slug'        => 'body',
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
		$name         = $this->props['name'];
		$job_title    = $this->props['job_title'];
		$profile_link = $this->props['profile_link'];
		$portrait_url = $this->props['portrait_url'];
		$portrait_alt = $this->props['portrait_alt'];
		$round        = $this->props['round_image'];
		$url          = $this->props['url'];

		$this->add_classname( 'profile-banner-wrapper' );
		$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

		$url = ! empty( $url ) ? esc_url( $url ) : '';

		if ( empty( $portrait_alt ) && ! empty( $portrait_url ) ) {
			$portrait_id  = attachment_url_to_postid( $portrait_url );
			$portrait_alt = get_post_meta( $portrait_id, '_wp_attachment_image_alt', true );
		}

		if ( 'on' !== $round ) {
			$round_class  = '';
			$inline_image = sprintf( ' style="background:url(%1$s) no-repeat right bottom;"', $portrait_url );
			$image        = '';
		} else {
			$round_class  = 'round-image';
			$inline_image = '';
			$image        = sprintf( '<div class="profile-banner-img-wrapper"><img src="%1$s" style="width: 90px; min-height: 90px;float: right;" alt="%2$s"/></div>', $portrait_url, $portrait_alt );
		}

		$output = sprintf( '<div%1$s%2$s><div class="profile-banner%3$s"><div class="inner"%4$s>%5$s<div class="banner-subtitle">%6$s</div><div class="banner-title">%7$s</div><div class="banner-link"><a href="%8$s">%9$s</a></div></div></div></div>', $this->module_id(), $class, $round_class, $inline_image, $image, $job_title, $name, $url, $profile_link );

		return $output;
	}
}
new CAWeb_Module_Profile_Banner();

