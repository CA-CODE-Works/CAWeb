<?php
/**
 * CAWeb Customizer Alert Banner Control
 *
 * @link https://developer.wordpress.org/reference/classes/WP_Customize_Control/
 * @package CAWeb
 */

if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * CAWeb_Customize_Alert_Banner_Control
	 */
	class CAWeb_Customize_Alert_Banner_Control extends WP_Customize_Control {
		/**
		 * Member Variable
		 *
		 * @var bool $is_expanded Whether alert banner is expanded.
		 */
		public $is_expanded = false;

		/**
		 * Member Variable
		 *
		 * @var string $header Alert Banner Header.
		 */
		public $header = 'Label';

		/**
		 * Member Variable
		 *
		 * @var string $message Alert Banner Message.
		 */
		public $message = '';

		/**
		 * Member Variable
		 *
		 * @var string Whether Alert Banner is display on all pages or just home page.
		 */
		public $display_on = 'home';

		/**
		 * Member Variable
		 *
		 * @var string $banner_color Alert Banner background color.
		 */
		public $banner_color = '#FFFFFF';

		/**
		 * Member Variable
		 *
		 * @var bool $read_more Whether the read more button is enabled.
		 */
		public $read_more = true;

		/**
		 * Member Variable
		 *
		 * @var string Read more button text.
		 */
		public $read_more_text = '';

		/**
		 * Member Variable
		 *
		 * @var string $read_more_url Read more button URL.
		 */
		public $read_more_url = '';

		/**
		 * Member Variable
		 *
		 * @var bool $read_more_target Whether read more opens in new tab or not.
		 */
		public $read_more_target = true;

		/**
		 * Member Variable
		 *
		 * @var string $icon Alert Banner Icon.
		 */
		public $icon = 'important';

		/**
		 * Member Variable
		 *
		 * @var undefined $active Whether Alert Banner is active.
		 */
		public $active = null;

		/**
		 * Member Variable
		 *
		 * @var undefined $alert_id Alert Banner Id
		 */
		public $alert_id = -1;

		/**
		 * __construct
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    {
		 *     Optional. Array of properties for the new Control object. Default empty array.
		 *
		 *     @type int                  $instance_number Order in which this instance was created in relation
		 *                                                 to other instances.
		 *     @type WP_Customize_Manager $manager         Customizer bootstrap instance.
		 *     @type string               $id              Control ID.
		 *     @type array                $settings        All settings tied to the control. If undefined, `$id` will
		 *                                                 be used.
		 *     @type string               $setting         The primary setting for the control (if there is one).
		 *                                                 Default 'default'.
		 *     @type string               $capability      Capability required to use this control. Normally this is empty
		 *                                                 and the capability is derived from `$settings`.
		 *     @type int                  $priority        Order priority to load the control. Default 10.
		 *     @type string               $section         Section the control belongs to. Default empty.
		 *     @type string               $label           Label for the control. Default empty.
		 *     @type string               $description     Description for the control. Default empty.
		 *     @type array                $choices         List of choices for 'radio' or 'select' type controls, where
		 *                                                 values are the keys, and labels are the values.
		 *                                                 Default empty array.
		 *     @type array                $input_attrs     List of custom input attributes for control output, where
		 *                                                 attribute names are the keys and values are the values. Not
		 *                                                 used for 'checkbox', 'radio', 'select', 'textarea', or
		 *                                                 'dropdown-pages' control types. Default empty array.
		 *     @type bool                 $allow_addition  Show UI for adding new content, currently only used for the
		 *                                                 dropdown-pages control. Default false.
		 *     @type array                $json            Deprecated. Use WP_Customize_Control::json() instead.
		 *     @type string               $type            Control type. Core controls include 'text', 'checkbox',
		 *                                                 'textarea', 'radio', 'select', and 'dropdown-pages'. Additional
		 *                                                 input types such as 'email', 'url', 'number', 'hidden', and
		 *                                                 'date' are supported implicitly. Default 'text'.
		 *     @type callable             $active_callback Active callback.
		 * }
		 * @return void
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );

			$this->alert_id = str_replace( 'caweb_alert_banner_', '', $this->id );
		}

		/**
		 * Render an Alert Banner.
		 *
		 * @link https://developer.wordpress.org/reference/classes/wp_customize_control/render_content/
		 * @return void
		 */
		public function render_content() {
			$collapse      = $this->is_expanded ? '' : ' show';
			$collapse_icon = $this->is_expanded ? ' dashicons-arrow-right' : '';

			$main_input    = sprintf( '<input type="hidden" name="%1$s" id="%1$s"/>', $this->id );
			$add_alert     = sprintf( '<a data-target="caweb-toggle-alert-%1$s" class="text-decoration-none text-reset caweb-toggle-alert">%2$s <span class="text-secondary align-baseline dashicons dashicons-arrow-down%3$s"></span></a>', $this->alert_id, $this->header, $collapse_icon );
			$alert_status  = sprintf(
				'<input type="checkbox" name="alert-status-%1$s"%2$s%3$s>',
				$this->alert_id,
				null !== $this->active ? ' data-toggle="toggle" data-onstyle="success" data-size="sm"' : '',
				null === $this->active || in_array( $this->active, array( 'on', 'active' ), true ) ? ' checked="checked"' : ''
			);
			$remove_button = '<button class="caweb-remove-alert btn btn-danger btn-sm">Remove</button>';
			$main_options  = "$main_input$add_alert$alert_status$remove_button";

			$main_div = sprintf(
				'<div id="caweb-toggle-alert-%1$s" class="collapse%2$s">%3$s%4$s%5$s%6$s%7$s%8$s</div>',
				$this->alert_id,
				$collapse,
				$this->render_alert_banner_header(),
				$this->render_alert_banner_message(),
				$this->render_alert_banner_display_on(),
				$this->render_alert_banner_color(),
				$this->render_alert_banner_read_more(),
				$this->render_alert_banner_icon()
			);

			printf( '%1$s%2$s', $main_options, $main_div );
		}

		/**
		 * Render Alert Banner Header
		 *
		 * @return string
		 */
		private function render_alert_banner_header() {
			$value = ! empty( $this->header ) ? sprintf( ' value="%1$s"', $this->header ) : '';
			return sprintf( '<div id="customize-control-alert-header-%1$s"><label for="alert-header-%1$s" class="customize-control-title">Title</label><input type="text" placeholder="Label" id="alert-header-%1$s" name="alert-header-%1$s"%2$s/></div>', $this->alert_id, $value );
		}

		/**
		 * Render Alert Banner Message field
		 *
		 * @return string
		 */
		private function render_alert_banner_message() {
			return sprintf( '<div><label for="alert-message-%1$s" class="customize-control-title">Message</label><textarea id="alert-message-%1$s" name="alert-message-%1$s" class="w-100">%2$s</textarea></div>', $this->alert_id, $this->message );
		}

		/**
		 * Render Alert Banner Display On field
		 *
		 * @return string
		 */
		private function render_alert_banner_display_on() {
			$home_page = sprintf( '<input id="alert-display-home-%1$s" name="alert-display-%1$s" type="radio" value="home"%2$s/><label for="">Home Page Only</label>', $this->alert_id, 'home' === $this->display_on ? ' checked="checked"' : '' );
			$all_pages = sprintf( '<input id="alert-display-all-%1$s" name="alert-display-%1$s" type="radio" value="all"%2$s/><label for="">All Pages</label>', $this->alert_id, 'all' === $this->display_on ? ' checked="checked"' : '' );

			return sprintf( '<div role="radiogroup"><span class="customize-control-title">Display On</span>%1$s%2$s</div>', $home_page, $all_pages );
		}

		/**
		 * Render Alert Banner Color field
		 *
		 * @return string
		 */
		private function render_alert_banner_color() {
			return sprintf( '<div><label for="alert-banner-color-%1$s" class="customize-control-title">Banner Color</label><input class="w-25" type="color" id="alert-banner-color-%1$s" name="alert-banner-color-%1$s" value="%2$s"/></div>', $this->alert_id, $this->banner_color );
		}

		/**
		 * Render Alert Banner Read More Button field
		 *
		 * @return string
		 */
		private function render_alert_banner_read_more() {
			$read_more       = sprintf( '<div><a href="#alert-read-more-options-%1$s" data-toggle="collapse"><input id="alert-read-more-%1$s" name="alert-read-more-%1$s" type="checkbox"%2$s/></a><label class="d-inline customize-control-title" for="alert-read-more-%1$s">Read More Button</label></div>', $this->alert_id, $this->read_more ? ' checked="checked"' : '' );
			$read_more_text  = sprintf( '<div><label for="alert-read-more-text-%1$s" class="customize-control-title">Read More Button Text</label><input type="text" id="alert-read-more-text-%1$s" name="alert-read-more-text-%1$s" value="%2$s" /></div>', $this->alert_id, $this->read_more_text );
			$read_more_url   = sprintf( '<div><label for="alert-read-more-url-%1$s" class="customize-control-title">Read More Button URL</label><input type="text" id="alert-read-more-url-%1$s" name="alert-read-more-url-%1$s" value="%2$s" /></div>', $this->alert_id, $this->read_more_url );
			$open_in_new_tab = sprintf( '<div><input type="checkbox" id="alert-read-more-target-%1$s" name="alert-read-more-target-%1$s"%2$s/><label for="alert-read-more-target-%1$s" class="d-inline customize-control-title">Open Link in New Tab</label></div>', $this->alert_id, $this->read_more_target ? ' checked="checked"' : '' );

			return sprintf(
				'%1$s<div id="alert-read-more-options-%2$s" class="collapse%3$s">%4$s%5$s%6$s</div>',
				$read_more,
				$this->alert_id,
				$this->read_more ? ' show' : '',
				$read_more_text,
				$read_more_url,
				$open_in_new_tab
			);
		}

		/**
		 * Renders Alert Banner Icon Menu field
		 *
		 * @return string
		 */
		private function render_alert_banner_icon() {
			return caweb_icon_menu(
				array(
					'select'       => $this->icon,
					'name'         => 'alert-icon-' . $this->alert_id,
					'header'       => 'Icon',
					'header_class' => array( 'customize-control-title', 'd-inline' ),
				)
			);
		}
	}
}

