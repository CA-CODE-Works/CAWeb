<?php
/**
 * CAWeb Post Handler Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb Post Handler Module Class (Standard)
 */
class CAWeb_Module_Post_Handler extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_post_handler';
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
		$this->name       = esc_html__( 'Post Detail', 'et_builder' );
		$this->post_types = array( 'post' );

		$this->main_css_element       = '%%order_class%%';
		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'style'   => esc_html__( 'Style', 'et_builder' ),
					'header'  => esc_html__( 'Header', 'et_builder' ),
					'course'  => esc_html__( 'Course', 'et_builder' ),
					'event'   => esc_html__( 'Event', 'et_builder' ),
					'exam'    => esc_html__( 'Exam', 'et_builder' ),
					'job'     => esc_html__( 'Job', 'et_builder' ),
					'news'    => esc_html__( 'News', 'et_builder' ),
					'profile' => esc_html__( 'Profile', 'et_builder' ),
					'body'    => esc_html__( 'Body', 'et_builder' ),
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
		// Custom handler: Output JS for editor preview in page footer.
		add_action( 'wp_footer', array( $this, 'remove_general_detail' ) );
	}

	/**
	 * Returns an array of all the Module Fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		$general_fields = array(
			'post_type_layout' => array(
				'label'             => esc_html__( 'Content Type', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'class'             => array( 'caweb_post_handler_style_selector' ),
				'options'           => array(
					'general' => esc_html__( 'General', 'et_builder' ),
					'course'  => esc_html__( 'Courses', 'et_builder' ),
					'event'   => esc_html__( 'Events', 'et_builder' ),
					'exam'    => esc_html__( 'Exams', 'et_builder' ),
					'faqs'    => esc_html__( 'FAQs', 'et_builder' ),
					'jobs'    => esc_html__( 'Jobs', 'et_builder' ),
					'news'    => esc_html__( 'News', 'et_builder' ),
					'profile' => esc_html__( 'Profile', 'et_builder' ),
				),
				'description'       => esc_html__( 'This is the layout style', 'et_builder' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'style',
			),
			'show_featured_image' => array(
				'label'           => esc_html__( 'Display Featured Image', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'default'         => 'on',
				'show_if'         => array( 'post_type_layout' => array( 'news', 'profile' ) ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'et_builder' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter additional content for this item.', 'et_builder' ),
				'show_if_not'     => array( 'post_type_layout' => 'general' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'show_tags_button' => array(
				'label'           => esc_html__( 'Tags', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'show_if_not'     => array( 'post_type_layout' => 'general' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'show_categories_button' => array(
				'label'           => esc_html__( 'Categories', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'show_if_not'     => array( 'post_type_layout' => 'general' ),
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

		$design_fields = array();

		$advanced_fields = array();

		$news_fields = array(
			'news_author' => array(
				'label'           => esc_html__( 'Author', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an Author for this news item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'news' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'news',
			),
			'news_publish_date' => array(
				'label'           => esc_html__( 'Publish Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a Publish Date for this news item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'news' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'news',
			),
			'news_publish_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'news' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'news',
			),
			'news_publish_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'M d, Y',
				'show_if'         => array(
					'post_type_layout'         => 'news',
					'news_publish_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'news',
			),
			'news_city' => array(
				'label'           => esc_html__( 'News Location', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a Location for this news item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'news' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'news',
			),
		);

		$profile_fields = array(
			'profile_image_align' => array(
				'label'             => esc_html__( 'Image Alignment', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Left', 'et_builder' ),
					'on'  => esc_html__( 'Right', 'et_builder' ),
				),
				'description'       => 'Alignment for the featured profile image',
				'show_if'           => array( 'post_type_layout' => 'profile' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'style',
			),
			'profile_name_prefix' => array(
				'label'           => esc_html__( 'Name Prefix', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a prefix for this profile item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'profile' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_name' => array(
				'label'           => esc_html__( 'Profile Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a profile name for this profile item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'profile' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_career' => array(
				'label'             => esc_html__( 'Career', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'description'       => 'Job related fields',
				'show_if'           => array( 'post_type_layout' => 'profile' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'profile',
			),
			'profile_career_title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout' => 'profile',
					'profile_career'   => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_career_position' => array(
				'label'           => esc_html__( 'Position', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout' => 'profile',
					'profile_career'   => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_additional_fields' => array(
				'label'             => esc_html__( 'Additional List Fields', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'description'       => 'Additional information for the Post List.',
				'show_if'           => array( 'post_type_layout' => 'profile' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'profile',
			),
			'profile_career_line_1' => array(
				'label'           => esc_html__( 'Line 1', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'          => 'profile',
					'profile_additional_fields' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_career_line_2' => array(
				'label'           => esc_html__( 'Line 2', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'          => 'profile',
					'profile_additional_fields' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
			'profile_career_line_3' => array(
				'label'           => esc_html__( 'Line 3', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'          => 'profile',
					'profile_additional_fields' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'profile',
			),
		);

		$exam_fields = array(
			'exam_id' => array(
				'label'           => esc_html__( 'Exam Code', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an Exam Code for this exam item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_class' => array(
				'label'           => esc_html__( 'Class Code', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an Class Code for this exam item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_status' => array(
				'label'             => esc_html__( 'Status', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'open'   => esc_html__( 'Open', 'et_builder' ),
					'closed' => esc_html__( 'Closed', 'et_builder' ),
				),
				'description'       => esc_html__( 'Select the status for this exam item.', 'et_builder' ),
				'show_if'           => array( 'post_type_layout' => 'exam' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'exam',
			),
			'exam_published_date' => array(
				'label'           => esc_html__( 'Publish Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the Publish Date for this exam item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_published_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_published_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'           => 'exam',
					'exam_published_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_final_filing_date_chooser' => array(
				'label'           => esc_html__( 'Use Date Picker for Final Filing Date', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'on',
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_final_filing_date' => array(
				'label'           => esc_html__( 'Final Filing Date', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the Final Filing Date for this exam item.', 'et_builder' ),
				'default'         => 'Until Filled',
				'show_if'         => array(
					'post_type_layout'               => 'exam',
					'exam_final_filing_date_chooser' => 'off',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_final_filing_date_picker' => array(
				'label'           => esc_html__( 'Final Filing Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the Final Filing Date for this exam item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'               => 'exam',
					'exam_final_filing_date_chooser' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_final_filing_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'exam' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_final_filing_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'              => 'exam',
					'exam_final_filing_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_type' => array(
				'label'             => esc_html__( 'Exam Type', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'web'      => esc_html__( 'Web', 'et_builder' ),
					'location' => esc_html__( 'Classroom', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'exam' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'exam',
			),
			'exam_url' => array(
				'label'           => esc_html__( 'URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the URL for this exam item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout' => 'exam',
					'exam_type'        => 'web',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_address' => array(
				'label'           => esc_html__( 'Address', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter address for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout' => 'exam',
					'exam_type'        => 'location',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_city' => array(
				'label'           => esc_html__( 'City', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter city for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout' => 'exam',
					'exam_type'        => 'location',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_state' => array(
				'label'           => esc_html__( 'State', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter state for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout' => 'exam',
					'exam_type'        => 'location',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
			'exam_zip' => array(
				'label'           => esc_html__( 'Zip', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter zip code for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout' => 'exam',
					'exam_type'        => 'location',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'exam',
			),
		);

		$course_fields = array(
			'show_course_presenter' => array(
				'label'             => esc_html__( 'Presenter', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'course' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'course',
			),
			'course_presenter_name' => array(
				'label'           => esc_html__( 'Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'      => 'course',
					'show_course_presenter' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_presenter_image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'show_if'            => array(
					'post_type_layout'      => 'course',
					'show_course_presenter' => 'on',
				),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'course',
			),
			'course_presenter_bio' => array(
				'label'           => esc_html__( 'Short Bio', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'      => 'course',
					'show_course_presenter' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_start_date' => array(
				'label'           => esc_html__( 'Start Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter start date for this course item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'course' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_start_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'course' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_start_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'         => 'course',
					'course_start_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_end_date' => array(
				'label'           => esc_html__( 'End Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter end date for this course item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'course' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_end_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'course' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_end_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'       => 'course',
					'course_end_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'show_course_address' => array(
				'label'             => esc_html__( 'Course Location', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'course' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'course',
			),
			'course_address' => array(
				'label'           => esc_html__( 'Address', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Course address for this course item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'    => 'course',
					'show_course_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_city' => array(
				'label'           => esc_html__( 'City', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Course city for this course item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'    => 'course',
					'show_course_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_state' => array(
				'label'           => esc_html__( 'State', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Course state for this course item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'    => 'course',
					'show_course_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_zip' => array(
				'label'           => esc_html__( 'Zip', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Course zip code for this course item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'    => 'course',
					'show_course_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'course_registration_type' => array(
				'label'             => esc_html__( 'Registration Type', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'description'       => esc_html__( 'Enter a registration type for this course item.', 'et_builder' ),
				'show_if'           => array( 'post_type_layout' => 'course' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'course',
			),
			'course_cost' => array(
				'label'           => esc_html__( 'Cost', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Course Cost for this course item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'course' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'course',
			),
			'show_course_map' => array(
				'label'             => esc_html__( 'Course Map', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'course' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'course',
			),
		);

		$event_fields = array(
			'event_organizer' => array(
				'label'           => esc_html__( 'Organizer', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the name of the organizer.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'show_event_presenter' => array(
				'label'             => esc_html__( 'Presenter', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'event' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'event',
			),
			'event_presenter_name' => array(
				'label'           => esc_html__( 'Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'     => 'event',
					'show_event_presenter' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_presenter_image' => array(
				'label'              => esc_html__( 'Image', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'show_if'            => array(
					'post_type_layout'     => 'event',
					'show_event_presenter' => 'on',
				),
				'tab_slug'           => 'general',
				'toggle_slug'        => 'event',
			),
			'event_presenter_bio' => array(
				'label'           => esc_html__( 'Short Bio', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout'     => 'event',
					'show_event_presenter' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_start_date' => array(
				'label'           => esc_html__( 'Start Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter start date for this event item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_start_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_start_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'        => 'event',
					'event_start_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_end_date' => array(
				'label'           => esc_html__( 'End Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter end date for this event item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_end_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_end_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'      => 'event',
					'event_end_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'show_event_address' => array(
				'label'             => esc_html__( 'Event Location', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'event' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'event',
			),
			'event_address' => array(
				'label'           => esc_html__( 'Address', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Event address for this event item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'event',
					'show_event_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_city' => array(
				'label'           => esc_html__( 'City', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Event city for this event item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'event',
					'show_event_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_state' => array(
				'label'           => esc_html__( 'State', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Event state for this event item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'event',
					'show_event_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_zip' => array(
				'label'           => esc_html__( 'Zip', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Event zip code for this event item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'event',
					'show_event_address' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
			'event_registration_type' => array(
				'label'             => esc_html__( 'Registration Type', 'et_builder' ),
				'type'              => 'text',
				'option_category'   => 'basic_option',
				'description'       => esc_html__( 'Enter a registration type for this event item.', 'et_builder' ),
				'show_if'           => array( 'post_type_layout' => 'event' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'event',
			),
			'event_cost' => array(
				'label'           => esc_html__( 'Cost', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Event Cost for this event item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'event' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'event',
			),
		);

		$job_fields = array(
			'show_about_agency' => array(
				'label'             => esc_html__( 'Agency', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'job',
			),
			'job_agency_name' => array(
				'label'           => esc_html__( 'Agency Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter an Agency Name for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_agency_address' => array(
				'label'           => esc_html__( 'Agency Address', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Agency address for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_agency_city' => array(
				'label'           => esc_html__( 'Agency City', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Agency city for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_agency_state' => array(
				'label'           => esc_html__( 'Agency State', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Agency state for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_agency_zip' => array(
				'label'           => esc_html__( 'Agency Zip', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Agency zip code for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_agency_about' => array(
				'label'           => esc_html__( 'About Agency', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter information about the Agency for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_about_agency' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_hours' => array(
				'label'           => esc_html__( 'Job Hours', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter job hours for this job item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'show_job_salary' => array(
				'label'             => esc_html__( 'Salary', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'job',
			),
			'job_salary_min' => array(
				'label'           => esc_html__( 'Minimum Salary', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout' => 'jobs',
					'show_job_salary'  => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_salary_max' => array(
				'label'           => esc_html__( 'Maximum Salary', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'show_if'         => array(
					'post_type_layout' => 'jobs',
					'show_job_salary'  => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_posted_date' => array(
				'label'           => esc_html__( 'Date Posted', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter posted date for this job item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_posted_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_posted_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'M d, Y',
				'show_if'         => array(
					'post_type_layout'       => 'jobs',
					'job_posted_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_position_number' => array(
				'label'           => esc_html__( 'Position Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a position number for this job item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_rpa_number' => array(
				'label'           => esc_html__( 'RPA Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter a rpa number for this job item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_ds_url' => array(
				'label'           => esc_html__( 'Duty Statement (URL)', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the duty statement\'s url link for this job item.', 'et_builder' ),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_final_filing_date_chooser' => array(
				'label'           => esc_html__( 'Use Date Picker for Final Filing Date', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_final_filing_date' => array(
				'label'           => esc_html__( 'Final Filing Date', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the final filing date for this job item.', 'et_builder' ),
				'default'         => 'Until Filled',
				'show_if'         => array(
					'post_type_layout'              => 'jobs',
					'job_final_filing_date_chooser' => 'off',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_final_filing_date_picker' => array(
				'label'           => esc_html__( 'Final Filing Date', 'et_builder' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter the Final Filing Date for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'              => 'jobs',
					'job_final_filing_date_chooser' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_final_filing_date_format' => array(
				'label'           => esc_html__( 'Custom Date Format', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_final_filing_date_custom_format' => array(
				'label'           => esc_html__( 'Pattern', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'depends_show_if' => 'on',
				'description'     => et_get_safe_localization(
					sprintf(
						'For formatting help visit <a href="%1$s" target="_blank" title="Formatting Date and Time">Formatting Date and Time</a>',
						esc_url( 'https://wordpress.org/support/article/formatting-date-and-time/' )
					)
				),
				'default'         => 'D, n/j/Y g:i a',
				'show_if'         => array(
					'post_type_layout'             => 'jobs',
					'job_final_filing_date_format' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'show_job_apply_to' => array(
				'label'             => esc_html__( 'Apply to', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'job',
			),
			'job_apply_to_dept' => array(
				'label'           => esc_html__( 'Department', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Department Name for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_apply_to_name' => array(
				'label'           => esc_html__( 'Contact Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact Name for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_apply_to_address' => array(
				'label'           => esc_html__( 'Address', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact address for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_apply_to_city' => array(
				'label'           => esc_html__( 'City', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact city for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_apply_to_state' => array(
				'label'           => esc_html__( 'State', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact state for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_apply_to_zip' => array(
				'label'           => esc_html__( 'Zip', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact zip code for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'  => 'jobs',
					'show_job_apply_to' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'show_job_questions' => array(
				'label'             => esc_html__( 'Questions', 'et_builder' ),
				'type'              => 'yes_no_button',
				'option_category'   => 'configuration',
				'options'           => array(
					'off' => esc_html__( 'Hide', 'et_builder' ),
					'on'  => esc_html__( 'Show', 'et_builder' ),
				),
				'show_if'           => array( 'post_type_layout' => 'jobs' ),
				'tab_slug'          => 'general',
				'toggle_slug'       => 'job',
			),
			'job_questions_name' => array(
				'label'           => esc_html__( 'Name', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact Name for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'jobs',
					'show_job_questions' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_questions_phone' => array(
				'label'           => esc_html__( 'Phone Number', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact Phone Number for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'jobs',
					'show_job_questions' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
			'job_questions_email' => array(
				'label'           => esc_html__( 'Email', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Enter Contact Email for this job item.', 'et_builder' ),
				'show_if'         => array(
					'post_type_layout'   => 'jobs',
					'show_job_questions' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'job',
			),
		);

		return array_merge( $general_fields, $course_fields, $event_fields, $exam_fields, $job_fields, $news_fields, $profile_fields, $design_fields, $advanced_fields );
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
		global $post;
		$post_type_layout = $this->props['post_type_layout'];

		setlocale( LC_MONETARY, get_locale() );

		if ( ! isset( $post->ID ) && 'general' !== $post_type_layout ) {
			return;
		}

		// List Style Type.
		switch ( $post_type_layout ) {
			// Course.
			case 'course':
				$this->add_classname( 'course-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf( '<article%1$s%2$s>%3$s</article>', $this->module_id(), $class, $this->renderCourseDetail( $post->ID ) );
			// Event.
			case 'event':
				$this->add_classname( 'event-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf( '<article%1$s%2$s>%3$s</article>', $this->module_id(), $class, $this->renderEventDetail( $post->ID ) );
			// Exams.
			case 'exam':
				$this->add_classname( 'exam-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf( '<div%1$s%2$s>%3$s</div>', $this->module_id(), $class, $this->renderExamDetail( $post->ID ) );
			// FAQs.
			case 'faqs':
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf(
					'<article%1$s%2$s>%3$s%4$s</article>',
					$this->module_id(),
					$class,
					$this->content,
					$this->renderFooter( $post->ID )
				);
			// Jobs.
			case 'jobs':
				$this->add_classname( 'job-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf(
					'<article%1$s%2$s>%3$s</article>',
					$this->module_id(),
					$class,
					$this->renderJobDetail( $post->ID )
				);
			// News.
			case 'news':
				$this->add_classname( 'news-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf(
					'<article%1$s%2$s>%3$s</article>',
					$this->module_id(),
					$class,
					$this->renderNewsDetail( $post->ID )
				);
			// Profile.
			case 'profile':
				$this->add_classname( 'profile-detail' );
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf(
					'<article%1$s%2$s>%3$s</article>',
					$this->module_id(),
					$class,
					$this->renderProfileDetail( $post->ID )
				);
			// General.
			case 'general':
				$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

				return sprintf( '<article id="general_post_detail"%1$s></article>', $class );
		}

	}

	/**
	 * This is a non-standard function, it outputs JS code to remove the general post detail article.
	 *
	 * @return void
	 */
	public function remove_general_detail() {
		$nonce    = wp_create_nonce( 'caweb_remove_general_detail' );
		$verified = isset( $nonce ) && wp_verify_nonce( sanitize_key( $nonce ), 'caweb_remove_general_detail' );

		global $post;

		if ( null === $post || ( isset( $_GET['et_fb'] ) && '1' === $_GET['et_fb'] ) ) {
			return;
		}

		$con    = ( is_object( $post ) ? $post->post_content : $post['post_content'] );
		$module = caweb_get_shortcode_from_content( $con, 'et_pb_ca_post_handler' );
		if ( empty( $module ) || 'general' !== $module->post_type_layout ) {
			return;
		} ?>
		<script>
			var detail = document.getElementById('general_post_detail').parentNode;
			if(1 === detail.childElementCount){
				var row = detail.parentNode;
				row.removeChild(detail);
				if(0 === row.childElementCount){
					if(1 === row.parentNode.childElementCount ){
						row.parentNode.remove();
					}else{
						row.parentNode.removeChild(row);
					}
				}
			}else{
				detail.removeChild(document.getElementById('general_post_detail'));
			}
			</script>
			<?php
	}

	/**
	 * Renders Course Detail Page
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderCourseDetail( $post_id ) {
		// Course Attributes.
		$show_course_presenter           = $this->props['show_course_presenter'];
		$course_presenter_name           = $this->props['course_presenter_name'];
		$course_presenter_image          = $this->props['course_presenter_image'];
		$course_presenter_bio            = $this->props['course_presenter_bio'];
		$course_start_date               = $this->props['course_start_date'];
		$course_start_date_format        = $this->props['course_start_date_format'];
		$course_start_date_custom_format = $this->props['course_start_date_custom_format'];
		$course_end_date                 = $this->props['course_end_date'];
		$course_end_date_format          = $this->props['course_end_date_format'];
		$course_end_date_custom_format   = $this->props['course_end_date_custom_format'];
		$show_course_address             = $this->props['show_course_address'];
		$course_address                  = $this->props['course_address'];
		$course_city                     = $this->props['course_city'];
		$course_state                    = $this->props['course_state'];
		$course_zip                      = $this->props['course_zip'];
		$course_registration_type        = $this->props['course_registration_type'];
		$course_cost                     = $this->props['course_cost'];
		$show_course_map                 = $this->props['show_course_map'];

		// Course Presenter Image.
		if ( ! empty( $course_presenter_image ) ) {
			$alt_text               = caweb_get_attachment_post_meta( $course_presenter_image, '_wp_attachment_image_alt' );
			$course_presenter_image = sprintf( '<img src="%1$s" alt="%2$s" class="img-left" style="height: 75px; width: 75px;">', $course_presenter_image, $alt_text );
		}

		// Display Course Presenter Information.
		$presenter = '';

		if ( 'on' === $show_course_presenter ) {
			$presenter = sprintf(
				'<div class="presenter mb-1 d-inline-block"><p><strong>Presenter:</strong><br><strong class="presenter-name">%1$s</strong></p><p>%2$s%3$s</p></div>',
				$course_presenter_name,
				$course_presenter_image,
				$course_presenter_bio
			);
		}

		$course_addr = array( $course_address, $course_city, $course_state, $course_zip );

		$location = 'on' === $show_course_address ? sprintf( '<span class="ca-gov-icon-road-pin"></span>%1$s', $this->caweb_get_google_map_place_link( $course_addr ) ) : '';

		$course_start_date        = ! empty( $course_start_date ) ? gmdate( $course_start_date_custom_format, strtotime( $course_start_date ) ) : '';
		$course_end_date          = ! empty( $course_end_date ) ? gmdate( $course_end_date_custom_format, strtotime( $course_end_date ) ) : '';
		$organizer                = sprintf(
			'<strong>Organizer</strong><br /><p class="date-time">%1$s%2$s<br />%3$s</p>',
			$course_start_date,
			! empty( $course_end_date ) ? sprintf( ' - %1$s', $course_end_date ) : '',
			$location
		);
		$course_registration_type = ! empty( $course_registration_type ) ? sprintf( 'Registration Type: %1$s', $course_registration_type ) : '';
		$course_cost              = ! empty( $course_cost ) ? sprintf( 'Registration Cost: %1$s', $course_cost ) : '';

		$reg = array_filter( array( $course_registration_type, $course_cost ) );
		$reg = ! empty( $reg ) ? sprintf( '<p>%1$s</p>', implode( '<br />', $reg ) ) : '';

		if ( 'on' === $show_course_map ) {
			$map_embed  = $this->caweb_get_google_map_place_link( $course_addr, true );
			$course_map = sprintf( '<div class="third">%1$s</div>', $map_embed );
		} else {
			$course_map = '';
		}

		return sprintf(
			'<div class="description">%1$s</div>%2$s<div class="group"><div class="two-thirds">%3$s%4$s</div>%5$s</div>%6$s',
			$this->content,
			$presenter,
			$organizer,
			$reg,
			$course_map,
			$this->renderFooter( $post_id )
		);

	}

	/**
	 * Renders Events Detail Page
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderEventDetail( $post_id ) {
		// Event Attributes.
		$event_organizer                = $this->props['event_organizer'];
		$show_event_presenter           = $this->props['show_event_presenter'];
		$event_presenter_name           = $this->props['event_presenter_name'];
		$event_presenter_image          = $this->props['event_presenter_image'];
		$event_presenter_bio            = $this->props['event_presenter_bio'];
		$event_start_date               = $this->props['event_start_date'];
		$event_start_date_format        = $this->props['event_start_date_format'];
		$event_start_date_custom_format = $this->props['event_start_date_custom_format'];
		$event_end_date                 = $this->props['event_end_date'];
		$event_end_date_format          = $this->props['event_end_date_format'];
		$event_end_date_custom_format   = $this->props['event_end_date_custom_format'];
		$show_event_address             = $this->props['show_event_address'];
		$event_address                  = $this->props['event_address'];
		$event_city                     = $this->props['event_city'];
		$event_state                    = $this->props['event_state'];
		$event_zip                      = $this->props['event_zip'];
		$event_registration_type        = $this->props['event_registration_type'];
		$event_cost                     = $this->props['event_cost'];

		// Event Presenter Image.
		if ( ! empty( $event_presenter_image ) ) {
			$alt_text              = caweb_get_attachment_post_meta( $event_presenter_image, '_wp_attachment_image_alt' );
			$event_presenter_image = sprintf( '<img src="%1$s" alt="%2$s" class="img-left" style="height: 75px; width: 75px;">', $event_presenter_image, $alt_text );
		}

		// Display Event Presenter Information.
		$presenter = '';

		if ( 'on' === $show_event_presenter ) {
			$presenter = sprintf( '<div class="presenter"><p><strong>Presenter:</strong><br><strong class="presenter-name">%1$s</strong></p><p>%2$s%3$s</p></div>', $event_presenter_name, $event_presenter_image, $event_presenter_bio );
		}

		$event_addr = array( $event_address, $event_city, $event_state, $event_zip );

		$location = 'on' === $show_event_address ? sprintf( '<span class="ca-gov-icon-road-pin"></span>%1$s', $this->caweb_get_google_map_place_link( $event_addr ) ) : '';

		$event_start_date = ! empty( $event_start_date ) ? gmdate( $event_start_date_custom_format, strtotime( $event_start_date ) ) : '';
		$event_end_date   = ! empty( $event_end_date ) ? gmdate( $event_end_date_custom_format, strtotime( $event_end_date ) ) : '';

		$organizer = sprintf(
			'%1$s<p class="date-time">%2$s%3$s<br />%4$s</p>',
			! empty( $event_organizer ) ? sprintf( '<strong>%1$s</strong><br />', $event_organizer ) : '',
			$event_start_date,
			! empty( $event_end_date ) ? sprintf( ' - %1$s', $event_end_date ) : '',
			$location
		);

		$event_registration_type = ! empty( $event_registration_type ) ? sprintf( 'Registration Type: %1$s', $event_registration_type ) : '';

		$event_cost = ! empty( $event_cost ) ? sprintf( 'Registration Cost: %1$s', $event_cost ) : '';

		$reg = array_filter( array( $event_registration_type, $event_cost ) );
		$reg = ! empty( $reg ) ? sprintf( '<p>%1$s</p>', implode( '<br />', $reg ) ) : '';

		return sprintf(
			'%1$s<div class="description">%2$s</div>%3$s%4$s%5$s%6$s',
			$this->caweb_get_the_post_thumbnail( null, 'thumbnail', array( 'class' => 'img-left pr-3' ) ),
			$this->content,
			$presenter,
			$organizer,
			$reg,
			$this->renderFooter( $post_id )
		);

	}

	/**
	 * Renders Exams Detail Page
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderExamDetail( $post_id ) {
		// Exam Attributes.
		$exam_id                              = $this->props['exam_id'];
		$exam_class                           = $this->props['exam_class'];
		$exam_status                          = $this->props['exam_status'];
		$exam_published_date                  = $this->props['exam_published_date'];
		$exam_published_date_format           = $this->props['exam_published_date_format'];
		$exam_published_date_custom_format    = $this->props['exam_published_date_custom_format'];
		$exam_final_filing_date_chooser       = $this->props['exam_final_filing_date_chooser'];
		$exam_final_filing_date               = $this->props['exam_final_filing_date'];
		$exam_final_filing_date_picker        = $this->props['exam_final_filing_date_picker'];
		$exam_final_filing_date_format        = $this->props['exam_final_filing_date_format'];
		$exam_final_filing_date_custom_format = $this->props['exam_final_filing_date_custom_format'];
		$exam_type                            = $this->props['exam_type'];
		$exam_url                             = $this->props['exam_url'];
		$exam_address                         = $this->props['exam_address'];
		$exam_city                            = $this->props['exam_city'];
		$exam_state                           = $this->props['exam_state'];
		$exam_zip                             = $this->props['exam_zip'];

		if ( 'web' === $exam_type ) {
			$exam_location = sprintf( 'Exam Url: <a href="%1$s">%1$s</a><br />', $exam_url );
		} else {
			$exam_addr     = $this->caweb_get_google_map_place_link( array( $exam_address, $exam_city, $exam_state, $exam_zip ) );
			$exam_location = sprintf( 'Exam Address: %1$s<br />', $exam_addr );
		}

		$exam_class  = ! empty( $exam_class ) ? sprintf( 'Class Code: %1$s', $exam_class ) : '';
		$exam_id     = ! empty( $exam_id ) ? sprintf( 'Exam Code: %1$s', $exam_id ) : '';
		$exam_course = array_filter( array( $exam_class, $exam_id ) );
		$exam_course = ! empty( $exam_course ) ? implode( ' - ', $exam_course ) . '<br />' : '';
		$pub_date    = gmdate( $exam_published_date_custom_format, strtotime( $exam_published_date ) );
		$pub_date    = ( ! empty( $exam_published_date ) ? sprintf( 'Published Date: %1$s<br />', $pub_date ) : '' );

		if ( 'on' === $exam_final_filing_date_chooser ) {
			$exam_final_filing_date = ! empty( $exam_final_filing_date_picker ) ? sprintf( 'Final Filing Date: %1$s<br />', gmdate( $exam_final_filing_date_custom_format, strtotime( $exam_final_filing_date_picker ) ) ) : '';
		} else {
			$exam_final_filing_date = sprintf( 'Final Filing Date: %1$s<br />', $exam_final_filing_date );
		}

		$exam_info = sprintf(
			'<p>%1$s%2$s%3$s%4$s</p>',
			sprintf( '%1$s', $exam_course ),
			$pub_date,
			$exam_final_filing_date,
			$exam_location
		);

		return sprintf(
			'<div class="header">%1$s%2$s</div>%3$s%4$s',
			$this->caweb_get_the_post_thumbnail( null, 'medium', array( 'class' => 'd-block mb-3' ) ),
			$exam_info,
			$this->content,
			$this->renderFooter( $post_id )
		);

	}

	/**
	 * Render Jobs Detail Page.
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderJobDetail( $post_id ) {
		// Job Attributes.
		$show_about_agency                   = $this->props['show_about_agency'];
		$job_agency_name                     = $this->props['job_agency_name'];
		$job_agency_address                  = $this->props['job_agency_address'];
		$job_agency_city                     = $this->props['job_agency_city'];
		$job_agency_state                    = $this->props['job_agency_state'];
		$job_agency_zip                      = $this->props['job_agency_zip'];
		$job_agency_about                    = $this->props['job_agency_about'];
		$job_posted_date                     = $this->props['job_posted_date'];
		$job_posted_date_format              = $this->props['job_posted_date_format'];
		$job_posted_date_custom_format       = $this->props['job_posted_date_custom_format'];
		$job_hours                           = $this->props['job_hours'];
		$show_job_salary                     = $this->props['show_job_salary'];
		$job_salary_min                      = $this->props['job_salary_min'];
		$job_salary_max                      = $this->props['job_salary_max'];
		$job_position_number                 = $this->props['job_position_number'];
		$job_rpa_number                      = $this->props['job_rpa_number'];
		$job_ds_url                          = $this->props['job_ds_url'];
		$job_final_filing_date_chooser       = $this->props['job_final_filing_date_chooser'];
		$job_final_filing_date               = $this->props['job_final_filing_date'];
		$job_final_filing_date_picker        = $this->props['job_final_filing_date_picker'];
		$job_final_filing_date_format        = $this->props['job_final_filing_date_format'];
		$job_final_filing_date_custom_format = $this->props['job_final_filing_date_custom_format'];
		$show_job_apply_to                   = $this->props['show_job_apply_to'];
		$job_apply_to_dept                   = $this->props['job_apply_to_dept'];
		$job_apply_to_name                   = $this->props['job_apply_to_name'];
		$job_apply_to_address                = $this->props['job_apply_to_address'];
		$job_apply_to_city                   = $this->props['job_apply_to_city'];
		$job_apply_to_state                  = $this->props['job_apply_to_state'];
		$job_apply_to_zip                    = $this->props['job_apply_to_zip'];
		$show_job_questions                  = $this->props['show_job_questions'];
		$job_questions_name                  = $this->props['job_questions_name'];
		$job_questions_phone                 = $this->props['job_questions_phone'];
		$job_questions_email                 = $this->props['job_questions_email'];

		$agency_addr = $this->caweb_get_google_map_place_link( array( $job_agency_address, $job_agency_city, $job_agency_state, $job_agency_zip ) );
		$agency_addr = ! empty( $agency_addr ) ? sprintf( '<span class="ca-gov-icon-road-pin"></span>%1$s', $agency_addr ) : '';

		$agency_info = 'on' === $show_about_agency ? sprintf( '<div class="entity"><strong>%1$s</strong>%2$s</div>', $job_agency_name, $agency_addr ) : '';

		if ( ! empty( $job_posted_date ) ) {
			$job_posted_date = gmdate( $job_posted_date_custom_format, strtotime( $job_posted_date ) );
			$d1              = date_create( gmdate( 'm/d/Y', strtotime( $job_posted_date ) ) );
			$d2              = date_create_from_format( 'm/d/Y', ( new DateTime( 'NOW' ) )->format( 'm/d/Y' ) );
			$tmp             = $d1->diff( $d2 )->format( '%a' );
			$days_passed     = 0 !== (int) $tmp ? sprintf( '&mdash;<span class="fuzzy-date"> %1$s days ago</span>', $tmp ) : '';
			$job_posted_date = sprintf( '<div class="published">Published: <time>%1$s</time>%2$s</div>', $job_posted_date, $days_passed );
		} else {
			$job_posted_date = '';
		}
		$job_hours      = ! empty( $job_hours ) ? sprintf( '%1$s<br />', $job_hours ) : '';
		$job_salary_min = $this->caweb_is_money( $job_salary_min, '$0.00' );
		$job_salary_max = $this->caweb_is_money( $job_salary_max, '$0.00' );
		$job_salary     = 'on' === $show_job_salary ? sprintf( 'Salary Range: %1$s - %2$s<br />', $job_salary_min, $job_salary_max ) : '';

		$job_position = '';
		if ( ! empty( $job_position_number ) && ! empty( $job_rpa_number ) ) {
			$job_position = sprintf( 'Position Number: %1$s, RPA #%2$s<br />', $job_position_number, $job_rpa_number );
		} elseif ( ! empty( $job_position_number ) ) {
			$job_position = sprintf( 'Position Number: %1$s<br />', $job_position_number );
		} elseif ( ! empty( $job_rpa_number ) ) {
			$job_position = sprintf( 'RPA #%1$s<br />', $job_rpa_number );
		}

		$job_ds_url = ! empty( $job_ds_url ) ? sprintf( 'Duty Statement (<a href="%1$s">PDF</a>)<br />', $job_ds_url ) : '';

		if ( 'on' === $job_final_filing_date_chooser ) {
			$job_final_filing_date = ! empty( $job_final_filing_date_picker ) ? sprintf( 'Final Filing Date:<time>%1$s</time><br />', gmdate( $job_final_filing_date_custom_format, strtotime( $job_final_filing_date_picker ) ) ) : '';
		} else {
			$job_final_filing_date = sprintf( 'Final Filing Date: %1$s<br />', $job_final_filing_date );
		}

		$job_info = sprintf(
			'<div class="half"><div class="well"><div class="well-body"><p>%1$s%2$s%3$s%4$s%5$s</p></div></div></div>',
			$job_hours,
			$job_salary,
			$job_position,
			$job_ds_url,
			$job_final_filing_date
		);

		if ( 'on' === $show_job_apply_to ) {
			$location = $this->caweb_get_address( array( $job_apply_to_address, $job_apply_to_city, $job_apply_to_state, $job_apply_to_zip ) );

			$job_apply_to_info = sprintf(
				'<strong>Apply To:</strong><br />%1$s%2$s%3$s',
				! empty( $job_apply_to_dept ) ? sprintf( '%1$s<br />', $job_apply_to_dept ) : '',
				! empty( $job_apply_to_name ) ? sprintf( ' Attn: %1$s<br />', $job_apply_to_name ) : '',
				$location
			);
		}

		if ( 'on' === $show_job_questions ) {
			$j_info             = ! empty( $job_questions_phone ) && ! empty( $job_questions_email ) ?
			sprintf( '%1$s, or <a href="mailto:%2$s">%2$s</a>', $job_questions_phone, $job_questions_email ) : '';
			$j_info             = empty( $j_info ) && ! empty( $job_questions_phone ) ? $job_questions_phone : $j_info;
			$j_info             = empty( $j_info ) && ! empty( $job_questions_email ) ? sprintf( '<a href="mailto:%1$s">%1$s</a>', $job_questions_email ) : $j_info;
			$job_questions_info = sprintf(
				'<strong>Questions</strong><br />%1$s%2$s',
				! empty( $job_questions_name ) ? sprintf( '%1$s at ', $job_questions_name ) : 'Contact ',
				$j_info
			);
		}

		$job_apply_info = ! empty( $job_apply_to_info ) || ! empty( $job_questions_info ) ?
					sprintf(
						'<div class="half"><div class="well"><div class="well-body">%1$s%2$s</div></div></div>',
						! empty( $job_apply_to_info ) ? sprintf( '<p>%1$s</p>', $job_apply_to_info ) : '',
						! empty( $job_questions_info ) ? sprintf( '<p>%1$s</p>', $job_questions_info ) : ''
					) : '';

		$job_agency_about = 'on' === $show_about_agency && ! empty( $job_agency_about ) ?
			sprintf( '<div class="panel panel-understated about-department"><div class="panel-heading"><h4>About this Department</h4></div><div class="panel-body"><p>%1$s</p></div></div> ', $job_agency_about ) : '';

		return sprintf(
			'<div class="sub-header">%1$s%2$s</div><div class="group">%3$s%4$s</div>%5$s%6$s%7$s',
			! empty( $agency_info ) ? $agency_info : '',
			$job_posted_date,
			$job_info,
			$job_apply_info,
			$job_agency_about,
			$this->content,
			$this->renderFooter( $post_id )
		);

	}

	/**
	 * Render News Detail Page.
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderNewsDetail( $post_id ) {
		// News Attributes.
		$news_author                     = $this->props['news_author'];
		$news_publish_date               = $this->props['news_publish_date'];
		$news_publish_date_format        = $this->props['news_publish_date_format'];
		$news_publish_date_custom_format = $this->props['news_publish_date_custom_format'];
		$news_city                       = $this->props['news_city'];
		$show_featured_image             = $this->props['show_featured_image'];

		$image = 'on' === $show_featured_image ? $this->caweb_get_the_post_thumbnail( null, array( 150, 100 ), array( 'class' => 'img-left' ) ) : '';

		$date_city = '';

		if ( ! empty( $news_publish_date ) || ! empty( $news_author ) || ! empty( $news_city ) ) {
			$news_publish_date = ! empty( $news_publish_date ) ? sprintf( 'Published: %1$s<br />', gmdate( $news_publish_date_custom_format, strtotime( $news_publish_date ) ) ) : '';
			$news_author       = ! empty( $news_author ) ? sprintf( 'Author: %1$s<br />', $news_author ) : '';
			$news_city         = ! empty( $news_city ) ? sprintf( '%1$s', $news_city ) : '';

			$date_city = sprintf( '<header><div class="published"><p>%1$s%2$s%3$s</p></div></header>', $news_author, $news_publish_date, $news_city );

		}

		return sprintf( '%1$s%2$s%3$s%4$s', $date_city, $image, $this->content, $this->renderFooter( $post_id ) );

	}

	/**
	 * Render Profile Detail Page.
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderProfileDetail( $post_id ) {
		// Profile Attributes.
		$profile_name_prefix  = $this->props['profile_name_prefix'];
		$profile_name         = $this->props['profile_name'];
		$profile_career_title = $this->props['profile_career_title'];
		$profile_image_align  = $this->props['profile_image_align'];
		$show_featured_image  = $this->props['show_featured_image'];

		$title = sprintf(
			'%1$s%2$s%3$s',
			! empty( $profile_name_prefix ) ? $profile_name_prefix . ' ' : '',
			$profile_name,
			! empty( $profile_career_title ) ? ', ' . $profile_career_title : ''
		);

		$img_align = 'on' === $profile_image_align ? 'img-right' : 'img-left';

		$image = 'on' === $show_featured_image ? $this->caweb_get_the_post_thumbnail(
			null,
			array( 150, 100 ),
			array(
				'class' => $img_align,
				'alt'   => $profile_name,
			)
		) : '';

		return sprintf( '%1$s%2$s%3$s%4$s', ! empty( $title ) ? sprintf( '<h1>%1$s</h1>', $title ) : '', $image, $this->content, $this->renderFooter( $post_id ) );

	}

	/**
	 * Render Footer Category/Tag Keywords.
	 *
	 * @param  string $post_id Post ID.
	 * @return string
	 */
	public function renderFooter( $post_id ) {
		// General Attributes.
		$show_tags_button       = $this->props['show_tags_button'];
		$show_categories_button = $this->props['show_categories_button'];

		// return posts tags.
		$tag_names = wp_get_post_tags( $post_id, array( 'fields' => 'names' ) );
		$tag_list  = '';
		if ( ! empty( $tag_names ) && 'on' === $show_tags_button ) {
			$tag_list = '<div class="pull-left mr-4">Tags or Keywords<ul>';
			foreach ( $tag_names as $n ) {
				$tag_list .= sprintf( '<li>%1$s</li>', $n );
			}
			$tag_list .= '</ul></div>';
		}
		// return posts categories.
		$cat_obj  = get_the_category( $post_id );
		$cat_list = '';
		if ( ! empty( $cat_obj ) && 'on' === $show_categories_button ) {
			$cat_list = 'Categories<ul>';
			foreach ( $cat_obj as $n ) {
				$cat_list .= sprintf( '<li>%1$s</li>', $n->name );
			}
			$cat_list .= '</ul>';
		}

		return sprintf( '<footer class="keywords">%1$s%2$s</footer>', $tag_list, $cat_list );
	}
}
new CAWeb_Module_Post_Handler();
?>
