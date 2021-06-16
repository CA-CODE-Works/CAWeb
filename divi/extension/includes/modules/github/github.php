<?php
/**
 * CAWeb GitHub Module (Standard)
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_CAWeb_Module' ) ) {
	require_once dirname( __DIR__ ) . '/class-caweb-builder-element.php';
}

/**
 * CAWeb GitHub Module Class (Standard)
 */
class CAWeb_Module_GitHub extends ET_Builder_CAWeb_Module {
	/**
	 * Module Slug Name
	 *
	 * @var string Module slug name.
	 */
	public $slug = 'et_pb_ca_github';
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
		$this->name = esc_html__( 'GitHub', 'et_builder' );

		$this->fields_defaults = array(
			'per_page'  => array( 100, 'add_default_setting' ),
			'repo_type' => array( 'all', 'add_default_setting' ),
		);

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
					'email' => esc_html__( 'Request Access Email', 'et_builder' ),
					'text'  => array(
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
			'per_page' => array(
				'label'       => esc_html__( 'Maximum # of results', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter amount to display. Default is 100.', 'et_builder' ),
				'default'     => 100,
				'tab_slug'    => 'general',
				'toggle_slug' => 'style',
			),
			'repo_type' => array(
				'label'           => esc_html__( 'Repository Type', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'all'     => esc_html__( 'All', 'et_builder' ),
					'public'  => esc_html__( 'Public', 'et_builder' ),
					'private' => esc_html__( 'Private', 'et_builder' ),
					'forks'   => esc_html__( 'Forks', 'et_builder' ),
				),
				'default'         => 'all',
				'description'     => 'Choose repository type you wish to display.',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'access_token' => array(
				'label'       => esc_html__( 'Personal Access Token', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This is required for Private Repositories to display.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'style',
				'show_if_not' => array( 'repo_type' => 'public' ),
			),
			'request_email' => array(
				'label'       => esc_html__( 'Code Request Email', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This is the administrators email that will receive all emails requesting access to private repositories.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'style',
				'show_if_not' => array( 'repo_type' => 'public' ),
			),
			'title' => array(
				'label'       => esc_html__( 'Title', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter a title for the list.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'header',
			),
			'username' => array(
				'label'       => esc_html__( 'Username', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter GitHub Username.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'body',
			),
			'increase_rate_limit' => array(
				'label'           => esc_html__( 'Increase Rate Limit', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'description'     => et_get_safe_localization( sprintf( 'Increase the maximum number of requests users are permitted to make per hour.<a href="%1$s" target="_blank" title="Rate Limiting">Rate Limiting</a>', esc_url( 'https://developer.github.com/v3/#rate-limiting' ) ) ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
			'client_id' => array(
				'label'       => esc_html__( 'Client ID', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter GitHub Client ID.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'body',
				'show_if'     => array( 'increase_rate_limit' => 'on' ),
			),
			'client_secret' => array(
				'label'       => esc_html__( 'Client Secret', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Enter GitHub Client Secret.', 'et_builder' ),
				'tab_slug'    => 'general',
				'toggle_slug' => 'body',
				'show_if'     => array( 'increase_rate_limit' => 'on' ),
			),
			'definitions' => array(
				'label'           => esc_html__( 'Definitions', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'name'       => esc_html__( 'Project Title', 'et_builder' ),
					'url'        => esc_html__( 'Add Link to repositories (Public Repositories Only)', 'et_builder' ),
					'desc'       => esc_html__( 'Description', 'et_builder' ),
					'fork'       => esc_html__( 'Fork', 'et_builder' ),
					'created_at' => esc_html__( 'Creation Date', 'et_builder' ),
					'updated_at' => esc_html__( 'Updated Date', 'et_builder' ),
					'language'   => esc_html__( 'Language', 'et_builder' ),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'body',
			),
		);

		$design_fields = array(
			'title_size' => array(
				'label'             => esc_html__( 'Title Size', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => $this->caweb_get_text_sizes( array( 'p', 'h6' ) ),
				'default'           => 'h2',
				'description'       => esc_html__( 'Here you can choose the heading size for the list title.', 'et_builder' ),
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'style',
			),
			'email_body' => array(
				'label'           => esc_html__( 'Body', 'et_builder' ),
				'type'            => 'textarea',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization( sprintf( 'Here you can create the content that will be used within the body. Content must use proper URL Encoding (e.g. %%0A = line feed, %%91 = [, %%93 = ] )<a href="%1$s" target="_blank" title="URL Encoding Reference">URL Encoding Reference</a>', esc_url( 'https://www.w3schools.com/tags/ref_urlencode.asp' ) ) ),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'email',
				'show_if_not'     => array( 'repo_type' => 'public' ),
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
		$title               = $this->props['title'];
		$title_size          = $this->props['title_size'];
		$username            = $this->props['username'];
		$client_id           = $this->props['client_id'];
		$client_secret       = $this->props['client_secret'];
		$access_token        = $this->props['access_token'];
		$definitions         = $this->props['definitions'];
		$increase_rate_limit = $this->props['increase_rate_limit'];
		$request_email       = $this->props['request_email'];
		$email_body          = $this->props['email_body'];
		$per_page            = $this->props['per_page'];
		$repo_type           = $this->props['repo_type'];

		$definitions = explode( '|', $definitions );

		$class = sprintf( ' class="%1$s"', $this->module_classname( $render_slug ) );

		$content = $this->content;

		$output = '';

		if ( ! empty( $username ) ) {
			$url = sprintf(
				'https://api.github.com/orgs/%1$s/repos?per_page=%2$s%3$s&type=%4$s%5$s',
				$username,
				$per_page,
				'on' === $increase_rate_limit && ! empty( $client_id ) && ! empty( $client_secret ) ?
					sprintf( '&client_id=%1$s&client_secret=%2$s', $client_id, $client_secret ) : '',
				$repo_type,
				! empty( $access_token ) ? sprintf( '&access_token=%1$s', $access_token ) : ''
			);

			$repos = wp_remote_get( $url );
			$code  = wp_remote_retrieve_response_code( $repos );

			if ( 200 === $code ) {
				$repos     = json_decode( wp_remote_retrieve_body( $repos ) );
				$repo_list = '';

				foreach ( $repos as $r => $repo ) {
					$request_link = ( ! empty( $request_email ) && $repo->private ?
					sprintf(
						'<p>* This is a Private Repository <a class="btn btn-default" href="mailto:%1$s?subject=%2$s&body=%3$s">Request Access</a></p>',
						$request_email,
						sprintf( '%1$s Repository Access Request', $repo->full_name ),
						$email_body
					) : '' );

					if ( 'on' === $definitions[0] ) {
						if ( 'on' !== $definitions[1] || $repo->private ) {
							$name = sprintf( '<h3>%1$s</h3>', $repo->name );
						} elseif ( 'on' === $definitions[1] ) {
							$name = sprintf(
								'<h3><a href="%1$s" target="blank">%2$s</a></h3>',
								$repo->html_url,
								$repo->name
							);
						}
					}

					$desc = ( 'on' === $definitions[2] && ! empty( $repo->description ) ?
										sprintf( '<p>Project Description: %1$s</p>', $repo->description ) : '' );

					$fork = ( 'on' === $definitions[3] ?
							sprintf( '<p>Project forked by another organization: %1$s</p>', ( empty( $repo->fork ) ? 'False' : 'True' ) ) :
									'' );

					$created_at = ( 'on' === $definitions[4] ?
												sprintf( '<p>Created on: %1$s</p>', gmdate( 'm/d/Y', strtotime( $repo->created_at ) ) ) : '' );

					$updated_at = ( 'on' === $definitions[5] ?
												sprintf( '<p>Updated on: %1$s</p>', gmdate( 'm/d/Y', strtotime( $repo->updated_at ) ) ) : '' );

					$language = ( 'on' === $definitions[6] && ! empty( $repo->language ) ?
											sprintf( '<p>Language: %1$s</p>', $repo->language ) : '' );

					$repo_list .= sprintf(
						'<li>%1$s%2$s%3$s%4$s%5$s%6$s%7$s<hr></li>',
						( ! empty( $name ) ? $name : '' ),
						$desc,
						$fork,
						$created_at,
						$updated_at,
						$language,
						( ! empty( $request_link ) ? $request_link : '' )
					);
				}
				$output = sprintf( '<ul class="pl-0">%1$s</ul>', $repo_list );
			} else {
				$output = '<strong>No GitHub Repository Found</strong>';
			}
		}

		$output = sprintf(
			'<div%1$s%2$s>%3$s%4$s</div>',
			$this->module_id(),
			$class,
			! empty( $title ) ? sprintf( '<%1$s>%2$s</%1$s>', $title_size, $title ) : '',
			$output
		);

		return $output;
	}
}
new CAWeb_Module_GitHub();


