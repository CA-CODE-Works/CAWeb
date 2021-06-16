// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Section_Primary extends CAWEeb_Component {

  static slug = 'et_pb_ca_section_primary';

  renderHeader(featured_image_button = 'on' ){
	var heading_size = this.props.heading_size;
	var heading_text_color = this.props.heading_text_color;
	var heading_style = undefined !== heading_text_color ? { 'color': heading_text_color } : {};
	var heading_class = "off" === featured_image_button ? 'text-' + this.props.heading_align : '';

	switch( heading_size){
		case 'h1':
		default:
			return(
				<h1 style={heading_style} className={heading_class}>
					{this.props.section_heading}
				</h1>
			);
		case 'h2':
			return(
				<h2 style={heading_style} className={heading_class}>
					{this.props.section_heading}
				</h2>
			);
		case 'h3':
			return(
				<h3 style={heading_style} className={heading_class}>
					{this.props.section_heading}
				</h3>
			);
		case 'h4':
			return(
				<h4 style={heading_style} className={heading_class}>
					{this.props.section_heading}
				</h4>
			);
		case 'h5':
			return(
				<h5 style={heading_style} className={heading_class}>
					{this.props.section_heading}
				</h5>
			);
	}

  }

  renderMoreButton(){
	var section_link = this.props.section_link;
	var section_heading = this.props.section_heading;

	if( "" !== section_link ){
		return(
			<div>
				<a href={section_link} class="btn btn-default" target="_blank">More Information<span class="sr-only">More information about {section_heading}</span></a>
			</div>
		);
	}

  }

  renderFeatureImage(){
 	var slide_image_button = this.props.slide_image_button;
    var image_pos = this.props.left_right_button;

    var classes = "on" === slide_image_button ? ' animate-fadeInLeft' : '';
    classes += "on" === image_pos ? ' pull-right pr-0' : ' pull-left pl-0';

	// caweb_get_attachment_post_meta need to figure away to run WP Functions
	// alt_text = caweb_get_attachment_post_meta($section_image, '_wp_attachment_image_alt');
	
	var section_image = <img src={this.props.section_image} class="img-responsive w-100" alt=""/>;

    return (
		<div className={"col-md-4 col-md-offset-0" + classes}>
			{section_image}
		</div>
	);
  }

  render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_section_primary et_pb_module section " + classes;
        var featured_image_button = this.props.featured_image_button;
        var show_more_button = this.props.show_more_button;

		var section_background_color = "" !== this.props.section_background_color ? {'background' : this.props.section_background_color} : {'background' : 'inherit'};
		var header = this.renderHeader(featured_image_button);

		var display_button = "on" === show_more_button ? this.renderMoreButton() : '';

		if ("on" === featured_image_button) {
			return(
				<Fragment>
					<div id={moduleID} className={classList} style={section_background_color}>
						{this.renderFeatureImage()}
						<div class="col-md-15" >
							{header}
							{this.props.content()}
							{display_button}
						</div>
					</div>
				</Fragment>
			);

		}else{
			return(
				<Fragment>
					<div>
						{header}
						{this.props.content()}
						{display_button}
					</div>
				</Fragment>
			);
		}
  }
}

export default CAWeb_Module_Section_Primary;
