// External Dependencies
import React, { Fragment } from 'react';
import renderHTML from 'react-render-html';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Fullwidth_Service_Tiles extends CAWEeb_Component {

    static slug = 'et_pb_ca_fullwidth_service_tiles';

    renderViewMoreButton(){
        return(
            <div class="more-button">
                <div class="more-content"></div>
                <a href={ this.esc_url(this.props.view_more_url) } class="btn-more inverse" target="_blanK">
                    <span class="ca-gov-icon-plus-fill" aria-hidden="true"></span>
                    <span class="more-title">{this.props.view_more_text}</span>
                </a>
            </div>
        );
    }

    render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_fullwidth_service_tiles et_pb_module " + classes;
       
        var view_more = "";

        if ( "on" === this.props.view_more_on_off ){
            view_more = this.renderViewMoreButton();
        } 


        var tileCount = this.props.content.length;
        var tiles = this.props.content;
        var output = []
        var sections = []

        for( var i = 0; i < tileCount; i++){
            var tileAttrs = tiles[i]['props']['attrs'];
            var tile_link = tileAttrs.tile_link;
            var title = <div class="teaser"><h4 class="title">{tileAttrs.item_title}</h4></div>;
            var tile_size = tileAttrs.tile_size;
            var item_image = tileAttrs.item_image;
            var tile_url = tileAttrs.tile_url;
            var tile_content = tiles[i]['props'].content;

            if( 'half' === tile_size ){
				tile_size = 'w-50';
			}else if( 'full' === tile_size ){
				tile_size = 'w-100';
			}

            if ("on" === tile_link) {
                if( "" !== item_image ){
                    var alt_text = "";
                    // caweb_get_attachment_post_meta need to figure away to run WP Functions
                    //var alt_text = caweb_get_attachment_post_meta($item_image, '_wp_attachment_image_alt');
                    item_image = <img 
                        src={item_image} 
                        alt={alt_text} 
                        class="w-100" 
                        style={ { 'background-size' : 'cover', 'height': '320px'} } />;
                }

                output.push(
                    <div 
                        tabindex="0" 
                        className={"service-tile service-tile-empty " + tile_size} 
                        data-url={tile_url} 
                        data-link-target="new" >
                        {item_image}
                        {title}
                    </div>
                );
            } else {
                output.push(
                    <div tabindex="0" 
                        className={"service-tile " + tile_size} 
                        data-tile-id={"panel-" + (i + 1) }
                        style={ { 'background-image': "url(' " + item_image + "')", 'background-size': 'cover'} } >
                        {title}
                    </div>
                );
                
                sections.push(
                    <div
                        data-tile-id={"panel-" + (i + 1) }>
                            <div class="section section-default px-3">
                                <div class="container pt-0">
                                    <div class="card card-block">
                                        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <div class="group px-3">{renderHTML(tile_content)}</div>
                                    </div>
                                </div>
                            </div>
                    </div>);
            }
        }

        return (
                <Fragment>
                    <div  id={moduleID} className={classList}>
                        <div class="service-group clearfix">
                            {output}
                            {sections}
                        </div>
                        {view_more}
                    </div>
                </Fragment>
            );
    }
}

export default CAWeb_Module_Fullwidth_Service_Tiles;
