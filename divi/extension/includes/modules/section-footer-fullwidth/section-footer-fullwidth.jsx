// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Fullwidth_Section_Footer extends CAWEeb_Component {

  static slug = 'et_pb_ca_fullwidth_section_footer';

  render() {
	var moduleId = "" !== this.props.module_id ? this.props.module_id : ''
	var classes = undefined !== this.props.module_class ? this.props.module_class : '';
	var classList = "et_pb_ca_fullwidth_section_footer et_pb_module section p-3 " + classes;

	var section_bg_color = "" !== this.props.section_background_color ? {'background' : this.props.section_background_color} : {'background' : 'inherit'};

	return (
		<Fragment>
			<div  id={moduleId} className={classList} style={section_bg_color}>
				{
					React.Children.map(this.props.content, group => {
						return  React.cloneElement(group);
					})
				}
			</div>
	 </Fragment>
	);

}
}

export default CAWeb_Module_Fullwidth_Section_Footer;
