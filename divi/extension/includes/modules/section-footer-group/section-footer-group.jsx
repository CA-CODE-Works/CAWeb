// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Footer_Group extends CAWEeb_Component {

  static slug = 'et_pb_ca_section_footer_group';

	renderGroupList(){
		var group_icon = this.props.font_icon;
		var group_icon_button = this.props.group_icon_button;
		var text_color= this.props.text_color;
		var display_link_as_button= this.props.display_link_as_button;

		// List Color Styles
		text_color = "" !== text_color ? {'color' : text_color} : '';
		
		var opts = {'padding-right' : '5px'};

		if( "" !== text_color ){
			//opts.push({'color' : text_color});
		}

		var icon = "on" === group_icon_button ? this.caweb_get_icon_span(group_icon, opts) : '';

		var link_as_button = "on" === display_link_as_button ? 'btn btn-default btn-xs' : '';

		var groupLinks = [];

		for (var i = 1; i <= 10; i++) {
			var group_link_show = this.props['group_link' + i + '_show'];
			var group_link_text = this.props['group_link_text' + i];
			var group_link_url = this.props['' + i ];
		
			if( "on" === group_link_show ){
				groupLinks.push(<li class="mb-2">
					<a href={group_link_url} className={link_as_button} style={text_color} target="_blank">
					{icon}{group_link_text}</a></li>);
			}
		}

		return(
			<ul class="list-unstyled p-0">
				{groupLinks}
			</ul>
		);
		
	}
  	render() {
		var moduleId = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_section_footer_group et_pb_module quarter " + classes;

    	var heading_color = "" !== this.props.heading_color ? {'color' : this.props.heading_color} : {'color' : 'inherit'};

		var display_more_button = "on" === this.props.group_show_more_button ?
		<a href={this.props.group_url} class="btn btn-primary" target="_blank">Read More<span class="sr-only">Read More about {this.props.group_title}</span></a> : '';
				
		return (
			<Fragment>
				<div  id={moduleId} className={classList}>
					<h4 style={heading_color}>{this.props.group_title}</h4>
					{this.renderGroupList()}
					{display_more_button}
			</div>
		</Fragment>
	);
	
  }	
}

export default CAWeb_Module_Footer_Group;
