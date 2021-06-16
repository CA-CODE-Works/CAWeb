<?php
/**
 * Template Name: Search Results Page
 *
 * This is the template for Search Results Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-page
 *
 * @package CAWeb
 */


get_header();
?>
<body <?php body_class( 'primary et-tb et-tb-has-header' ); ?>>
	<?php require_once dirname( __DIR__ ) . '/partials/header.php'; ?>
	<style>
		#main-content .container {
			padding-top: 0
		}

		.search-container {
			top: 0 !important;
		}

		.mobile-controls .toggle-search,
		form#Search .close-search {
			display: none !important
		}
	</style>

	<div id="page-container">
		<div id="et-main-area">

			<div id="main-content" class="main-content" tabindex="-1">
				<main class="main-primary">
					<!--Search result section-->
					<div class="section section-default search-container active px-0">
						<?php
						require_once dirname( __DIR__ ) . '/partials/content/search-form.php';
						?>
					</div>
					<div class="section">
						<div class="container">
							<h1>Search Results for: <?php print esc_attr( $keyword ); ?></h1>
							<gcse:searchresults-only></gcse:searchresults-only>
						</div>
					</div>
				</main>
			</div>

		</div>
	</div>
	<?php get_footer(); ?>

</body>

</html>
