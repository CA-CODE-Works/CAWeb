<?php
/**
 * This is a generic template for Posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CAWeb
 */

get_header();
$caweb_is_page_builder_used = function_exists( 'et_pb_is_pagebuilder_used' ) && et_pb_is_pagebuilder_used( get_the_ID() );

?>
<body <?php body_class( 'primary' ); ?>>
	<?php require_once 'partials/single.php'; ?>
</body>
</html>
