<?php
/**
 * The template for displaying author pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @todo Move script
 * @package CAWeb
 */

wp_nav_menu(
	array(
		'theme_location' => 'footer-menu',
	)
);

wp_footer();

if ( is_tag() || is_archive() || is_category() || is_author() ) : ?>
<script>
	jQuery(document).ready(function() {
		var articles = document.getElementsByTagName('main')[0].getElementsByTagName('article');
		var makeSpace = false;

		for (var i = 0, len = articles.length; i < len; i++) {
			if (articles[i].classList.contains('has-post-thumbnail')) {
				makeSpace = true;
			}
		}

		if (makeSpace) {
			for (var i = 0, len = articles.length; i < len; i++) {
				if (!articles[i].classList.contains('has-post-thumbnail'))
					articles[i].getElementsByTagName('a')[0].setAttribute("style", "width:200px;height:150px;padding-right:20px;padding-bottom:15px;float:left;");

			}
		}

	});
</script>
	<?php

endif;

if ( is_active_sidebar( 'caweb-site-wide-widget' ) ) {
	dynamic_sidebar( 'caweb-site-wide-widget' );
}

