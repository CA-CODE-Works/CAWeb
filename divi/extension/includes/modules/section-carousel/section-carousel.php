<?php

if( ! class_exists('ET_Builder_CAWeb_Module') ){
    require_once( dirname(__DIR__) . '/class-caweb-builder-element.php');
}

/*
Divi Icon Field Names
make sure the field name is one of the following:
'font_icon', 'button_one_icon', 'button_two_icon',  'button_icon'
 */

// Standard Version
class CAWeb_Module_Section_Carousel extends ET_Builder_CAWeb_Module {
    public $slug = 'et_pb_ca_section_carousel';
    public $vb_support = 'on';

    function init() {
        $this->name = esc_html__('Section - Carousel', 'et_builder');
        
        $this->child_slug      = 'et_pb_ca_section_carousel_slide';
        $this->child_item_text = esc_html__('Slide', 'et_builder');
        
        $this->main_css_element = '%%order_class%%';

        $this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                    'style'  => esc_html__('Style', 'et_builder'),
                    'panel' => esc_html__('Panel', 'et_builder'),
                ),
            ),
            'advanced' => array(
                'toggles' => array(
                    'style'  => esc_html__('Style', 'et_builder'),
                    'text' => array(
                        'title'    => esc_html__('Text', 'et_builder'),
                        'priority' => 49,
                    ),
                ),
            ),
        );

        // Custom handler: Output JS for editor preview in page footer.
        add_action('wp_footer', array($this, 'carousel_fix'), 20);
    }

    function get_fields() {
        $general_fields = array(
			'carousel_style' => array(
				'label'           => esc_html__( 'Style', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'content_fit' => esc_html__( 'Content Fit', 'et_builder' ),
					'image_fit'   => esc_html__( 'Image Fit', 'et_builder' ),
					'media'       => esc_html__( 'Media', 'et_builder' ),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'slide_amount' => array(
				'label'           => esc_html__( 'Viewable Display Amount', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the amount of slides to display at one time.', 'et_builder' ),
				'default'         => 4,
				'show_if'         => array( 'carousel_style' => 'media' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'in_panel' => array(
				'label'           => esc_html__( 'Display in Panel', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'carousel_style' => 'media' ),
				'description'     => 'Choose whether to display this carousel inside of a panel',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'panel_title' => array(
				'label'           => esc_html__( 'Heading', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a Heading Title.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_layout' => array(
				'label'             => esc_html__( 'Style', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'default'            => esc_html__( 'Default', 'et_builder' ),
					'standout'           => esc_html__( 'Standout', 'et_builder' ),
					'standout highlight' => esc_html__( 'Standout Highlight', 'et_builder' ),
					'overstated'         => esc_html__( 'Overstated', 'et_builder' ),
					'understated'        => esc_html__( 'Understated', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose the style of panel to display', 'et_builder' ),
				'show_if'           => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'default' => 'default',
				'tab_slug'          => 'general',
				'toggle_slug'       => 'panel',
			),
			'panel_show_button' => array(
				'label'           => esc_html__( 'Read More Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'description'     => esc_html__( 'Here you can select to display a button.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_text' => array(
				'label'           => esc_html__( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the Text for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_link' => array(
				'label'           => esc_html__( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the URL for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
		);

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

    function before_render() {
        global $et_pb_ca_section_carousel_style;

        $et_pb_ca_section_carousel_style = $this->props['carousel_style'];
    }

    function render($unprocessed_props, $content = null, $render_slug) {
        $carousel_style = $this->props['carousel_style'];
		$in_panel = $this->props['in_panel'];
		$panel_layout = $this->props['panel_layout'];
		
		$section_bg_color = $this->props['section_background_color'];

		$content = $this->content;

		$section_bg_color = ! empty($section_bg_color) ? sprintf(' style="background: %1$s;" ', $section_bg_color) : '';

		if ( 'media' === $carousel_style && 'on' === $in_panel ) {
			$this->add_classname( 'panel' );
			$this->add_classname( sprintf( 'panel-%1$s', $panel_layout ) );
			$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

			
			$output  = sprintf('<div%1$s%2$s>%3$s</div></div>', $this->module_id(), $class, $this->renderPanelCarousel($section_bg_color));
		} else {
			$this->add_classname($carousel_style);
			$this->add_classname('section');
			$class = sprintf(' class="%1$s" ', $this->module_classname($render_slug));

			$output = sprintf('<div%1$s%2$s%3$s>%4$s</div>', $this->module_id(), $class, $section_bg_color, $this->renderCarousel($carousel_style));
		}

        return $output;
	}

	function renderCarousel($style){
		$style = in_array( $style, array( "media", "content") ) ? $style : "content";

		return sprintf('<div class="carousel carousel-%1$s owl-carousel">%2$s</div>', $style, $this->content);
	}

	function renderPanelCarousel($section_bg_color){
		$panel_title = $this->props['panel_title'];
		$panel_show_button = $this->props['panel_show_button'];
		$panel_button_text = $this->props['panel_button_text'];
		$panel_button_link = $this->props['panel_button_link'];

		$display_title = '';
		$display_button = '';
		
		if( "on" == $panel_show_button && ! empty($panel_button_link) ){
			$display_button = sprintf('<div class="options"><a href="%1$s" class="btn btn-default" target="_blank">%2$s</a></div>', 
			esc_url($panel_button_link), ! empty($panel_button_text) ? $panel_button_text : 'Read More');
		} 

		if( ! empty( $panel_title ) ){
			$display_title = sprintf('<div class="panel-heading"><h4>%1$s</h4>%2$s</div>', $panel_title, $display_button);
		}

		return sprintf('%1$s<div class="panel-body"%2$s>%3$s</div>', $display_title, $section_bg_color, $this->renderCarousel('media'));
	}

    // This is a non-standard function. It outputs JS code to change items amount for carousel-media.
    function carousel_fix() {
		global $post;
        $con = is_object($post) ? $post->post_content : $post['post_content']; 
        $carousels = ! is_404() && ! empty($con) ? json_encode(caweb_get_shortcode_from_content($con, $this->slug, true)) : array(); ?>
		
		<script>
        $ = jQuery.noConflict();

       var media_carousels = <?php print_r($carousels); ?>;

        media_carousels.forEach(function(element, index) {
			var carousel = $('.<?php print $this->slug; ?>_' + index );
			if( $(carousel).hasClass('media') || $(carousel).hasClass('panel') ){
				$(carousel).find('.carousel-media').owlCarousel({
          			responsive : true,
					responsive: {
				        0: {
				        	items: 1,
							nav: true
				        },
				        400: {
				        	items: 1,
							nav: true
				        },
				        768: {
				            items: undefined == element.slide_amount ? 4 : element.slide_amount,
				            nav: true
				        },
				    },
          			margin : 10,
          			nav : true,
          			dots : false,
          			navText: [
          				'<span class="ca-gov-icon-arrow-prev" aria-hidden="true"></span>',
          				'<span class="ca-gov-icon-arrow-next" aria-hidden="true"></span>'
        			],
		        });
			}
		})
		</script>
	<?php
    }
}
new CAWeb_Module_Section_Carousel;

// Fullwidth Version
class CAWeb_Module_Fullwidth_Section_Carousel extends ET_Builder_CAWeb_Module {
    public $slug = 'et_pb_ca_fullwidth_section_carousel';
    public $vb_support = 'on';

    function init() {
        $this->name = esc_html__('Section - Carousel', 'et_builder');
        
        $this->child_slug      = 'et_pb_ca_fullwidth_section_carousel_slide';
        $this->child_item_text = esc_html__('Slide', 'et_builder');
        
        $this->main_css_element = '%%order_class%%';

        $this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                    'style'  => esc_html__('Style', 'et_builder'),
                    'panel' => esc_html__('Panel', 'et_builder'),
                ),
            ),
            'advanced' => array(
                'toggles' => array(
                    'style'  => esc_html__('Style', 'et_builder'),
                    'text' => array(
                        'title'    => esc_html__('Text', 'et_builder'),
                        'priority' => 49,
                    ),
                ),
            ),
        );

        // Custom handler: Output JS for editor preview in page footer.
        add_action('wp_footer', array($this, 'carousel_fix'), 20);
    }

    function get_fields() {
        $general_fields = array(
			'carousel_style' => array(
				'label'           => esc_html__( 'Style', 'et_builder' ),
				'type'            => 'select',
				'option_category' => 'configuration',
				'options'         => array(
					'content_fit' => esc_html__( 'Content Fit', 'et_builder' ),
					'image_fit'   => esc_html__( 'Image Fit', 'et_builder' ),
					'media'       => esc_html__( 'Media', 'et_builder' ),
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'slide_amount' => array(
				'label'           => esc_html__( 'Viewable Display Amount', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__('Here you can enter the amount of slides to display at one time.', 'et_builder'),
				'default' => 4,
				'show_if'   	=> array('carousel_style' => 'media'),
				'tab_slug'			=> 'general',
				'toggle_slug'			=> 'style',
			),
			'in_panel' => array(
				'label'           => esc_html__( 'Display in Panel', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'default'         => 'off',
				'show_if'         => array( 'carousel_style' => 'media' ),
				'description'     => 'Choose whether to display this carousel inside of a panel',
				'tab_slug'        => 'general',
				'toggle_slug'     => 'style',
			),
			'panel_title' => array(
				'label'           => esc_html__( 'Heading', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter a Heading Title.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_layout' => array(
				'label'             => esc_html__( 'Style', 'et_builder' ),
				'type'              => 'select',
				'option_category'   => 'configuration',
				'options'           => array(
					'default'            => esc_html__( 'Default', 'et_builder' ),
					'standout'           => esc_html__( 'Standout', 'et_builder' ),
					'standout highlight' => esc_html__( 'Standout Highlight', 'et_builder' ),
					'overstated'         => esc_html__( 'Overstated', 'et_builder' ),
					'understated'        => esc_html__( 'Understated', 'et_builder' ),
				),
				'description'       => esc_html__( 'Here you can choose the style of panel to display', 'et_builder' ),
				'show_if'           => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'default' => 'default',
				'tab_slug'          => 'general',
				'toggle_slug'       => 'panel',
			),
			'panel_show_button' => array(
				'label'           => esc_html__( 'Read More Button', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'show_if'         => array(
					'carousel_style' => 'media',
					'in_panel'       => 'on',
				),
				'description'     => esc_html__( 'Here you can select to display a button.', 'et_builder' ),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_text' => array(
				'label'           => esc_html__( 'Button Text', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the Text for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
			'panel_button_link' => array(
				'label'           => esc_html__( 'Button URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Here you can enter the URL for the button.', 'et_builder' ),
				'show_if'         => array(
					'carousel_style'    => 'media',
					'in_panel'          => 'on',
					'panel_show_button' => 'on',
				),
				'tab_slug'        => 'general',
				'toggle_slug'     => 'panel',
			),
		);

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

    function before_render() {
        global $et_pb_ca_section_carousel_style;

        $et_pb_ca_section_carousel_style = $this->props['carousel_style'];
    }

    function render($unprocessed_props, $content = null, $render_slug) {
        $carousel_style = $this->props['carousel_style'];
		$in_panel = $this->props['in_panel'];
		$panel_layout = $this->props['panel_layout'];
		
		$section_bg_color = $this->props['section_background_color'];

		$content = $this->content;

		$section_bg_color = ! empty($section_bg_color) ? sprintf(' style="background: %1$s;" ', $section_bg_color) : '';

		if ( 'media' === $carousel_style && 'on' === $in_panel ) {
			$this->add_classname( 'panel' );
			$this->add_classname( sprintf( 'panel-%1$s', $panel_layout ) );
			$class = sprintf( ' class="%1$s" ', $this->module_classname( $render_slug ) );

			
			$output  = sprintf('<div%1$s%2$s>%3$s</div></div>', $this->module_id(), $class, $this->renderPanelCarousel($section_bg_color));
		} else {
			$this->add_classname($carousel_style);
			$this->add_classname('section');
			$class = sprintf(' class="%1$s" ', $this->module_classname($render_slug));

			$output = sprintf('<div%1$s%2$s%3$s>%4$s</div>', $this->module_id(), $class, $section_bg_color, $this->renderCarousel($carousel_style));
		}

        return $output;
	}

	function renderCarousel($style){
		$style = in_array( $style, array( "media", "content") ) ? $style : "content";

		return sprintf('<div class="carousel carousel-%1$s owl-carousel">%2$s</div>', $style, $this->content);
	}

	function renderPanelCarousel($section_bg_color){
		$panel_title = $this->props['panel_title'];
		$panel_show_button = $this->props['panel_show_button'];
		$panel_button_text = $this->props['panel_button_text'];
		$panel_button_link = $this->props['panel_button_link'];

		$display_title = '';
		$display_button = '';
		
		if( "on" == $panel_show_button && ! empty($panel_button_link) ){
			$display_button = sprintf('<div class="options"><a href="%1$s" class="btn btn-default" target="_blank">%2$s</a></div>', 
			esc_url($panel_button_link), ! empty($panel_button_text) ? $panel_button_text : 'Read More');
		} 

		if( ! empty( $panel_title ) ){
			$display_title = sprintf('<div class="panel-heading"><h4>%1$s</h4>%2$s</div>', $panel_title, $display_button);
		}

		return sprintf('%1$s<div class="panel-body"%2$s>%3$s</div>', $display_title, $section_bg_color, $this->renderCarousel('media'));
	}

    // This is a non-standard function. It outputs JS code to change items amount for carousel-media.
    function carousel_fix() {
		global $post;
        $con = is_object($post) ? $post->post_content : $post['post_content']; 
        $carousels = ! is_404() && ! empty($con) ? json_encode(caweb_get_shortcode_from_content($con, $this->slug, true)) : array(); ?>
		
		<script>
        $ = jQuery.noConflict();

       var media_carousels = <?php print_r($carousels); ?>;

        media_carousels.forEach(function(element, index) {
			var carousel = $('.<?php print $this->slug; ?>_' + index );
			if( $(carousel).hasClass('media') || $(carousel).hasClass('panel') ){
				$(carousel).find('.carousel-media').owlCarousel({
          			responsive : true,
					responsive: {
				        0: {
				        	items: 1,
							nav: true
				        },
				        400: {
				        	items: 1,
							nav: true
				        },
				        768: {
				            items: undefined == element.slide_amount ? 4 : element.slide_amount,
				            nav: true
				        },
				    },
          			margin : 10,
          			nav : true,
          			dots : false,
          			navText: [
          				'<span class="ca-gov-icon-arrow-prev" aria-hidden="true"></span>',
          				'<span class="ca-gov-icon-arrow-next" aria-hidden="true"></span>'
        			],
		        });
			}
		})
		</script>
	<?php
    }
}
new CAWeb_Module_Fullwidth_Section_Carousel;

?>
