<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CAWeb
 */

global $is_IE, $is_edge;
$caweb_x_ua_compatibility = get_option( 'ca_x_ua_compatibility', false ) ? '11' : 'edge';
$caweb_google_meta_id     = get_option( 'ca_google_meta_id', '' );

$caweb_apple_icon = CAWEB_URI . '/images/system/apple-touch-icon';

?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
	<meta charset="utf-8">
	<meta name="Author" content="State of California" />
	<meta name="Description" content="State of California" />
	<meta name="Keywords" content="California, government" />

	<!-- http://t.co/dKP3o1e -->
	<meta name="HandheldFriendly" content="True">

	<!-- for Blackberry, AvantGo -->
	<meta name="MobileOptimized" content="320">

	<!-- for Windows mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

	<!-- Google Meta-->
	<meta name="google-site-verification" content="<?php print esc_attr( $caweb_google_meta_id ); ?>" />

	<?php if ( $is_IE ) : ?>
	<!-- Activate ClearType for Mobile IE -->
	<meta http-equiv="cleartype" content="on">
	<?php endif; ?>

	<?php if ( $is_IE && ! $is_edge && $caweb_x_ua_compatibility ) : ?>
	<!-- Use highest compatibility mode -->
	<meta http-equiv="X-UA-Compatible" content="IE=<?php print esc_attr( $caweb_x_ua_compatibility ); ?>">
	<?php endif; ?>

	<link rel="apple-touch-icon-precomposed" sizes="100x100" href="<?php print esc_url( $caweb_apple_icon ); ?>-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="192x192" href="<?php print esc_url( $caweb_apple_icon ); ?>-192x192.png">
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?php print esc_url( $caweb_apple_icon ); ?>-180x180.png">
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php print esc_url( $caweb_apple_icon ); ?>-152x152.png">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php print esc_url( $caweb_apple_icon ); ?>-144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php print esc_url( $caweb_apple_icon ); ?>-120x120.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php print esc_url( $caweb_apple_icon ); ?>-114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php print esc_url( $caweb_apple_icon ); ?>-72x72.png">
	<link rel="apple-touch-icon-precomposed" href="<?php print esc_url( $caweb_apple_icon ); ?>-57x57.png">
	<link rel="apple-touch-icon" href="<?php print esc_url( $caweb_apple_icon ); ?>.png">


	<?php

	wp_head();
	?>
</head>
