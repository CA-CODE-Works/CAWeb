// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

// Internal Dependencies
import './style.css';


class CAWeb_Module_Card extends CAWEeb_Component {

	static slug = 'et_pb_ca_card';

	renderImage() {
		if ('on' === this.props.show_image) {
			return (
				<img className="card-img-top img-responsive" src={this.props.featured_image} alt="Card Cap" />
			);
		}
	}

	renderHeader() {
		if ('on' === this.props.include_header) {
			var text_color = undefined !== this.props.text_color ? {'color' : this.props.text_color} :{'color' : 'inherit'} ;
			return (
				  <div className="card-header">
				  	<h4 class="card-title" style={text_color}>{this.props.title}</h4>
				  </div>
			);
		}
	}

	renderButton() {
		if ('on' === this.props.show_button) {
			var button_link = undefined !== this.props.button_link ? this.props.button_link : '';
			return (
				  <a href={button_link} className="btn btn-default">{this.props.button_text}</a>
			);
		}
	}

	renderFooter() {
		if ('on' === this.props.include_footer) {
			var footer_color = undefined !== this.props.footer_color ?  {'color' : this.props.footer_color} :{'color' : 'inherit'};
			return (
				  <div className="card-footer" style={footer_color}>{this.props.footer_text}</div>
			);
		}
	}

	render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : '';
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var card_layout = "custom" === this.props.card_layout ? 'default' : this.props.card_layout;
		var classList = "et_pb_ca_card et_pb_module card card-" + card_layout + classes;

		var card_color = undefined !== this.props.card_color && "custom" === this.props.card_layout ? {'background-color' : this.props.card_color} : {'background-color' : 'inherit'};

	    return (
			<Fragment>
	        	<div id={moduleID} className={classList}>
	        		{ this.renderImage() }
				    { this.renderHeader() }
				    <div className="card-block" style={card_color}>				    	
				        { this.props.content() }
				        { this.renderButton() }
				    </div>
				     { this.renderFooter() }
				</div>
	      	</Fragment>
	    );
	}

}

export default CAWeb_Module_Card;
