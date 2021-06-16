<?php
/**
 * Loads CAWeb <header> tag.
 *
 * @package CAWeb
 */

global $post;

$caweb_fixed_header = get_option( 'ca_sticky_navigation', false ) ? ' fixed' : '';
$caweb_color        = get_option( 'ca_site_color_scheme', 'oceanside' );
$caweb_schemes      = caweb_color_schemes( caweb_template_version(), 'filename' );
$caweb_colorscheme  = isset( $caweb_schemes[ $caweb_color ] ) ? $caweb_color : 'oceanside';

/* Search */
$caweb_frontpage_search_enabled = get_option( 'ca_frontpage_search_enabled' );

/* Google Translate */
$caweb_google_search_id     = get_option( 'ca_google_search_id', '' );
$caweb_google_trans_enabled = get_option( 'ca_google_trans_enabled' );
$caweb_google_trans_page    = get_option( 'ca_google_trans_page', '' );
$caweb_google_trans_icon    = get_option( 'ca_google_trans_icon', '' );

/* Google Tag Manager */
$google_tag_manager_id = get_option( 'ca_google_tag_manager_id', '' );

if ( ! empty( $google_tag_manager_id ) ) :
	$src = sprintf( 'https://www.googletagmanager.com/ns.html?id=%1$s', $google_tag_manager_id );

	?>
<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe src="<?php print $src; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
			
<?php endif; ?>

<header id="header" class="global-header<?php print esc_attr( $caweb_fixed_header ); ?>">
	<div id="skip-to-content"><a href="#main-content">Skip to Main Content</a></div>
	<?php

	/* Alerts */
	require_once 'content/alerts.php';

	/* Include Utility Header */
	require_once 'content/utility-header.php';

	/* Include Location Bar */
	require_once 'content/bar-location.php';

	/* Include Settings Bar */
	require_once 'content/bar-settings.php';

	/* Include Branding */
	require_once 'content/branding.php';

	/* Include Mobile Controls */
	require_once 'content/mobile-controls.php';

	?>


	<div class="navigation-search">

		<!-- Include Navigation -->
		<?php
		wp_nav_menu(
			array(
				'theme_location'               => 'header-menu',
				'style'                        => get_option( 'ca_default_navigation_menu' ),
				'home_link'                    => ( ! is_front_page() && get_option( 'ca_home_nav_link', true ) ? true : false ),
			)
		);

			$caweb_search  = is_front_page() && $caweb_frontpage_search_enabled ? ' featured-search fade ' : '';
			$caweb_search .= empty( $caweb_google_search_id ) ? ' hidden ' : '';

		?>
		<div id="head-search" class="search-container<?php print esc_attr( $caweb_search ); ?> hidden-print" role="region" aria-label="Search Expanded">
			<?php
			if ( 'page-templates/searchpage.php' !== get_page_template_slug( get_the_ID() ) ) {
				require_once 'content/search-form.php';
			}
			?>
		</div>
	</div>
</header>
