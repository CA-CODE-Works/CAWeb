<?php
/**
 * Loads the extensions module files
 *
 * @package CAWebModuleExtension
 */

if ( ! class_exists( 'ET_Builder_Element' ) ) {
	return;
}

$caweb_module_files = glob( __DIR__ . '/modules/*/*.php' );

// Load custom Divi Builder modules.
foreach ( (array) $caweb_module_files as $caweb_module_file ) {
	if ( $caweb_module_file && preg_match( "/\/modules\/\b([^\/]+)\/\\1\.php$/", $caweb_module_file ) ) {
		require_once $caweb_module_file;
	}
}
