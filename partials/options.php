<?php
/**
 * CAWeb Options Page
 *
 * @package CAWeb
 */

// Render Options Page.
caweb_display_options_page();

/**
 * Main Entry Point for CAWeb Options Page
 *
 * @return void
 */
function caweb_display_options_page() {
	// if saving.
	if ( isset( $_POST['caweb_submit'], $_POST['caweb_theme_options_nonce'] ) &&
	wp_verify_nonce( sanitize_key( $_POST['caweb_theme_options_nonce'] ), 'caweb_theme_options' ) ) {
		caweb_save_options( $_POST, $_FILES );
	}

	// CAWeb Options Page Nonce.
	$caweb_nonce = wp_create_nonce( 'caweb_theme_options' );

	// Selected Tab.
	$caweb_selected_tab = isset( $_POST['tab_selected'] ) ? sanitize_text_field( wp_unslash( $_POST['tab_selected'] ) ) : 'general';

	// Get User Profile Color.
	$caweb_user_color = caweb_get_user_color()->colors[2];

	?>
	<style>
		<?php
		printf( '.menu-list li.list-group-item,.menu-list li.list-group-item:hover {background-color: %1$s !important;}.menu-list li.list-group-item:not(.selected) a {color: %1$s !important;}', esc_attr( $caweb_user_color ) );
		?>
	</style>
	<div class="container-fluid mt-4">
		<form id="caweb-options-form" action="<?php print esc_url( admin_url( 'admin.php?page=caweb_options' ) ); ?>" method="POST" enctype="multipart/form-data">
			<input type="submit" name="caweb_options_submit" class="button button-primary mb-2" value="Save Changes">
			<div class="row">
				<ul class="menu-list list-group list-group-horizontal">
					<li class="list-group-item<?php print 'general' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#general" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'general' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>General Settings</a></li>
					<li class="list-group-item<?php print 'social-share' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#social-share" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'social-share' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>Social Media Links</a></li>
					<li class="list-group-item<?php print 'custom-css' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#custom-css" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'custom-css' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>Custom CSS</a></li>
					<li class="list-group-item<?php print 'custom-js' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#custom-js" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'custom-js' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>Custom JS</a></li>
					<li class="list-group-item<?php print 'alert-banners' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#alert-banners" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'alert-banners' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>Alert Banners</a></li>
					<li class="list-group-item<?php print 'additional-features' === $caweb_selected_tab ? ' selected' : ''; ?>"><a href="#additional-features" class="text-decoration-none text-white" data-toggle="collapse" <?php print 'additional-features' === $caweb_selected_tab ? ' aria-expanded="true"' : ''; ?>>Additional Features</a></li>
				</ul>
			</div>
			<div class="row pr-3">
				<div class="col-12 bg-white border pt-2" id="caweb-settings">
					<input type="hidden" id="tab_selected" name="tab_selected" value="<?php print esc_attr( $caweb_selected_tab ); ?>">
					<input type="hidden" name="caweb_theme_options_nonce" value="<?php print esc_attr( $caweb_nonce ); ?>" />
					<?php
						caweb_display_general_settings( 'general' === $caweb_selected_tab );
						caweb_display_social_media_settings( 'social-share' === $caweb_selected_tab );
						caweb_display_custom_file_settings( 'custom-css' === $caweb_selected_tab, 'css' );
						caweb_display_custom_file_settings( 'custom-js' === $caweb_selected_tab, 'js' );
						caweb_display_alert_banner_settings( 'alert-banners' === $caweb_selected_tab );
						caweb_display_additional_features_settings( 'additional-features' === $caweb_selected_tab );
					?>
					<input type="hidden" name="caweb_submit">
				</div>
			</div>
			<input type="submit" name="caweb_options_submit" class="button button-primary mt-2" value="Save Changes">
		</form>
	</div>
	<?php
}

/**
 * Main Entry Point for CAWeb Options General Settings
 *
 * @param  boolean $is_active If this is the active tab.
 * @return void
 */
function caweb_display_general_settings( $is_active = false ) {
	?>
	<!-- General Settings -->
	<div id="general" class="collapse<?php print $is_active ? ' show' : ''; ?>" data-parent="#caweb-settings">
		<div id="general-settings">
		<?php
			caweb_display_general_options();
			caweb_display_utility_header_options();
			caweb_display_page_header_options();
			caweb_display_google_options();
		?>
		</div>
	</div>
	<?php
}

/**
 * Display for General Options
 *
 * @return void
 */
function caweb_display_general_options() {
	// State Template Version variables.
	$ver = caweb_template_version();

	// Fav Icon.
	$fav_icon      = get_option( 'ca_fav_ico', caweb_default_favicon_url() );
	$fav_icon_name = caweb_favicon_name();

	// Header Menu.
	$navigation_menu = get_option( 'ca_default_navigation_menu', 'megadropdown' );

	// Color Scheme.
	$color_scheme      = get_option( 'ca_site_color_scheme', 'oceanside' );
	$available_schemes = caweb_color_schemes( $ver, 'displayname' );

	// Show Search on FrontPage.
	$frontpage_search_enabled = get_option( 'ca_frontpage_search_enabled', false ) ? ' checked' : '';

	// Sticky Nav.
	$sticky_nav_enabled = get_option( 'ca_sticky_navigation', false ) ? ' checked' : '';

	// Menu Home Link.
	$home_nav_link_enabled = get_option( 'ca_home_nav_link', true ) ? ' checked' : '';

	// Title Display.
	$display_post_title = get_option( 'ca_default_post_title_display', false ) ? ' checked' : '';

	// Display Date for Non Divi Posts.
	$display_post_date = get_option( 'ca_default_post_date_display', false ) ? ' checked' : '';

	// Legacy Browser Support.
	$ua_compatibiliy = get_option( 'ca_x_ua_compatibility', false ) ? ' checked' : '';

	?>
	<!-- General Section -->
	<div>
		<a class="d-inline-block text-decoration-none" aria-label="CAWeb General Settings" data-toggle="collapse" href="#general-setting" role="button" aria-expanded="true" aria-controls="general-settings">
			<h2 class="mb-0">General <span class="text-secondary ca-gov-icon-"></span></h2>
		</a>
	</div>
	<div class="collapse show" id="general-setting" data-parent="#general-settings">
		<!-- State Template Version Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_site_version" class="d-block mb-0"><strong>State Template Version</strong></label>
				<small class="mb-2 text-muted d-block">Select a California State Template version.</small>
				<select id="ca_site_version" name="ca_site_version" class="w-50 form-control">
				<?php
				foreach ( caweb_template_versions() as $version => $label ) :
					?>
					<option value="<?php print esc_attr( $version ); ?>"<?php print "$version" === "$ver" ? ' selected="selected"' : ''; ?>><?php print esc_attr( $label ); ?></option>
				<?php endforeach; ?>
				</select>
			</div>
		</div>
		<!-- Fav Icon Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_fav_ico_filename" class="d-block mb-0"><strong>Fav Icon</strong></label>
				<small class="mb-2 text-muted d-block">Select a site fav icon (displays in browser tab).</small>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<!-- Fav Icon Preview -->
							<img class="ca_fav_ico_option" id="ca_fav_ico_img" src="<?php print esc_url( $fav_icon ); ?>"/> 
						</span>
					</div>
					<!-- Fav Icon Input Field -->
					<input 
						type="text" 
						name="ca_fav_ico" 
						id="ca_fav_ico_filename" 
						value="<?php print esc_attr( $fav_icon_name ); ?>" 
						class="form-control library-link h-auto mx-2" 
						placeholder="Fav Icon" 
						data-choose="Choose a Fav Icon"
						data-update="Set as Fav Icon"
						data-option="x-image/icon, image/x-icon, x-image/x-icon, image/icon, image/vnd.microsoft.icon"
						data-uploader="false" 
						data-icon-check="true"
						readonly>
					<div class="input-group-append">
						<button 
							name="ca_fav_ico"
							class="btn btn-outline-primary library-link" 
							data-choose="Choose a Fav Icon"
							data-update="Set as Fav Icon"
							data-option="x-image/icon, image/x-icon, x-image/x-icon, image/icon, image/vnd.microsoft.icon"
							data-uploader="false" 
							data-icon-check="true">Browse</button>
						<button id="resetFavIcon" class="btn btn-outline-primary" type="button">Reset</button>
					</div>
					<!-- Fav Icon Hidden  -->
					<input type="hidden" id="ca_fav_ico" name="ca_fav_ico" value="<?php print esc_attr( $fav_icon ); ?>" >
				</div>
			</div>
		</div>

		<!-- Header Menu Type Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_default_navigation_menu" class="d-block mb-0"><strong>Header Menu Type</strong></label>
				<small class="mb-2 text-muted d-block">Set a menu style for all pages.</small>
				<select id="ca_default_navigation_menu" name="ca_default_navigation_menu" class="w-50 form-control">
					<option value="flexmega"
					<?php print 'flexmega' === $navigation_menu ? 'selected="selected"' : ''; ?>>Flex Mega Menu</option>
					<option value="megadropdown"
					<?php print 'megadropdown' === $navigation_menu ? 'selected="selected"' : ''; ?>>Mega Drop</option>
					<option value="dropdown"
					<?php print 'dropdown' === $navigation_menu ? 'selected="selected"' : ''; ?>>Drop Down</option>
					<option value="singlelevel"
					<?php print 'singlelevel' === $navigation_menu ? 'selected="selected"' : ''; ?>>Single Level</option>
				</select>
			</div>
		</div>

		<!-- Colorscheme Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_site_color_scheme" class="d-block mb-0"><strong>Color Scheme</strong></label>
				<small class="mb-2 text-muted d-block">Apply a site-wide color scheme.</small>
				<select id="ca_site_color_scheme" name="ca_site_color_scheme" class="w-50 form-control">
				<?php
				foreach ( $available_schemes as $key => $data ) {
					$selected = $key === $color_scheme ? ' selected="selected"' : '';
					?>
					<option value="<?php print esc_attr( $key ); ?>"
						<?php print esc_attr( $selected ); ?>>
						<?php print esc_attr( $data ); ?>
					</option>
					<?php
				}
				?>
				</select>
			</div>
		</div>

		<!-- Search on FrontPage, Sticky Navigation & Menu Home Link Row -->
		<div class="form-row">
			<!-- Title Display Default Off -->
			<div class="form-group col">
				<label for="ca_default_post_title_display" class="d-block mb-0"><strong>Title Display Default Off</strong></label>
				<small class="mb-2 text-muted d-block">Suppress the title for all new pages/posts.</small>
				<input type="checkbox" name="ca_default_post_title_display" id="ca_default_post_title_display" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $display_post_title ); ?>>
			</div>
			<!-- Sticky Navigation -->
			<div class="form-group col">
				<label for="ca_sticky_navigation" class="d-block mb-0"><strong>Sticky Navigation</strong></label>
				<small class="mb-2 text-muted d-block">Keep the navigation menu visibile when scrolling.</small>
				<input type="checkbox" name="ca_sticky_navigation" id="ca_sticky_navigation" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $sticky_nav_enabled ); ?>>
			</div>
			<!-- Menu Home Link -->
			<div class="form-group col">
				<label for="ca_home_nav_link" class="d-block mb-0"><strong>Menu Home Link</strong></label>
				<small class="mb-2 text-muted d-block">Add Home link to subpages header.</small>
				<input type="checkbox" name="ca_home_nav_link" id="ca_home_nav_link" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $home_nav_link_enabled ); ?>>
			</div>
		</div>

		<!-- Title Display Default Off, Display Date for Non-Divi Posts & Legacy Browser Support Row -->
		<div class="form-row">
			<!-- Display Date for Non-Divi Posts -->
			<div class="form-group col">
				<label for="ca_default_post_date_display" class="d-block mb-0"><strong>Display Date for Non-Divi Posts</strong></label>
				<small class="mb-2 text-muted d-block">Display the post published date for non-Divi posts.</small>
				<input type="checkbox" name="ca_default_post_date_display" id="ca_default_post_date_display" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $display_post_date ); ?>>
			</div>
			<!-- Legacy Browser Support -->
			<div class="form-group col">
				<label for="ca_x_ua_compatibility" class="d-block mb-0"><strong>Legacy Browser Support</strong></label>
				<small class="mb-2 text-muted d-block">Creates tradeoff: IE works better but forces accessibility errors.</small>
				<input type="checkbox" name="ca_x_ua_compatibility" id="ca_x_ua_compatibility" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $ua_compatibiliy ); ?> >
				<small class="text-danger d-block"><?php print ! empty( $ua_compatibiliy ) ? 'IE 11 browser compatibility enabled. Warning: creates accessibility errors when using IE browsers.' : ''; ?></small>
			</div>
			<!-- Search on FrontPage -->
			<div class="form-group col">
				<label for="ca_frontpage_search_enabled" class="d-block mb-0"><strong>Show Search on Front Page</strong></label>
				<small class="mb-2 text-muted d-block">Enable Feature Search box on home page.</small>
				<input type="checkbox" name="ca_frontpage_search_enabled" id="ca_frontpage_search_enabled" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $frontpage_search_enabled ); ?> >
			</div>
		</div>
	</div>
	<?php
}

/**
 * Display for Utility Header Options
 *
 * @return void
 */
function caweb_display_utility_header_options() {
	// Contact Us Page.
	$contact_us_link = get_option( 'ca_contact_us_link', '' );

	// Geo Locator.
	$geo_locator_enabled = get_option( 'ca_geo_locator_enabled', false ) ? ' checked' : '';

	// Utility Header Home Icon.
	$utility_header_home_icon = get_option( 'ca_utility_home_icon', true ) ? ' checked' : '';

	?>
	<!-- Utility Header Section -->
	<div>
		<a class="collapsed d-inline-block text-decoration-none" data-toggle="collapse" href="#utility-header-settings" role="button" aria-expanded="false" aria-controls="utility-header-settings">
			<h2 class="mb-0">Utility Header <span class="text-secondary ca-gov-icon-"></span></h2>
		</a>
	</div>
	<div class="collapse" id="utility-header-settings" data-parent="#general-settings">
		<!-- Contact Us Page Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_contact_us_link" class="d-block mb-0"><strong>Contact Us Page</strong></label>
				<small class="mb-2 text-muted d-block">Enter the URL to your &quot;Contact Us&quot; page.</small>
				<input type="text" name="ca_contact_us_link" id="ca_contact_us_link" class="form-control" value="<?php print esc_url( $contact_us_link ); ?>">
			</div>
		</div>

		<!-- Enable Geo Locator & Menu Home Link Row -->
		<div class="form-row">
			<!-- Enable Geo Locator -->
			<div class="form-group col-4">
				<label for="ca_geo_locator_enabled" class="d-block mb-0"><strong>Enable Geo Locator</strong></label>
				<small class="mb-2 text-muted d-block">Displays a geo locator feature at the top right of each page.</small>
				<input type="checkbox" name="ca_geo_locator_enabled" id="ca_geo_locator_enabled" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $geo_locator_enabled ); ?>> 
			</div>
			<!-- Home Link -->
			<div class="form-group col-4">
				<label for="ca_utility_home_icon" class="d-block mb-0"><strong>Home Link</strong></label>
				<small class="mb-2 text-muted d-block">Put a home icon/link on the left side of the utility header.</small>
				<input type="checkbox" name="ca_utility_home_icon" id="ca_utility_home_icon" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $utility_header_home_icon ); ?>>
			</div>
		</div>

		<!-- Custom Link Row -->
		<div class="form-row">
			<?php
			for ( $i = 1; $i <= 3; $i++ ) :
				$p      = "ca_utility_link_$i";
				$name   = get_option( "{$p}_name", '' );
				$url    = get_option( "$p", '' );
				$target = get_option( "{$p}_new_window", true ) ? ' checked' : '';
				$enable = get_option( "{$p}_enable", 'init' );

				if ( ( 'init' === $enable && ! empty( $url ) && ! empty( $name ) ) || $enable ) {
					$enable = ' checked';
				} else {
					$enable = '';
				}
				?>
			<!-- Custom Link <?php print esc_attr( $i ); ?> -->
			<div class="form-group col">
				<label for="<?php print esc_attr( $p ); ?>_enable" class="d-block mb-0"><strong>Custom Link <?php print esc_attr( $i ); ?></strong></label>
				<small class="mb-2 text-muted d-block">Enable a custom link.</small>
				<a data-toggle="collapse" href="#custom_link_<?php print esc_attr( $i ); ?>" aria-expanded="<?php print ! empty( $enable ) ? 'true' : 'false'; ?>" aria-controls="custom_link_<?php print esc_attr( $i ); ?>" class="shadow-none">
					<input type="checkbox" id="<?php print esc_attr( $p ); ?>_enable" name="<?php print esc_attr( $p ); ?>_enable" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $enable ); ?>>
				</a> 
				<div id="custom_link_<?php print esc_attr( $i ); ?>" class="collapse<?php print ! empty( $enable ) ? ' show' : ''; ?>">
					<!-- Link Label -->
					<label for="<?php print esc_attr( $p ); ?>_name" class="d-block mb-0"><strong>Custom Link <?php print esc_attr( $i ); ?> Label</strong></label>
					<small class="mb-2 text-muted d-block">Custom link label text.</small>
					<input type="text" name="<?php print esc_attr( $p ); ?>_name" id="<?php print esc_attr( $p ); ?>_name" class="form-control w-75" value="<?php print esc_attr( $name ); ?>"/>

					<!-- Link Url -->
					<label for="<?php print esc_attr( $p ); ?>" class="d-block mb-0"><strong>Custom Link <?php print esc_attr( $i ); ?> URL</strong></label>
					<small class="mb-2 text-muted d-block">Enter a valid link URL.</small>
					<input type="text" name="<?php print esc_attr( $p ); ?>" id="<?php print esc_attr( $p ); ?>" class="form-control w-75" value="<?php print esc_url( $url ); ?>"/>

					<!-- Link Target -->
					<label for="<?php print esc_attr( $p ); ?>_new_window" class="d-block mb-0">
						<input type="checkbox" name="<?php print esc_attr( $p ); ?>_new_window" id="<?php print esc_attr( $p ); ?>_new_window"<?php print esc_attr( $target ); ?>/>
						<strong>Open in New Tab</strong>
					</label>
					<small class="mb-2 text-muted d-block">Open the link in new tab.</small>
				</div>
			</div>
			<?php endfor; ?>
		</div>
	</div>
	<?php
}

/**
 * Display for Page Header Options
 *
 * @return void
 */
function caweb_display_page_header_options() {
	// Organization Logo.
	$org_logo          = get_option( 'header_ca_branding', '' );
	$org_logo_filename = ! empty( $org_logo ) ? substr( $org_logo, strrpos( $org_logo, '/' ) + 1 ) : '';

	// Organization Logo Alt Text.
	$org_logo_alt_text = '';
	if ( ! empty( $org_logo ) ) {
		$org_logo_alt_text = ! empty( get_option( 'header_ca_branding_alt_text', '' ) ) ? get_option( 'header_ca_branding_alt_text' ) : caweb_get_attachment_post_meta( $org_logo, '_wp_attachment_image_alt' );
	}
	?>
	<!-- Page Header Section -->
	<div>
		<a class="collapsed d-inline-block text-decoration-none" data-toggle="collapse" href="#page-header-settings" role="button" aria-expanded="false" aria-controls="page-header-settings">
			<h2 class="mb-0">Page Header <span class="text-secondary ca-gov-icon-"></span></h2>
		</a>
	</div>
	<div class="collapse" id="page-header-settings" data-parent="#general-settings">
		<!-- Organization Logo-Brand Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="header_ca_branding_filename" class="d-block mb-0"><strong>Organization Logo-Brand</strong></label>
				<small class="mb-2 text-muted d-block">Select an image to use as the agency logo. Recommended size is 300 pixels wide by 80 pixels tall.</small>
				<div class="input-group">
					<!-- Organization Logo-Brand Field -->
					<input 
						type="text" 
						name="header_ca_branding" 
						id="header_ca_branding_filename" 
						readonly 
						value="<?php print esc_attr( $org_logo_filename ); ?>" 
						class="library-link form-control" 
						data-choose="Choose an Organization Logo-Brand"
						data-update="Set as Default Logo"  
						data-uploader="false">

					<div class="input-group-append">
						<!-- Organization Logo-Brand Browse images -->
						<button 
							name="header_ca_branding" 
							class="library-link btn btn-outline-primary" 
							data-choose="Choose an Organization Logo-Brand"
							data-update="Set as Default Logo" 
							data-uploader="false"
							>Browse</button>
					</div>
				</div>

				<!-- Organization Logo-Brand  -->
				<input type="hidden" name="header_ca_branding" value="<?php print esc_attr( $org_logo ); ?>" >

				<!-- Organization Logo-Brand Preview -->
				<img class="header_ca_branding_option" id="header_ca_branding_img" src="<?php print esc_url( $org_logo ); ?>"/>
			</div>
		</div>

		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="header_ca_branding_alt_text" class="d-block mb-0"><strong>Organization Logo-Alt Text</strong></label>
				<small class="mb-2 text-muted d-block">Enter alternative text for the agency logo image.</small>
				<!-- Organization Logo-Brand image alt text -->
				<input type="text" name="header_ca_branding_alt_text" id="header_ca_branding_alt_text" value="<?php print esc_attr( $org_logo_alt_text ); ?>" class="form-control">
			</div>
		</div>
	</div>
	<?php
}

/**
 * Display for Google Options
 *
 * @return void
 */
function caweb_display_google_options() {
	// Search ID.
	$google_search_id = get_option( 'ca_google_search_id', '' );

	// Analytics ID.
	$google_analytic_id = get_option( 'ca_google_analytic_id', '' );

	// Tag Manager ID
	$google_tag_manager_id = get_option( 'ca_google_tag_manager_id', '' );

	// Meta ID.
	$google_meta_id = get_option( 'ca_google_meta_id', '' );

	// Translate.
	$google_translate_mode       = get_option( 'ca_google_trans_enabled', 'none' );
	$google_translate_enabled    = 'custom' !== $google_translate_mode ? ' class="hidden"' : '';
	$google_translate_page       = get_option( 'ca_google_trans_page', '' );
	$google_translate_new_window = get_option( 'ca_google_trans_page_new_window', true ) ? ' checked' : '';
	$google_translate_icon       = get_option( 'ca_google_trans_icon', 'globe' );

	?>
	<!-- Google Section -->
	<div>
		<a class="collapsed d-inline-block text-decoration-none" data-toggle="collapse" href="#google-settings" role="button" aria-expanded="false" aria-controls="google-settings">
			<h2 class="mb-0">Google <span class="text-secondary ca-gov-icon-"></span></h2>
		</a>
	</div>
	<div class="collapse" id="google-settings" data-parent="#general-settings">
		<!-- Search Engine ID Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_google_search_id" class="d-block mb-0"><strong>Search Engine ID</strong></label>
				<small class="mb-2 text-muted d-block">Enter your unique Google search engine ID, if you don't have one see an administrator.</small>
				<!-- Search Engine ID Field -->
				<input type="text" name="ca_google_search_id" id="ca_google_search_id" class="form-control" value="<?php print esc_attr( $google_search_id ); ?>" >
			</div>
		</div>

		<!-- Analytics ID Row -->
		<div class="form-row">
			<div class="form-group col-sm-12<?php print ! empty( $google_tag_manager_approved ) ? ' hidden' : ''; ?>">
				<label for="ca_google_analytic_id" class="d-block mb-0"><strong>Analytics ID</strong></label>
				<small class="mb-2 text-muted d-block">Enter your unique Google Analytics ID, if you don't have one see an administrator.</small>
				<!-- Analytics ID Field -->
				<input type="text" name="ca_google_analytic_id" id="ca_google_analytic_id" class="form-control w-25" value="<?php print esc_attr( $google_analytic_id ); ?>">
			</div>
		</div>
		<!-- Tag Manager ID Row -->
		<div class="form-row">
			<div class="form-group col-sm-12">
				<label for="ca_google_tag_manager_id" class="d-block mb-0"><strong>Tag Manager ID</strong></label>
				<small class="mb-2 text-muted d-block">Enter your unique Google Tag Manager ID, if you don't have one see an administrator.</small>
				<small class="mb-2 text-muted d-block">Note: If you fill out the Analytics field above with the same UA-xxxxxxxx code as you implement via Google Tag Manager, you will get duplicate reporting within Google Analytics.</small>
				<!-- Tag Manager ID Field -->
				<input type="text" name="ca_google_tag_manager_id" id="ca_google_tag_manager_id" class="form-control w-25" value="<?php print esc_attr( $google_tag_manager_id ); ?>" >
			</div>
		</div>
		<!-- Meta ID Row -->
		<div class="form-row">
			<div class="form-group col-sm-5">
				<label for="ca_google_meta_id" class="d-block mb-0"><strong>Site Verification Meta ID</strong></label>
				<small class="mb-2 text-muted d-block">Enter your unique Google Site Verification Meta ID, if you don't have one see an administrator.</small>
				<!-- Meta ID Field -->
				<input type="text" name="ca_google_meta_id" id="ca_google_meta_id" class="form-control" value="<?php print esc_attr( $google_search_id ); ?>" >
			</div>
		</div>

		<!-- Google Translate Row -->
		<div class="form-row">
			<div class="form-group" role="radiogroup" aria-label="Google Translate Modes">
				<label for="ca_google_trans_enabled" class="d-block mb-0"><strong>Enable Google Translate</strong></label>
				<small class="mb-2 text-muted d-block">Displays the Google translate feature at the top right of each page.</small>
				<!-- Google Translate None -->
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="ca_google_trans_enabled" id="ca_google_trans_enabled_none" value="none"<?php print empty( $google_translate_mode ) || false === $google_translate_mode || 'none' === $google_translate_mode ? ' checked' : ''; ?>>
					<label class="form-check-label" for="ca_google_trans_enabled_none">None</label>
				</div>

				<!-- Google Translate Standard -->
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" value="standard" name="ca_google_trans_enabled" id="ca_google_trans_enabled_standard" <?php print true === $google_translate_mode || 'standard' === $google_translate_mode ? ' checked' : ''; ?>>
					<label class="form-check-label" for="ca_google_trans_enabled_standard">Standard</label>
				</div>

				<!-- Google Translate Custom -->
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" id="ca_google_trans_enabled_custom" value="custom" name="ca_google_trans_enabled" <?php print 'custom' === $google_translate_mode ? ' checked' : ''; ?>>
					<label class="form-check-label" for="ca_google_trans_enabled_custom">Custom</label>
				</div>
			</div>
		</div>

		<!-- Google Translate Custom Extras -->
		<div class="form-row collapse <?php print 'custom' === $google_translate_mode ? ' show' : ''; ?>" id="ca_google_trans_enabled_custom_extras">
			<!-- Google Translate Page -->
			<div class="form-group col-sm-5">
				<label for="ca_google_trans_page" class="d-block mb-0"><strong>Translate Page</strong></label>
				<small class="mb-2 text-muted d-block">Select a Page/Post where the Google Translate Service is located.</small>
				<!-- Translate Page Field -->
				<input type="text" name="ca_google_trans_page" id="ca_google_trans_page" class="form-control" value="<?php print esc_attr( $google_translate_page ); ?>" >
			</div>

			<div class="form-group col-sm-2">
				<!-- Open Translate in New Page -->
				<label for="ca_google_trans_page_new_window" class="d-block mb-0"><strong>Open in New Tab</strong></label>
				<small class="mb-2 text-muted d-block">Open link in new tab.</small>
				<input type="checkbox" id="ca_google_trans_page_new_window" name="ca_google_trans_page_new_window" data-on="Yes" data-off="No" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $google_translate_new_window ); ?> />
			</div>

			<!-- Google Translate Icon -->
			<div class="form-group col-sm-12">
				<?php
				print wp_kses(
					caweb_icon_menu(
						array(
							'select' => $google_translate_icon,
							'name'   => 'ca_google_trans_icon',
							'header' => 'Icon',
						)
					),
					caweb_allowed_html( array(), true )
				);
				?>
				<small class="mb-2 text-muted d-block">Select an icon to display in front of the Google Translate Page link.</small>
			</div>

			<div class="form-group col-sm-6">
				<label for="caweb-google-trans-shorcode" class="d-block mb-0"><strong>Google Translate Shortcode</strong></label>
				<small class="mb-2 text-muted d-block">Paste this shortcode on any page to render Google Translate.</small>
				<input id="caweb-google-trans-shorcode" type="text" class="form-control" readonly value="[caweb_google_translate /]">
			</div>
		</div>
	</div>
	<?php
}

/**
 * Main Entry Point for CAWeb Options Social Media Links
 *
 * @param  boolean $is_active If this is the active tab.
 * @return void
 */
function caweb_display_social_media_settings( $is_active = false ) {
	?>
	<!-- Social Media Links -->
	<div class="p-2 collapse<?php print $is_active ? ' show' : ''; ?>" id="social-share" data-parent="#caweb-settings">
		<div class="form-row">
			<div class="form-group">
				<h2 class="d-inline">Social Media Links</h2>
			</div>
		</div>
		<?php
			$social_options = caweb_get_site_options( 'social' );
		foreach ( $social_options as $social => $option ) {
			$share_email        = 'ca_social_email' === $option ? true : false;
			$social             = $share_email ? "Share via $social" : $social;
			$header_checked     = get_option( sprintf( '%1$s_header', $option ) ) ? ' checked' : '';
			$footer_checked     = get_option( sprintf( '%1$s_footer', $option ) ) ? ' checked' : '';
			$new_window_checked = get_option( sprintf( '%1$s_new_window', $option ) ) ? ' checked' : '';
			$hover_text         = get_option( sprintf( '%1$s_hover_text', $option ), "Share via $social" );
			?>
				<div class="form-row">
					<a class="collapsed d-block text-decoration-none" data-toggle="collapse" href="#<?php print esc_attr( $option ); ?>-settings" role="button" aria-expanded="false" aria-controls="<?php print esc_attr( $option ); ?>-settings">
						<h2 class="d-inline"><?php print esc_attr( $social ); ?> <span class="text-secondary ca-gov-icon-"></span></h2>
					</a>
				</div>
				<div class="form-row collapse pt-2" id="<?php print esc_attr( $option ); ?>-settings">
				<?php if ( ! $share_email ) : ?>
					<!-- Option URL -->
					<div class="form-group col-md-12">
						<input type="text" class="form-control w-50" name="<?php print esc_attr( $option ); ?>" aria-label="<?php print esc_attr( $social ); ?>" value="<?php print esc_url( get_option( $option ) ); ?>" />
						<small class="text-muted d-block">Enter social media URL share link.</small>
					</div>
					<?php endif; ?>
					<!-- Show in header -->
					<div class="form-group col-sm-3">
						<label for="<?php print esc_attr( $option ); ?>_header" class="d-block"><strong>Show in header:</strong></label>
						<small class="text-muted d-block">Display social link in the utility header.</small>
						<input type="checkbox" id="<?php print esc_attr( $option ); ?>_header" name="<?php print esc_attr( $option ); ?>_header" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $header_checked ); ?>>
					</div>
					<!-- Show in footer -->
					<div class="form-group col-sm-3">
						<label for="<?php print esc_attr( $option ); ?>_footer" class="d-block"><strong>Show in footer:</strong></label>
						<small class="text-muted d-block">Display social link in the footer.</small>
						<input type="checkbox" id="<?php print esc_attr( $option ); ?>_footer" name="<?php print esc_attr( $option ); ?>_footer" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $footer_checked ); ?>>
					</div>
				<?php if ( ! $share_email ) : ?>
					<!-- Open in New Tab -->
					<div class="form-group col-sm-3">
						<label for="<?php print esc_attr( $option ); ?>_new_window" class="d-block"><strong>Open in New Tab:</strong></label>
						<small class="text-muted d-block">Open link in new tab.</small>
						<input type="checkbox" id="<?php print esc_attr( $option ); ?>_new_window" name="<?php print esc_attr( $option ); ?>_new_window" data-on="Yes" data-off="No" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $new_window_checked ); ?>>
					</div>
					<!-- Hover Text -->
					<div class="form-group col-sm-3">
						<label for="<?php print esc_attr( $option ); ?>_hover_text" class="d-block"><strong>Hover Text:</strong></label>
						<small class="text-muted d-block">Text displayed on mouse hover.</small>
						<input type="text" id="<?php print esc_attr( $option ); ?>_hover_text" name="<?php print esc_attr( $option ); ?>_hover_text" value="<?php print esc_attr( $hover_text ); ?>">
					</div>
					<?php endif; ?>
				</div>
				<?php
		}
		?>
	</div>
	<?php
}

/**
 * Main Entry Point for CAWeb Options Custom CSS & Custom JS
 *
 * @param  boolean $is_active If this is the active tab.
 * @param  string  $file_type Either building CSS or JS Page.
 * @return void
 */
function caweb_display_custom_file_settings( $is_active = false, $file_type = 'css' ) {
	// External File Directory URI.
	$ext_file_dir = sprintf( '%1$s/%2$s/%3$s', CAWEB_EXTERNAL_URI, $file_type, get_current_blog_id() );

	// Uploaded File.
	$ext_files             = get_option( "caweb_external_$file_type", array() );
	$ext_file_input_name   = "caweb_external_${file_type}[]";
	$ext_file_data_section = "custom-$file_type";

	// Custom File.
	$custom_file = get_option( "ca_custom_$file_type", '' );

	// Descriptive.
	$desc = 'css' === $file_type ? 'styles' : 'scripts';
	?>
	<!-- Custom <?php print esc_attr( strtoupper( $file_type ) ); ?> Section -->
	<div class="p-2 collapse<?php print $is_active ? ' show' : ''; ?>" id="custom-<?php print esc_attr( $file_type ); ?>" data-parent="#caweb-settings">
		<!-- Custom Uploaded <?php print esc_attr( strtoupper( $file_type ) ); ?> -->
		<div class="form-row">
			<div class="form-group">
				<h2 class="d-inline">Import <?php print esc_attr( strtoupper( $file_type ) ); ?></h2>
				<button class="btn btn-primary" id="add-<?php print esc_attr( $file_type ); ?>">New File</button>
				<small class="form-text mb-2 text-muted">Any <?php print esc_attr( $desc ); ?> added will override any pre-existing <?php print esc_attr( $desc ); ?>. Uploaded <?php print esc_attr( $desc ); ?> load at the bottom of the head in the order listed. To adjust the order, click and drag the name of the file in the order you would like.</small>
			</div>
			<div class="form-group col-lg-12">
				<ul class="list-group" id="uploaded-<?php print esc_attr( $file_type ); ?>">
					<?php
					foreach ( $ext_files as $name ) :
						?>
						<li class="list-group-item">
							<a href="<?php print esc_url( "$ext_file_dir/$name" ); ?>?TB_iframe=true&width=600&height=550" title="<?php print esc_attr( $name ); ?>" class="text-decoration-none thickbox dashicons dashicons-visibility preview-<?php print esc_attr( $file_type ); ?> text-success align-middle"></a>
							<a href="<?php print esc_url( "$ext_file_dir/$name" ); ?>" download="<?php print esc_attr( $name ); ?>" title="<?php print esc_attr( $name ); ?>" class="text-decoration-none dashicons dashicons-download download-<?php print esc_attr( $file_type ); ?> align-middle"></a>
							<a title="<?php print esc_attr( $name ); ?>" class="dashicons dashicons-dismiss remove-<?php print esc_attr( $file_type ); ?> text-danger align-middle"></a>
							<input type="hidden" name="<?php print esc_attr( $ext_file_input_name ); ?>" data-section="<?php print esc_attr( $ext_file_data_section ); ?>" value="<?php print esc_attr( $name ); ?>"><?php print esc_attr( $name ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<!-- Custom <?php print esc_attr( strtoupper( $file_type ) ); ?> -->
		<div class="form-row">
			<div class="form-group col-lg-12">
				<h2 class="d-inline">Manual <?php print esc_attr( strtoupper( $file_type ) ); ?></h2>
				<small class="form-text mb-2 text-muted">Any <?php print esc_attr( $desc ); ?> added will override any pre-existing <?php print esc_attr( $desc ); ?>.</small>
				<textarea id="ca_custom_<?php print esc_attr( $file_type ); ?>" name="ca_custom_<?php print esc_attr( $file_type ); ?>" class="form-control" aria-label="Custom <?php print esc_attr( strtoupper( $file_type ) ); ?>"><?php print esc_html( wp_unslash( $custom_file ) ); ?></textarea>
			</div>
		</div>
	</div>
	<?php

}

/**
 * Main Entry Point for CAWeb Options Alert Banners
 *
 * @param  boolean $is_active If this is the active tab.
 * @return void
 */
function caweb_display_alert_banner_settings( $is_active = false ) {
	$alerts = get_option( 'caweb_alerts', array() );
	?>
	<!-- Alert Banners -->
	<div class="p-2 collapse<?php print $is_active ? ' show' : ''; ?>" id="alert-banners" data-parent="#caweb-settings">
		<div class="form-row">
			<div class="form-group">
				<h2 class="d-inline">Create an Alert Banner </h2>
				<button class="btn btn-primary" id="add-alert">New Banner</button>
				<small class="mb-2 text-muted d-block">Add Alert Banner</small>
			</div>
		</div>

		<ol id="caweb-alert-banners" class="ml-3">
			<?php
			if ( ! empty( $alerts ) ) {
				foreach ( $alerts as $a => $data ) :
					$header         = $data['header'];
					$default_header = ! empty( $header ) ? $header : 'Label';
					$count          = $a + 1;
					$status         = ! empty( $data['status'] ) ? ' checked' : '';
					$alert_home     = 'home' === $data['page_display'] ? ' checked' : '';
					$alert_all      = 'all' === $data['page_display'] ? ' checked' : '';

					$banner_color = $data['color'];

					$readmore        = 'on' === $data['button'] ? ' checked' : '';
					$readmore_text   = isset( $data['text'] ) && ! empty( $data['text'] ) ? substr( $data['text'], 0, 16 ) : 'More Information';
					$readmore_url    = $data['url'];
					$readmore_target = '_blank' === $data['target'] ? ' checked' : '';

					$alert_icon = $data['icon'];
					?>
			<li class="pl-2">
				<!-- Alert Banner Row -->
				<div class="form-row">
					<a class="collapsed d-block text-decoration-none" data-toggle="collapse" href="#alert-banner-<?php print esc_attr( $count ); ?>" aria-expanded="false" aria-controls="alert-banner-<?php print esc_attr( $count ); ?>">
						<h2 class="d-inline"><?php print esc_attr( $default_header ); ?> <span class="text-secondary ca-gov-icon-"></span></h2>
					</a>
					<!-- Alert Options -->
					<div>
						<input type="checkbox" name="alert-status-<?php print esc_attr( $count ); ?>" data-toggle="toggle" data-onstyle="success"<?php print esc_attr( $status ); ?>>
						<button class="btn btn-danger remove-alert">Remove</button>
					</div>

					<!-- Alert Banner Fields -->
					<div id="alert-banner-<?php print esc_attr( $count ); ?>" class="form-row col-sm-12 p-2 collapse">
					<!-- Alert Banner Title -->
					<div class="form-group col-sm-7">
						<label for="alert-header-<?php print esc_attr( $count ); ?>" class="mb-0"><strong>Title</strong></label>
						<small class="text-muted d-block mb-2">Enter header text for the alert.</small>
						<input placeholder="Label" class="form-control" id="alert-header-<?php print esc_attr( $count ); ?>" name="alert-header-<?php print esc_attr( $count ); ?>" type="text" value="<?php print esc_attr( $header ); ?>">
					</div>

					<!-- Alert Banner Message -->
					<div class="form-group col-sm-12">
						<label for="alert-message-<?php print esc_attr( $count ); ?>"><strong>Message</strong></label>
						<small class="text-muted d-block mb-2">Enter message for the alert</small>
						<?php print wp_editor( stripslashes( $data['message'] ), "alert-message-$count", caweb_tiny_mce_settings() ); ?>
					</div>

					<!-- Alert Banner Settings -->
					<div class="form-group col-sm-12">
						<!-- Display On -->
						<div class="form-group col-sm pl-0" role="radiogroup" aria-label="Alert Display On Options">
								<label class="d-block mb-0"><strong>Display on</strong></label>
								<small class="text-muted d-block mb-2">Select whether alert should display on home page or on all pages.</small>
								<div class="form-check form-check-inline">
									<input 
										id="alert-display-home-<?php print esc_attr( $count ); ?>" 
										name="alert-display-<?php print esc_attr( $count ); ?>" 
										class="form-check-input" 
										type="radio" 
										value="home"
										<?php print esc_attr( $alert_home ); ?>>
									<label class="form-check-label" for="alert-display-home-<?php print esc_attr( $count ); ?>">Home Page Only</label>
								</div>
								<div class="form-check form-check-inline">
									<input 
										id="alert-display-all-<?php print esc_attr( $count ); ?>" 
										name="alert-display-<?php print esc_attr( $count ); ?>" 
										class="form-check-input" 
										type="radio" 
										value="all"
										<?php print esc_attr( $alert_all ); ?>>
									<label class="form-check-label" for="alert-display-all-<?php print esc_attr( $count ); ?>">All Pages</label>
								</div>
							</div>

							<!-- Banner Color -->
							<div class="form-group col-sm pl-0">
								<label for="alert-banner-color-<?php print esc_attr( $count ); ?>"><strong>Banner Color</strong></label>
								<small class="text-muted d-block mb-2">Select a color for the alert banner.</small>
								<input type="color" id="alert-banner-color-<?php print esc_attr( $count ); ?>" name="alert-banner-color-<?php print esc_attr( $count ); ?>" value="<?php print esc_attr( $banner_color ); ?>" class="form-control-sm">
							</div>

							<!-- Read More Button -->
							<div class="form-group pl-0">
								<label for="alert-read-more-<?php print esc_attr( $count ); ?>" class="d-block mb-0"><strong>Read More Button</strong></label>
								<a data-toggle="collapse" href="#alert-banner-read-more-<?php print esc_attr( $count ); ?>" class="shadow-none"> 
									<input type="checkbox" id="alert-read-more-<?php print esc_attr( $count ); ?>" name="alert-read-more-<?php print esc_attr( $count ); ?>" <?php print esc_attr( $readmore ); ?> data-toggle="toggle" data-onstyle="success" class="form-control">
								</a>
							</div>

							<div id="alert-banner-read-more-<?php print esc_attr( $count ); ?>" class="collapse<?php print ! empty( $readmore ) ? ' show' : ''; ?>">
								<!-- Read More Button Text -->
								<div class="form-group col-sm-6 pl-0">
									<label for="alert-read-more-text-<?php print esc_attr( $count ); ?>" class="d-block mb-0"><strong>Read More Button Text</strong></label>
									<input type="text" id="alert-read-more-text-<?php print esc_attr( $count ); ?>" name="alert-read-more-text-<?php print esc_attr( $count ); ?>" maxlength="16" class="form-control" value="<?php print esc_attr( $readmore_text ); ?>">
									<small class="text-muted">(Max Characters: 16)</small>
								</div>

								<!-- Read More Button URL -->
								<div class="form-group col-sm-6 pl-0 d-inline-block">
									<label for="alert-read-more-url-<?php print esc_attr( $count ); ?>" class="d-block mb-0"><strong>Read More Button URL</strong></label>
									<input type="text" id="alert-read-more-url-<?php print esc_attr( $count ); ?>" name="alert-read-more-url-<?php print esc_attr( $count ); ?>" class="form-control" value="<?php print esc_url( $readmore_url ); ?>">
								</div>

								<!-- Read More Button Target -->
								<div class="form-group col-sm-4 pl-0 d-inline-block align-top">
									<label for="alert-read-more-target-<?php print esc_attr( $count ); ?>"class="d-block mb-0"><strong>Open link in New Tab</strong></label>
									<input type="checkbox" id="alert-read-more-target-<?php print esc_attr( $count ); ?>" name="alert-read-more-target-<?php print esc_attr( $count ); ?>" <?php print esc_attr( $readmore_target ); ?> data-on="Yes" data-off="No" data-toggle="toggle" data-onstyle="success" class="form-control">
								</div>
							</div>

							<!-- Banner Icon -->
							<div class="form-group col-sm-12 d-inline-block pl-0">
								<?php
								print wp_kses(
									caweb_icon_menu(
										array(
											'select' => $alert_icon,
											'name'   => "alert-icon-$count",
											'header' => 'Icon',
										)
									),
									caweb_allowed_html( array(), true )
								);
								?>
							</div>
					</div>
				</div>
			</li>
					<?php
				endforeach;
			}
			?>
			<input id="caweb_alert_count" type="hidden" name="caweb_alert_count" value="<?php print ! empty( $alerts ) ? count( $alerts ) : 0; ?>">
		</ol>
	</div>
	<?php
}

/**
 * Main Entry Point for CAWeb Options Additional Features
 *
 * @param  boolean $is_active If this is the active tab.
 * @return void
 */
function caweb_display_additional_features_settings( $is_active = false ) {
	$directory           = wp_upload_dir();
	$file                = $directory['basedir'] . '/pdf-word-sitemap.xml';
	$file_url            = file_exists( $file ) ? sprintf( 'File location: <a href="%1$s%2$s" target="_blank">Document Map</a>', $directory['baseurl'], '/pdf-word-sitemap.xml' ) : '';
	$cap                 = is_multisite() ? 'manage_network_options' : 'manage_options';
	$live_drafts_enabled = get_option( 'caweb_live_drafts', false ) ? ' checked' : '';

	?>
	<div class="p-2 collapse<?php print $is_active ? ' show' : ''; ?>" id="additional-features" data-parent="#caweb-settings">
	<div class="form-row">
			<div class="form-group col-sm-12">
				<strong>Document Map</strong>
				<button class="doc-sitemap btn btn-primary btn-sm">Generate</button>
				<small class="doc-sitemap-update text-muted"><?php print esc_url( $file_url ); ?></small>
			</div>
		</div>
		<?php if ( current_user_can( $cap ) ) : ?>
		<div class="form-row">
			<div class="form-group col-sm-12">
				<label for="caweb_live_drafts"><strong>Enable Live Drafts</strong></label>
				<input type="checkbox" name="caweb_live_drafts" id="caweb_live_drafts" data-toggle="toggle" data-onstyle="success" <?php print esc_attr( $live_drafts_enabled ); ?>>
				<small class="text-muted d-block">This will enable the live drafts functionality.</small>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<?php
}
