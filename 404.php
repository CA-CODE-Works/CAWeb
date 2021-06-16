<?php
/**
 * The template for displaying 404 pages (Page Not Found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package CAWeb
 */

get_header();
?>

<body <?php body_class( 'primary' ); ?>>
	<?php require_once 'partials/header.php'; ?>


	<div id="main-content" class="main-content">
		<div class="section">
			<main class="main-primary">

				<article id="post-0" <?php post_class( 'et_pb_post not_found' ); ?>>
					<div class="entry-content">
						<!-- Page Title-->
						<h1>Page Not Found</h1>
						<div class="description">The page you requested was not found.</div>
					</div>
				</article> <!-- .et_pb_post -->

			</main>
		</div>
	</div> <!-- #main-content -->

	<?php get_footer(); ?>
</body>

</html>
