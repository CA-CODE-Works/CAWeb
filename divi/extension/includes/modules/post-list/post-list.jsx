// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Post_List extends CAWEeb_Component {

  static slug = 'et_pb_ca_post_list';


  render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_post_list et_pb_module " + classes;
        
        var title_size = this.props.title_size;
        var list_title = this.props.title;
        
        if ( ! this.isEmpty(list_title)) {
			if ('h-1' === title_size) {
				list_title = <h1>{list_title}</h1>;
			} else if ('h-2' === title_size) {
				list_title = <h2>{list_title}</h2>;
			} else if ('h-3' === title_size) {
				list_title = <h3>{list_title}</h3>;
			}
        }
        
        return (
                <Fragment>
                    <div  id={moduleID} className={classList}>
                        {list_title}
                        <div class="alert alert-warning"><strong>* NOTE: </strong>This module will render once the page has been saved.</div>
                    </div>
                </Fragment>
            );
    }
}

export default CAWeb_Module_Post_List;
