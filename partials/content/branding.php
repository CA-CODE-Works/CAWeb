<?php
/**
 * Loads CAWeb site branding.
 *
 * @package CAWeb
 */

global $post;

$caweb_logo          = '' !== esc_url( get_option( 'header_ca_branding' ) ) ? esc_url( get_option( 'header_ca_branding' ) ) : '';
$caweb_logo_alt_text = ! empty( get_option( 'header_ca_branding_alt_text', '' ) ) ? get_option( 'header_ca_branding_alt_text' ) : caweb_get_attachment_post_meta( $caweb_logo, '_wp_attachment_image_alt' );

?>
<!-- Branding -->
<div class="branding">
	<div class="header-organization-banner">
		<a href="/">
			<img alt="<?php print esc_attr( get_bloginfo( 'name' ) ); ?> Logo" src="<?php print esc_url( $caweb_logo ); ?>" alt="<?php print esc_attr( $caweb_logo_alt_text ); ?>" />
		</a>
	</div>
</div>
