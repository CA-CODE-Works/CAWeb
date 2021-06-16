<?php
/**
 * Loads CAWeb Alerts.
 *
 * @package CAWeb
 */

$caweb_utility_home_icon            = get_option( 'ca_utility_home_icon', true );
$caweb_social_options               = caweb_get_site_options( 'social' );
$caweb_contact_us_link              = get_option( 'ca_contact_us_link', '' );
$caweb_google_trans_page            = get_option( 'ca_google_trans_page', '' );
$caweb_google_trans_enabled         = get_option( 'ca_google_trans_enabled', false );
$caweb_google_trans_page_new_window = get_option( 'ca_google_trans_page_new_window', true ) ? '_blank' : '_self';
$caweb_google_trans_icon            = get_option( 'ca_google_trans_icon', '' );

?>
<!-- Utility Header -->
<div class="utility-header hidden-print">
	<div class="container">
		<div class="group flex-row">
			<div class="social-media-links">
				<div class="header-cagov-logo">
					<a href="https://www.ca.gov/" title="CA.gov website">
						<span class="sr-only">CA.gov</span>
						<img style="height: 31px;" src="<?php print esc_url( CAWEB_URI ); ?>/images/system/logo.svg" class="pos-rel" alt="CA.gov website" aria-hidden="true" />
					</a>
				</div>

				<?php if ( $caweb_utility_home_icon ) : ?>
					<a href="/" title="Home" class="utility-home-icon ca-gov-icon-home"><span class="sr-only">Home</span></a>
					<?php
				endif;

				foreach ( $caweb_social_options as $caweb_opt ) {
					$caweb_share_email = 'ca_social_email' === $caweb_opt ? true : false;
					$caweb_sub         = rawurlencode( sprintf( '%1$s | %2$s', get_the_title(), get_bloginfo( 'name' ) ) );
					$caweb_body        = rawurlencode( get_permalink() );
					$caweb_mailto      = $caweb_share_email ? sprintf( 'mailto:?subject=%1$s&body=%2$s', $caweb_sub, $caweb_body ) : '';

					if ( get_option( "${caweb_opt}_header" ) && ( $caweb_share_email || '' !== get_option( $caweb_opt ) ) ) :
						$caweb_share  = substr( $caweb_opt, 10 );
						$caweb_share  = str_replace( '_', '-', $caweb_share );
						$caweb_class  = "utility-social-$caweb_share ca-gov-icon-$caweb_share";
						$caweb_title  = get_option( "${caweb_opt}_hover_text", 'Share via ' . ucwords( $caweb_share ) );
						$caweb_href   = $caweb_share_email ? $caweb_mailto : get_option( $caweb_opt );
						$caweb_target = get_option( "${caweb_opt}_new_window" ) ? 'target="_blank"' : ''
						?>
						<a class="<?php print esc_attr( $caweb_class ); ?>" href="<?php print esc_url( $caweb_href ); ?>" title="<?php print esc_attr( $caweb_title ); ?>" <?php print esc_attr( $caweb_target ); ?>>
							<span class="sr-only"><?php print esc_attr( $caweb_title ); ?></span>
						</a>
						<?php
					endif;

				}
				?>
			</div>
			<div class="settings-links">
				
				<?php
				for ( $caweb_i = 1; $caweb_i < 4; $caweb_i++ ) {
					$caweb_url     = get_option( "ca_utility_link_$caweb_i" );
					$caweb_text    = get_option( "ca_utility_link_${caweb_i}_name" );
					$caweb_target  = get_option( "ca_utility_link_${caweb_i}_new_window" ) ? ' target="_blank"' : '';
					$caweb_enabled = get_option( "ca_utility_link_${caweb_i}_enable", 'init' );
					if ( ( 'init' === $caweb_enabled && ! empty( $url ) && ! empty( $name ) ) || $caweb_enabled ) {
						$caweb_enabled = ' checked';
					} else {
						$caweb_enabled = '';
					}

					if ( $caweb_enabled ) {
						printf(
							'<a class="utility-custom-%1$s" href="%2$s"%3$s>%4$s</a>',
							esc_attr( $caweb_i ),
							esc_url( $caweb_url ),
							esc_attr( $caweb_target ),
							esc_html( $caweb_text )
						);
					}
				}
				?>
				<?php if ( ! empty( $caweb_contact_us_link ) ) : ?>
				<a class="utility-contact-us" href="<?php print esc_url( $caweb_contact_us_link ); ?>">Contact Us</a>
				<?php endif; ?>

				<?php if ( 'custom' === $caweb_google_trans_enabled && ! empty( $caweb_google_trans_page ) ) : ?>
				<a id="caweb-gtrans-custom" target="<?php print esc_attr( $caweb_google_trans_page_new_window ); ?>" href="<?php print esc_url( $caweb_google_trans_page ); ?>">
					<?php if ( ! empty( $caweb_google_trans_icon ) ) : ?>
				<span class="ca-gov-icon-<?php print esc_attr( $caweb_google_trans_icon ); ?>"></span>
				<?php endif; ?>
				</a>
				<?php endif; ?>
				<?php if ( true === $caweb_google_trans_enabled || 'standard' === $caweb_google_trans_enabled ) : ?>
				<div class="quarter standard-translate" id="google_translate_element"></div>
				<?php endif; ?>
				<button class="btn btn-xs btn-primary collapsed" data-toggle="collapse" data-target="#siteSettings" aria-controls="siteSettings">
					<span class="ca-gov-icon-gear" aria-hidden="true"></span> Settings</button>
			</div>
		</div>
	</div>
</div>
