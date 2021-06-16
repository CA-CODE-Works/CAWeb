// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

// Internal Dependencies
import './style.css';

class CAWeb_Module_Location extends CAWEeb_Component {

  static slug = 'et_pb_ca_location_widget';

  renderContactLocation() {
    var moduleID = "" !== this.props.module_id ? this.props.module_id : '';
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_module et_pb_ca_location_widget location " + this.props.location_layout + classes;

    var address = this.caweb_return_address(this.props.addr, this.props.city, this.props.state, this.props.zip);

    var display_other = "";
    var display_button = "";
    var display_icon = "";
    
    if( "on" === this.props.show_contact ){
      var phone = "" !== this.props.phone ? <span>General Information: {this.props.phone}<br /></span>: '';
      var fax = "" !== this.props.fax ? "FAX: " + this.props.fax : '';
      display_other = <p class="other">{phone}{fax}</p>;

    } 

    if ( "on" === this.props.show_button && "" !== this.props.location_link ){
        display_button = <a href={this.props.location_link} class="btn" target="_blank">More</a>;
    } 
    
    if ( "" !== this.props.name ) {
      address = <span>{this.props.name}<br />{this.caweb_get_google_map_place_link(address)}</span>;
    } 

    if ("on" === this.props.show_icon ){
        display_icon = this.caweb_get_icon_span(this.props.font_icon);
    }

    return (
      <div  id={moduleID} className={classList}>
        {display_icon}
        <div class="contact">
          <p class="address">{address}</p>
          {display_other}
          {display_button}
        </div>
      </div>
    );
  }

  renderBannerLocation() {
    var moduleID = "" !== this.props.module_id ? this.props.module_id : '';
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_module et_pb_ca_location_widget location " + this.props.location_layout + classes;

    var thumbnail = undefined !== this.props.featured_image ? <img src={this.props.featured_image} alt="Featured" className="w-100"/> : '';
    var desc = "";
    var display_button = "";
    var address = this.caweb_return_address(this.props.addr, this.props.city, this.props.state, this.props.zip);

    if ( "on" === this.props.show_button && "" !== this.props.location_link ){
      display_button = <a href={this.props.location_link} class="btn" target="_blank">View More Details</a>;
    }

    if( "" !== this.props.desc ){
      desc = <Fragment><div class="title">Description</div><div class="description pb-2">{this.props.desc}</div></Fragment>;
    }

    if ( "" !== address ){
      address = <Fragment><span class="ca-gov-icon-road-pin"></span>{this.caweb_get_google_map_place_link(address, false, 'm-l-md d-inline-block')}</Fragment>;
    }
    
    return (
      <div  id={moduleID} className={classList}>
        <div class="thumbnail">{thumbnail}</div>
        <div class="contact">
          <div class="title">{this.props.name}</div>
          <div class="address">{address}</div>
        </div>
        <div class="summary">{desc}{display_button}</div>
      </div>
    );
  }

  renderMiniLocation() {
    var moduleID = "" !== this.props.module_id ? this.props.module_id : '';
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_module et_pb_ca_location_widget location " + this.props.location_layout + classes;

    var address = this.caweb_return_address(this.props.addr, this.props.city, this.props.state, this.props.zip);
    var display_icon = "";
    var contactClass = "";

    if ("on" === this.props.show_icon ){
      display_icon = <div>{this.caweb_get_icon_span(this.props.font_icon)}</div>;
    }else{
      contactClass = " ml-0";
    }

    if( "" !== address ){
      address = <div class="address">{this.caweb_get_google_map_place_link(address)}</div>;
    }

    return (
      <div  id={moduleID} className={classList}>
        {display_icon}
        <div className={"contact" + contactClass}>
          <div class="title">
            <a href={this.props.location_link} target="_blank">{this.props.name}</a>
          </div>
          {address}
          </div>
        </div>
    );
  }

  render() {
    var location_layout = this.props.location_layout;

    if ("contact" === location_layout) {
      return (
        <Fragment>
          { this.renderContactLocation() }
        </Fragment>
        );
    } else if ("mini" === location_layout) {
      return (
        <Fragment>
          { this.renderMiniLocation() }
        </Fragment>
        );
    } else {
      return (
        <Fragment>
          { this.renderBannerLocation() }
        </Fragment>
        );
    }

  }

}

export default CAWeb_Module_Location;
