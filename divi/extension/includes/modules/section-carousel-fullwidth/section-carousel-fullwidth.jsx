// External Dependencies
import React, { Fragment } from 'react';
import OwlCarousel from 'react-owl-carousel';

import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Fullwidth_Section_Carousel extends CAWEeb_Component {

  static slug = 'et_pb_ca_fullwidth_section_carousel';

  renderCarousel( s ){
    s = "media" === s || "content" === s ? s : "content";

    if( "media" === s ){
      return (
        <OwlCarousel 
            className={"carousel-" + s}
            nav={true}
            dots={false}
            margin={10}
            items={this.props.slide_amount}
            loop
            navText={["<span class='ca-gov-icon-arrow-prev'></span>","<span class='ca-gov-icon-arrow-next'></span>"]}
          >
              {this.props.content}
          </OwlCarousel>
      );
    }else{
      return (
        <OwlCarousel className={"carousel-" + s}>
              {this.props.content}
          </OwlCarousel>
      );
    }
		
  }

  renderPanelCarousel(bgColor){
    var button_text = "" !== this.props.panel_button_text ? this.props.panel_button_text : 'Read More';

    var display_button = "";
    var display_title = "";
    
    if( "on" === this.props.panel_show_button && "" !== this.props.panel_button_link ){
			display_button = <div class="options"><a href={this.props.panel_button_link} class="btn btn-default" target="_blank">{button_text}</a></div>;
    }
    
    if ( "" !== this.props.panel_title ){
      display_title = <div class="panel-heading"><h4>{this.props.panel_title}</h4>{display_button}</div>;
    }
     
    return(
      <Fragment>
        {display_title}
        <div class="panel-body" style={bgColor}>
          {this.renderCarousel('media')}
        </div>
      </Fragment>
    );
  }

  render() {
		var moduleId = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_fullwidth_section_carousel et_pb_module " + classes;
    var carousel_style = this.props.carousel_style;
    var section_bg_color = "" !== this.props.section_background_color ? {'background' : this.props.section_background_color} : {'background' : 'inherit'};

    
    if ("media" === carousel_style && "on" === this.props.in_panel) {
        classList += "panel panel-" + this.props.panel_layout;
       
        return (
    			<Fragment>
            <div  id={moduleId} className={classList}>
            {this.renderPanelCarousel(section_bg_color)}
          </div>
        </Fragment>
    	);
      } else {
        classList += " section " + carousel_style;

        return (
    			<Fragment>
            <div  id={moduleId} className={classList} style={section_bg_color}>
              {this.renderCarousel(carousel_style)}
            </div>
         </Fragment>
    		);

    }

  }
}

export default CAWeb_Module_Fullwidth_Section_Carousel;
