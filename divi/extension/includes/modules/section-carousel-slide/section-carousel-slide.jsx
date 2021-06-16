// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Section_Carousel_Slide extends CAWEeb_Component {

  static slug = 'et_pb_ca_section_carousel_slide';

  renderMediaSlide(){
    var slide_url = this.props.slide_url;
    var button = "";
    var slide = "";
    var slide_alt_text = this.props.slide_alt_text;

    if( "on" === this.props.slide_show_more_button && "" !== slide_url ){
      var button_class = "" === this.props.slide_desc ? " mt-3" : "";
      button = <div className={"details text-center" + button_class}><a href={slide_url} target="_blank">{this.props.slide_title}</a></div>;
    }

    var desc = "" !== this.props.slide_desc ? <div class="details mt-3">{this.props.slide_desc}</div> : '';

    if ("" !== this.props.slide_image ){
      if( "" === slide_alt_text ){
        // caweb_get_attachment_post_meta need to figure away to run WP Functions
        //slide_alt_text = caweb_get_attachment_post_meta($slide_image, '_wp_attachment_image_alt');
      }
       
      slide = <div class="preview-image"><img class="h-100" src={this.props.slide_image} alt={slide_alt_text} /></div>
     }

    return(
      <Fragment>
        <div>
        {slide}{desc}{button}
        </div>
      </Fragment>
    );
  }

  renderSlide(){
    var slide_url = this.props.slide_url;
    var slide_desc = this.props.slide_desc;
    var slide_title = "" !== this.props.slide_title ? <h2>{this.props.slide_title}</h2> : '';
    var slide_alt_text = this.props.slide_alt_text;

    var carousel_style = this.props.et_pb_ca_section_carousel_style;

    var button = "";

    if( "on" === this.props.slide_show_more_button && "" !== slide_url ){
      button = <a class="btn btn-primary" href={slide_url} target="_blank">{slide_title}<span class="sr-only">More information about {slide_title}</span></a>;
    }

    var hide = "" === slide_title && "" === slide_desc && "" === button ? ' hidden' : '';

    if( "" === slide_alt_text ){
      // caweb_get_attachment_post_meta need to figure away to run WP Functions
      //slide_alt_text = caweb_get_attachment_post_meta($slide_image, '_wp_attachment_image_alt');
    }

    var image_fit = "image_fit" === carousel_style ? <img src={this.props.slide_image} alt={slide_alt_text}/> : '';

    var contentClass = "content_fit" === carousel_style ? hide : '';

    var content = <div class={"content" + contentClass}>{slide_title}{slide_desc}{button}</div>;

    contentClass = "image_fit" === carousel_style ? hide : ''; 
    var content_container = <div class={"content-container" + contentClass}>{content}</div>;

    return(
      <Fragment>
        {image_fit}
        {content_container}
      </Fragment>
    );

  }

  render() {
		var classList = "et_pb_ca_section_carousel_slide et_pb_module item ";
    var carousel_style = this.props.et_pb_ca_section_carousel_style;

    if ("media" === carousel_style ) {
        return (<Fragment><div className={classList}>{this.renderMediaSlide()}</div></Fragment>);
    } else {
      classList += " backdrop " + carousel_style; 
      var content_fit = "content_fit" === this.props.et_pb_ca_section_carousel_style ? {'background-image' : 'url(this.props.slide_image)'}  : {};
  
        return (
    			<Fragment>
            <div className={classList} style={content_fit}>
            {this.renderSlide()}
            </div>
         </Fragment>
    		);

    }

  }
}

export default CAWeb_Module_Section_Carousel_Slide;
