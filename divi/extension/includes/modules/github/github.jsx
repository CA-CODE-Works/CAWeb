// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';

class CAWeb_Module_Github extends CAWEeb_Component {

  static slug = 'et_pb_ca_github';


  render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_github et_pb_module " + classes;
        var title = this.props.title ;

        var githubAPI = <a href="https://developer.github.com/v3/" rel="noopener noreferrer" target="_blank">GitHub Rest API v3</a>;
        var githubRateLimit = <a href="https://developer.github.com/v3/#rate-limiting" rel="noopener noreferrer" target="_blank">Rate Limiting</a>;
        return (
                <Fragment>
                    <div  id={moduleID} className={classList}>
                        <h2>{title}</h2>
                        <div class="alert alert-warning"><strong>* NOTE: </strong>This module utilizes the {githubAPI} to retrieve data about the organizations repositories and is subjected to {githubRateLimit}. To prevent unneccessary requests, this module will display the repository information once the page has been saved.</div>
                    </div>
                </Fragment>
            );
    }
}

export default CAWeb_Module_Github;
