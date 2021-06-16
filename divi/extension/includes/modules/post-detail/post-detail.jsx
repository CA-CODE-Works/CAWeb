// External Dependencies
import React, { Fragment } from 'react';
import  CAWEeb_Component from '../component.jsx';
import moment from 'moment';

class CAWeb_Module_Post_Handler extends CAWEeb_Component {

	static slug = 'et_pb_ca_post_handler';

	render() {
		var moduleID = "" !== this.props.module_id ? this.props.module_id : ''
		var classes = undefined !== this.props.module_class ? this.props.module_class : '';
		var classList = "et_pb_ca_post_handler et_pb_module " + classes;

		var post_type_layout = this.props.post_type_layout;

		switch(post_type_layout){
			// Course
			case 'course':
				classList += ' course-detail';

				return (
					<article id={moduleID} className={classList}>
						{this.renderCourseDetail(-1)}
					</article>
				);
			
			// Event
			case 'event':
				classList += ' event-detail';

				return (
					<article id={moduleID} className={classList}>
						{this.renderEventDetail(-1)}
					</article>	
				);
			// Exams
			case 'exam':
				classList += ' exam-detail';

				return (
					<div id={moduleID} className={classList}>
						{this.renderExamDetail(-1)}						
					</div>	
				);			
			// FAQs
			case 'faqs':
				return (
					<article id={moduleID} className={classList}>
						{this.props.content()}
						{this.renderFooter(-1)}
					</article>	
				);
			// Jobs
			case 'jobs':
				classList += ' job-detail';

				return (
					<article id={moduleID} className={classList}>
						{this.renderJobDetail(-1)}						
					</article>	
				);
			// News
			case 'news':
				classList += ' news-detail';

				return (
					<article id={moduleID} className={classList}>
						{this.renderNewsDetail(-1)}	
					</article>	
				);
			// Profile
			case 'profile':
				classList += ' profile-detail';

				return (
					<article id={moduleID} className={classList}>
						{this.renderProfileDetail(-1)}	
					</article>	
				);
			case 'general':
			default:
				return (
					<article id="general_post_detail" className={classList}>
					</article>	
				);
		}

  	}
	
    // Renders Course Detail Page
	renderCourseDetail( postID ){
		// Course Attributes
        var show_course_presenter = this.props.show_course_presenter;
        var course_presenter_name = this.props.course_presenter_name;
        var course_presenter_image = this.props.course_presenter_image;
        var course_presenter_bio = this.props.course_presenter_bio;
        var course_start_date = this.props.course_start_date;
        var course_start_date_format = this.props.course_start_date_format;
        var course_start_date_custom_format = this.props.course_start_date_custom_format;
        var course_end_date = this.props.course_end_date;
        var course_end_date_format = this.props.course_end_date_format;
        var course_end_date_custom_format = this.props.course_end_date_custom_format;
        var show_course_address = this.props.show_course_address;
        var course_address = this.props.course_address;
        var course_city = this.props.course_city;
        var course_state = this.props.course_state;
        var course_zip = this.props.course_zip;
        var course_registration_type = this.props.course_registration_type;
        var course_cost = this.props.course_cost;
		var show_course_map = this.props.show_course_map;
		
        // Course Presenter Image
		var presenter_img = undefined !== course_presenter_image && "" !== course_presenter_image ? <img src={course_presenter_image} class="img-left" style={{'height' : '75px', 'width' : '75px'}} alt=""/> : '';
		
		var presenter = "";

		// Display Course Presenter Information
		if( "on" === show_course_presenter ){
			presenter = <div class="presenter mb-1 d-inline-block">
			<p><strong>Presenter:</strong><br />
				<strong class="presenter-name">{course_presenter_name}</strong>
			</p>
			<p>{presenter_img}{course_presenter_bio}</p>
			</div>
		} 
		var course_addr = [course_address, course_city, course_state, course_zip];
		
		var location = "on" === show_course_address ? <Fragment><span class="ca-gov-icon-road-pin"></span>{this.caweb_get_google_map_place_link(course_addr)}</Fragment> : '';

		if( "off" === course_start_date_format || 
			( "on" === course_start_date_format && "" === course_start_date_custom_format.trim() ) ){
				course_start_date_custom_format = 'D, n/j/Y g:i a';
		}

		if( "off" === course_end_date_format || 
			( "on" === course_end_date_format && "" === course_end_date_custom_format.trim() ) ){
				course_end_date_custom_format = 'D, n/j/Y g:i a';
		}

		course_start_date = undefined !== course_start_date ? this.caweb_format_date( course_start_date, course_start_date_custom_format ) : '';
		course_end_date = undefined !== course_end_date ? this.caweb_format_date( course_end_date, course_end_date_custom_format ) : '';
		var course_date = ! this.isEmpty(course_start_date) && ! this.isEmpty(course_end_date) ? 
			<Fragment>{course_start_date} - {course_end_date}</Fragment> : 
			<Fragment>{course_start_date}{course_end_date}</Fragment>;

		var organizer = <Fragment><strong>Organizer</strong><br /><p class="date-time">{course_date}<br />{location}</p></Fragment>;

		course_registration_type = undefined !== course_registration_type && "" !== course_registration_type.trim() ? <Fragment>Registration Type: {course_registration_type}<br /></Fragment> : '';

		course_cost =  undefined !== course_cost && "" !== course_cost.trim() ? `Registration Cost: ${course_cost}`  : '';

		var reg = '' !== course_registration_type || '' !== course_cost ? <p>{course_registration_type}{course_cost}</p> : '';

		var course_map = "on" === show_course_map ? <div class="third">{this.caweb_get_google_map_place_link(course_addr, true)}</div> : '';
		
		return (
			<Fragment>
					<div class="description">
						{this.props.content()}
					</div>
					{presenter}
					<div class="group">
						<div class="two-thirds">
						{organizer}{reg}
						</div>
						{course_map}
					</div>
					{this.renderFooter( postID )}
     </Fragment>
		);
	}
	
    // Renders Events Detail Page
	renderEventDetail( postID ){
		// Event Attributes
		var event_organizer = this.props.event_organizer;
		var show_event_presenter = this.props.show_event_presenter;
		var event_presenter_name = this.props.event_presenter_name;
		var event_presenter_image = this.props.event_presenter_image;
		var event_presenter_bio = this.props.event_presenter_bio;
		var event_start_date = this.props.event_start_date;
		var event_start_date_format = this.props.event_start_date_format;
		var event_start_date_custom_format = this.props.event_start_date_custom_format;
		var event_end_date = this.props.event_end_date;
		var event_end_date_format = this.props.event_end_date_format;
		var event_end_date_custom_format = this.props.event_end_date_custom_format;
		var show_event_address = this.props.show_event_address;
		var event_address = this.props.event_address;
		var event_city = this.props.event_city;
		var event_state = this.props.event_state;
		var event_zip = this.props.event_zip;
		var event_registration_type = this.props.event_registration_type;
		var event_cost = this.props.event_cost;

		// Event Presenter Image
		if( undefined !== event_presenter_image && "" !== event_presenter_image ){
			event_presenter_image = <img src={event_presenter_image} class="img-left" style={ {'height': '75px', 'width': '75px'} } alt="" />;
		}

		// Display Event Presenter Information
		var presenter = "";

		if( "on" === show_event_presenter ){
			presenter = <div class="presenter">
				<p><strong>Presenter:</strong><br />
					<strong class="presenter-name">{event_presenter_name}</strong>
				</p>
				<p>{event_presenter_image}{event_presenter_bio}</p>
			</div>
		}

		var event_addr = [event_address, event_city, event_state, event_zip];

		var location = "on" === show_event_address ? <Fragment><span class="ca-gov-icon-road-pin"></span>{this.caweb_get_google_map_place_link(event_addr)}</Fragment> : '';

		if( "off" === event_start_date_format || 
			( "on" === event_start_date_format && "" === event_start_date_custom_format.trim() ) ){
				event_start_date_custom_format = 'D, n/j/Y g:i a';
		}

		if( "off" === event_end_date_format || 
			( "on" === event_end_date_format && "" === event_end_date_custom_format.trim() ) ){
				event_end_date_custom_format = 'D, n/j/Y g:i a';
		}

		event_start_date = undefined !== event_start_date ? this.caweb_format_date( event_start_date, event_start_date_custom_format ) : '';
		event_end_date = undefined !== event_end_date ? this.caweb_format_date( event_end_date, event_end_date_custom_format ) : '';
		var event_date = ! this.isEmpty(event_start_date) && ! this.isEmpty(event_end_date) ? 
			<Fragment>{event_start_date} - {event_end_date}</Fragment> : 
			<Fragment>{event_start_date}{event_end_date}</Fragment>;

		event_organizer = undefined !== event_organizer && "" !== event_organizer ? <Fragment><strong>{event_organizer}</strong><br /></Fragment>: '';

		var organizer = <Fragment>{event_organizer}<p class="date-time">{event_date}<br />{location}</p></Fragment>;

		event_registration_type =  undefined !== event_registration_type && "" !== event_registration_type ? `Registration Type: ${event_registration_type}` : '';

		event_cost = undefined !== event_cost && "" !== event_cost ? `Registration Cost: ${event_cost}` : '';

		var reg = '' !== event_registration_type || '' !== event_cost ? <p>{event_registration_type}{event_cost}</p> : '';

		// have to find a way to call WP Functions
		//$this->caweb_get_the_post_thumbnail(null, 'thumbnail', array('class'=>'img-left pr-3'))
		return(
			<Fragment>
				{}
				<div class="description">
					{this.props.content()}
				</div>
				{presenter}
				{organizer}
				{reg}
				{this.renderFooter(postID)}
			</Fragment>
		);
	}

    // Renders Exams Detail Page
	renderExamDetail( postID ){
		// Exam Attributes
		var exam_id = this.props.exam_id;
		var exam_class = this.props.exam_class;
		//var exam_status = this.props.exam_status;
		var exam_published_date = this.props.exam_published_date;
		var exam_published_date_format = this.props.exam_published_date_format;
		var exam_published_date_custom_format = this.props.exam_published_date_custom_format;
		var exam_final_filing_date_chooser = this.props.exam_final_filing_date_chooser;
		var exam_final_filing_date = this.props.exam_final_filing_date;
		var exam_final_filing_date_picker = this.props.exam_final_filing_date_picker;
		var exam_final_filing_date_format = this.props.exam_final_filing_date_format;
		var exam_final_filing_date_custom_format = this.props.exam_final_filing_date_custom_format;
		var exam_type = this.props.exam_type;
		var exam_url = this.props.exam_url;
		var exam_address = this.props.exam_address;
		var exam_city = this.props.exam_city;
		var exam_state = this.props.exam_state;
		var exam_zip = this.props.exam_zip;

		var exam_location = '';
		if ("web" === exam_type) {
			exam_location = <Fragment>Exam Url: <a href={exam_url}>{exam_url}</a><br /></Fragment>;
		} else {
			var exam_addr = this.caweb_get_google_map_place_link([exam_address, exam_city, exam_state, exam_zip]);
			exam_location = <Fragment>Exam Address: {exam_addr}<br /></Fragment>;
		}

		exam_class = undefined !== exam_class && "" !== exam_class.trim() ? `Class Code: ${exam_class}` : '';
		exam_id = undefined !== exam_id && "" !== exam_id.trim() ? `Exam Code: ${exam_id}` : '';

		var exam_course = [exam_class, exam_id].filter(w => w !== '');
		exam_course = exam_course.length ? 
			<Fragment>{exam_course.join(' - ', exam_course)}<br /></Fragment> : '';

		if( "off" === exam_published_date_format || 
			( "on" === exam_published_date_format && "" === exam_published_date_custom_format.trim() ) ){
				exam_published_date_custom_format = 'D, n/j/Y g:i a';
		}

		exam_published_date = undefined !== exam_published_date  ? 
			<Fragment>Published Date: {this.caweb_format_date( exam_published_date, exam_published_date_custom_format )}<br /></Fragment> : '';

		if( "off" === exam_final_filing_date_format || 
			( "on" === exam_final_filing_date_format && this.isEmpty(exam_final_filing_date_custom_format.trim()) ) ){
				exam_final_filing_date_custom_format = 'D, n/j/Y g:i a';
		}

		if ("on" === exam_final_filing_date_chooser ) {
			exam_final_filing_date = undefined !== exam_final_filing_date_picker && ! this.isEmpty( exam_final_filing_date_picker ) ? 
				<Fragment>Final Filing Date: {this.caweb_format_date(exam_final_filing_date_picker, exam_final_filing_date_custom_format)}<br /></Fragment> : '';
		} else {
			exam_final_filing_date = <Fragment>Final Filing Date: {this.caweb_format_date(exam_final_filing_date, exam_final_filing_date_custom_format)}<br /></Fragment>;
		}

		var exam_info = <p>{exam_course}{exam_published_date}{exam_final_filing_date}{exam_location}</p>

		//$this->caweb_get_the_post_thumbnail(null, 'medium', array('class' => 'd-block mb-3'))
		return(
			<Fragment>
				<div class="header">
					{}
					{exam_info}
				</div>
				{this.props.content()}
				{this.renderFooter(postID)}
			</Fragment>
		);
	}
	
	// Renders Jobs Detail Page
	renderJobDetail( postID ){
		// Job Attributes
		var show_about_agency = this.props.show_about_agency;
		var job_agency_name = this.props.job_agency_name;
		var job_agency_address = this.props.job_agency_address;
		var job_agency_city = this.props.job_agency_city;
		var job_agency_state = this.props.job_agency_state;
		var job_agency_zip = this.props.job_agency_zip;
		var job_agency_about = this.props.job_agency_about;
		var job_posted_date = this.props.job_posted_date;
		var job_posted_date_format = this.props.job_posted_date_format;
		var job_posted_date_custom_format = this.props.job_posted_date_custom_format;
		var job_hours = this.props.job_hours;
		var show_job_salary = this.props.show_job_salary;
		var job_salary_min = this.props.job_salary_min;
		var job_salary_max = this.props.job_salary_max;
		var job_position_number = this.props.job_position_number;
		var job_rpa_number = this.props.job_rpa_number;
		var job_ds_url = this.props.job_ds_url;
		var job_final_filing_date_chooser = this.props.job_final_filing_date_chooser;
		var job_final_filing_date = this.props.job_final_filing_date;
		var job_final_filing_date_picker = this.props.job_final_filing_date_picker;
		var job_final_filing_date_format = this.props.job_final_filing_date_format;
		var job_final_filing_date_custom_format = this.props.job_final_filing_date_custom_format;
		var show_job_apply_to = this.props.show_job_apply_to;
		var job_apply_to_dept = this.props.job_apply_to_dept;
		var job_apply_to_name = this.props.job_apply_to_name;
		var job_apply_to_address = this.props.job_apply_to_address;
		var job_apply_to_city = this.props.job_apply_to_city;
		var job_apply_to_state = this.props.job_apply_to_state;
		var job_apply_to_zip = this.props.job_apply_to_zip;
		var show_job_questions = this.props.show_job_questions;
		var job_questions_name = this.props.job_questions_name;
		var job_questions_phone = this.props.job_questions_phone;
		var job_questions_email = this.props.job_questions_email;

		var agency_addr = this.caweb_get_google_map_place_link([job_agency_address, job_agency_city, job_agency_state, job_agency_zip]);
		agency_addr = this.isEmpty(agency_addr) ? <Fragment><span class="ca-gov-icon-road-pin"></span>{agency_addr}</Fragment> : '';

		var agency_info = "on" === show_about_agency ? <div class="entity"><strong>{job_agency_name}</strong>{agency_addr}</div> : '';

		if( "off" === job_posted_date_format || 
			( "on" === job_posted_date_format && this.isEmpty(job_posted_date_custom_format.trim())  ) ){
				job_posted_date_custom_format = 'M d, Y';
		}

		var days_passed = moment(job_posted_date).isValid() ? Math.abs( new Date() - new Date(job_posted_date)) : '';
		days_passed = ! this.isEmpty(days_passed) ? parseInt(((days_passed/1000)/60)/1440) : '';
		days_passed = ! this.isEmpty(days_passed) ? <Fragment>&mdash;<span class="fuzzy-date"> {days_passed} days ago</span></Fragment> : '';
		job_posted_date = undefined !== job_posted_date ? <div class="published">Published: {this.caweb_format_date(job_posted_date, job_posted_date_custom_format )}{days_passed}</div> : '';

		job_hours = ! this.isEmpty(job_hours) ? <Fragment>{job_hours}<br /></Fragment> : '';
		job_salary_min = this.isMoney(job_salary_min, '$0.00');
		job_salary_max    = this.isMoney(job_salary_max, '$0.00');

		var job_salary = "on" === show_job_salary ? <Fragment>Salary Range: {job_salary_min} - {job_salary_max}<br /></Fragment> : '';

		var job_position	= '';
		if ( ! this.isEmpty(job_position_number) && ! this.isEmpty(job_rpa_number)) {
			job_position = <Fragment>Position Number: {job_position_number}, RPA #{job_rpa_number}<br /></Fragment>;
		} else if ( ! this.isEmpty(job_position_number) ) {
			job_position = <Fragment>Position Number: {job_position_number}<br /></Fragment>;
		} else if ( ! this.isEmpty(job_rpa_number)) {
			job_position = <Fragment>RPA #{job_rpa_number}<br /></Fragment>;
		}

		job_ds_url = ! this.isEmpty(job_ds_url) ? <Fragment>Duty Statement (<a href={job_ds_url}>PDF</a>)<br /></Fragment> : '';

		if( "off" === job_final_filing_date_format || 
		( "on" === job_final_filing_date_format && this.isEmpty(job_final_filing_date_custom_format.trim()) ) ){
			job_final_filing_date_custom_format = 'D, n/j/Y g:i a';
		}

		if ("on" === job_final_filing_date_chooser ) {
			job_final_filing_date = undefined !== job_final_filing_date_picker && ! this.isEmpty(job_final_filing_date_picker) ? 
			<Fragment>Final Filing Date:<time>{this.caweb_format_date(job_final_filing_date_picker, job_final_filing_date_custom_format)}</time><br /></Fragment> : '';
		} else {
			job_final_filing_date = <Fragment>Final Filing Date:<time>{this.caweb_format_date( job_final_filing_date, job_final_filing_date_format )}</time><br /></Fragment>
		}

		var job_info = <div class="half">
			<div class="well">
				<div class="well-body">
					<p>{job_hours}{job_salary}{job_position}{job_ds_url}{job_final_filing_date}</p>
				</div>
			</div>
		</div>;

		var job_apply_to_info = '';
		var job_questions_info = '';

		if ("on" === show_job_apply_to) {
			var location = this.caweb_return_address([job_apply_to_address, job_apply_to_city, job_apply_to_state, job_apply_to_zip]);
			job_apply_to_dept = ! this.isEmpty(job_apply_to_dept) ? <Fragment>{job_apply_to_dept}<br /></Fragment> : '';
			job_apply_to_name = ! this.isEmpty(job_apply_to_name) ? <Fragment>Attn: {job_apply_to_name}<br /></Fragment> : '';

			job_apply_to_info = <p><strong>Apply To:</strong><br />{job_apply_to_dept}{job_apply_to_name}{location}</p>;
		}

		if ("on" === show_job_questions) {
			var jInfo = '';
			
			if( ! this.isEmpty(job_questions_phone)  && ! this.isEmpty(job_questions_email) ){
				jInfo = <Fragment>{job_questions_phone}, or <a href={"mailto:" + job_questions_email}>{job_questions_email}</a></Fragment>;
			} else if( ! this.isEmpty(job_questions_phone) ){
				jInfo = job_questions_phone;
			} else if (! this.isEmpty(job_questions_email)){
				jInfo =  <a href={"mailto:" + job_questions_email}>{job_questions_email}</a>;
			}
			
			job_questions_name = ! this.isEmpty(job_questions_name) ? `${job_questions_name} at ` : 'Contact';

			job_questions_info = <p><strong>Questions</strong><br />{job_questions_name}{jInfo}</p>;
		}

		var job_apply_info = ! this.isEmpty(job_apply_to_info) || ! this.isEmpty(job_questions_info) ?
					<div class="half"><div class="well"><div class="well-body">{job_apply_to_info}{job_questions_info}</div></div></div> : '';


		job_agency_about  = "on" === show_about_agency && ! this.isEmpty(job_agency_about) ? 
			<div class="panel panel-understated about-department">
				<div class="panel-heading"><h4>About this Department</h4></div>
				<div class="panel-body"><p>{job_agency_about}</p></div>
			</div> : '';
				
		return (
			<Fragment>
				<div class="sub-header">
					{agency_info}{job_posted_date}
				</div>
				<div class="group">
					{job_info}{job_apply_info}
				</div>
				{job_agency_about}
				{this.props.content()}
				{this.renderFooter(postID)}
			</Fragment>
		);

	}

    // Render News Detail Page
	renderNewsDetail( postID ){
		// News Attributes
        var news_author = this.props.news_author;
        var news_publish_date = this.props.news_publish_date;
        var news_publish_date_format = this.props.news_publish_date_format;
        var news_publish_date_custom_format = this.props.news_publish_date_custom_format;
        var news_city = this.props.news_city;
        var show_featured_image = this.props.show_featured_image;

		//$this->caweb_get_the_post_thumbnail(null, array(150, 100), array('class' => 'img-left'))
		var image = "on" === show_featured_image ? '' : '';

        var date_city  = "";
        
        if( ! this.isEmpty(news_publish_date) || ! this.isEmpty(news_author) || ! this.isEmpty(news_city) ){
			if( "off" === news_publish_date_format || 
			( "on" === news_publish_date_format && "" === news_publish_date_custom_format.trim() ) ){
				news_publish_date_custom_format = 'D, n/j/Y g:i a';
			}

            news_publish_date = ! this.isEmpty(news_publish_date) ? <Fragment>Published: {this.caweb_format_date(news_publish_date, news_publish_date_custom_format)}<br /></Fragment> : '';
            news_author = ! this.isEmpty(news_author) ? <Fragment>Author: {news_author}<br /></Fragment> : '';
            news_city = ! this.isEmpty(news_city) ? news_city : '';

			date_city = <header><div class="published"><p>{news_author}{news_publish_date}{news_city}</p></div></header>;
        }
	   
		return(
			<Fragment>
				{date_city}
				{image}
				{this.props.content()}
				{this.renderFooter(postID)}
			</Fragment>
		)

	}

	// Render Profile Detail Page
    renderProfileDetail( postID ){
        // Profile Attributes
        var profile_name_prefix = this.props.profile_name_prefix;
        var profile_name = this.props.profile_name;
        var profile_career_title = this.props.profile_career_title;
        var profile_image_align = this.props.profile_image_align;
        var show_featured_image = this.props.show_featured_image;


		var title = ! this.isEmpty(profile_name_prefix) ? `${profile_name_prefix} ` : '';
		title += profile_name;
		title += ! this.isEmpty(profile_career_title) ? `, ${profile_career_title} ` : '';
		title = ! this.isEmpty(title) ? <h1>{title}</h1> : '';
		
        var img_align = "on" ===  profile_image_align ? "img-right" : "img-left";

        //$image = "on" == $show_featured_image ? $this->caweb_get_the_post_thumbnail(null, array(150, 100), array('class' => $img_align, 'alt' =>  $profile_name)) : '';
        var image = "on" === show_featured_image ? '' : '';

		return(
			<Fragment>
				{title}
				{image}
				{this.props.content()}
				{this.renderFooter(postID)}
			</Fragment>
		);

	}
	
	renderFooter( postID ){
		// General Attributes
        var show_tags_button = this.props.show_tags_button;
		var show_categories_button = this.props.show_categories_button;
		
		//return posts tags
		var tag_names = []
		// have to find a way to call WP Functions
		//wp_get_post_tags(postID, array('fields' => 'names'));
		var tag_list = [];
		
        if ( tag_names.length && "on" ===  show_tags_button) {
            for(var n = 0; n < tag_names.length; n++) {
				tag_list.push(<li>{tag_names[n]}</li>) 
            }
            tag_list = <div class="pull-left mr-4">Tags or Keywords<ul>{tag_list}</ul></div>;
        }
		
		// return posts categories
        var cat_obj = [];
		// have to find a way to call WP Functions
		// get_the_category(postID);
		var cat_list = [];
		
        if ( cat_obj.length && "on" ===  show_categories_button) {
            for(var i = 0; i < cat_obj.length; i++) {
                cat_list.push(<li>{cat_list[i]['name']}</li>);
            }
            cat_list = <Fragment>Categories<ul>{cat_list}</ul></Fragment>;
        }

		return(
			<footer class="keywords">
				{tag_list}
				{cat_list}
			</footer>
		);
	}
	
}

export default CAWeb_Module_Post_Handler;
