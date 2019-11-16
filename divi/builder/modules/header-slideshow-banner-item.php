<?php
/*
Divi Icon Field Names
make sure the field name is one of the following:
'font_icon', 'button_one_icon', 'button_two_icon',  'button_icon'
 */

 if( ! class_exists('ET_Builder_CAWeb_Module') ){
    require_once( dirname(__DIR__) . '/class-caweb-builder-element.php');
}

class CAWeb_Module_Fullwidth_Header_Slideshow_Banner_Item extends ET_Builder_CAWeb_Module {
    public $slug = 'et_pb_ca_fullwidth_banner_item';
    public $vb_support = 'on';

    function init() {
        $this->name = esc_html__('FullWidth Banner Slide', 'et_builder');
        $this->type = 'child';
        $this->fullwidth       = true;
        $this->child_title_var = 'heading';
        $this->child_title_fallback_var = 'heading';

        $this->advanced_setting_title_text = esc_html__('New Slide', 'et_builder');
        $this->settings_text = esc_html__('Slide Settings', 'et_builder');
        $this->main_css_element = '%%order_class%%';

        $this->settings_modal_toggles = array(
            'general' => array(
                'toggles' => array(
                    'content'  => esc_html__('Content', 'et_builder'),
                    'image'    => esc_html__('Image', 'et_builder'),
                ),
            ),
            'advanced' => array(
                'toggles' => array(
                    'banner'  => esc_html__('Banner', 'et_builder'),
                ),
            ),
        );
    }
    function get_fields() {
        $general_fields = array(
            'display_banner_info' => array(
                'label'           => esc_html__('Banner Information', 'et_builder'),
                'type'            => 'yes_no_button',
                'option_category' => 'configuration',
                'options'         => array(
                    'on'  => esc_html__('Yes', 'et_builder'),
                    'off' => esc_html__('No', 'et_builder'),
                ),
                'affects' => array('heading', 'display_heading', 'button_text', 'button_link', 'banner_align'),
                'tab_slug' => 'general',
                'toggle_slug' => 'content',
            ),
            'heading' => array(
                'label' => esc_html__('Heading', 'et_builder'),
                'type' => 'text',
                'option_category' => 'basic_option',
                'description'     => esc_html__('Define the title text for your slide.', 'et_builder'),
                'show_if' => array('display_banner_info' => 'on'),
                'tab_slug' => 'general',
                'toggle_slug' => 'content',
            ),
            'display_heading' => array(
                'label'           => esc_html__('Display Heading', 'et_builder'),
                'type'            => 'yes_no_button',
                'options'         => array(
                    'on'  => esc_html__('Yes', 'et_builder'),
                    'off' => esc_html__('No', 'et_builder'),
                ),
                'option_category' => 'configuration',
                'description'       => esc_html__('This will toggle the heading on/off in the banner slide', 'et_builder'),
                'show_if' => array('display_banner_info' => 'on'),
                'tab_slug' => 'general',
                'toggle_slug'       => 'content',
            ),
            'button_text' => array(
                'label' => esc_html__('Button Text', 'et_builder'),
                'type'=> 'textarea',
                'option_category' => 'basic_option',
                'description' => esc_html__('Define the text for the slide button', 'et_builder'),
                'show_if' => array('display_banner_info' => 'on'),
                'tab_slug' => 'general',
                'toggle_slug'     => 'content',
            ),
            'button_link' => array(
                'label' => esc_html__('Button URL', 'et_builder'),
                'type' => 'text',
                'option_category' => 'basic_option',
                'description' => esc_html__('Input a destination URL for the slide button.', 'et_builder'),
                'default' => '#',
                'show_if' => array('display_banner_info' => 'on'),
                'tab_slug' => 'general',
                'toggle_slug'    => 'content',
            ),
            'background_image' => array(
                'label' => esc_html__('Background Image', 'et_builder'),
                'type' => 'upload',
                'option_category' => 'basic_option',
                'upload_button_text' => esc_attr__('Upload an image', 'et_builder'),
                'choose_text' => esc_attr__('Choose a Background Image', 'et_builder'),
                'update_text' => esc_attr__('Set As Background', 'et_builder'),
                'description' => esc_html__('If defined, this image will be used as the background for this module. To remove a background image, simply delete the URL from the settings field.', 'et_builder'),
                'tab_slug' => 'general',
                'toggle_slug' => 'image'
            ),
        );

        $design_fields = array(
            'banner_align' => array(
                'label'           => esc_html__('Banner Alignment', 'et_builder'),
                'type'            => 'select',
                'options'         => array(
                    'left'  => 'Left',
                    'center'  => 'Center',
                    'right'  => 'Right',
                ),
                'option_category' => 'configuration',
                'show_if' => array('display_banner_info' => 'on'),
                'description'       => esc_html__('Here you can choose the alignment for the banner information', 'et_builder'),
                'tab_slug' => 'advanced',
                'toggle_slug'       => 'banner',
            ),
        );

        $advanced_fields = array(
        );

        return array_merge($general_fields, $design_fields, $advanced_fields);
    }
    function render($unprocessed_props, $content = null, $render_slug) {
        $display_banner_info = $this->props['display_banner_info'];
        $heading = $this->props['heading'];
        $display_heading = $this->props['display_heading'];
        $button_text = $this->props['button_text'];
        $button_link = $this->props['button_link'];
        $background_image = $this->props['background_image'];
        $banner_align = $this->props['banner_align'];

        global $et_pb_fullwidth_header_slider_item_num;

        $et_pb_fullwidth_header_slider_item_num++;

        $this->add_classname('slide');
        $class = sprintf(' class="%1$s" ', $this->module_classname($render_slug));

        $button_link = ! empty($button_link) ? esc_url($button_link) : '';

        $link = ("on" == $display_banner_info ? sprintf('<a href="%1$s" target="_blank"><p class="slide-text %2$s"><span class="title" %3$s>%4$s<br /></span>%5$s</p></a>', $button_link, $banner_align, ("off" == $display_heading ? 'style="display:none;"' : ''), $heading, $button_text) : '');

        $output = sprintf('<div%1$s%2$s style="background-image:url(%3$s);">%4$s</div>', $this->module_id(), $class, $background_image, $link);

        return $output;
    }
}
new CAWeb_Module_Fullwidth_Header_Slideshow_Banner_Item;

?>