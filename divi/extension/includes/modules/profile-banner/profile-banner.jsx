// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Profile_Banner extends CAWEeb_Component {

  static slug = 'et_pb_profile_banner';

  renderInner(){
    var image = '';
    var inline_image = {};

    if( 'on' !== this.props.round_image ){
      inline_image = {'background': 'url(' + this.props.portrait_url + ') no-repeat right bottom' };
    }else{
      image = <div class="profile-banner-img-wrapper"><img src={this.props.portrait_url} alt={this.props.portrait_alt} class="float-right" style={ {'width': '90px', 'min-height': '90px'} }/></div>;
    }

    return(
      <div class="inner" style={inline_image}>
          {image}
          {this.renderSubtitle()}
          {this.renderTitle()}
          {this.renderLink()}
      </div>
    )
  }

  
  renderSubtitle(){
    return(
      <div class="banner-subtitle">{this.props.job_title}</div>
    );
  }

  renderTitle(){
    return(
      <div class="banner-title">{this.props.name}</div>
    );
  }

  renderLink(){
    var url = "" !== this.props.url ? this.props.url : '';

    return(
      <div class="banner-link"><a href={url}>{this.props.profile_link}</a></div>
    );
  }

  render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_profile_banner et_pb_module profile-banner-wrapper " + classes;


    var round_image = 'on' === this.props.round_image ? ' round-image': '';

    return (
			<Fragment>
        <div id={moduleID} className={classList}>
          <div className={"profile-banner" + round_image}>
            {this.renderInner()}
          </div>
        </div>
     </Fragment>
		);
  }
}

export default CAWeb_Module_Profile_Banner;
