<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CAWeb
 */

get_header();

?>
<body <?php body_class( 'primary' ); ?>>
	<?php require_once 'partials/header.php'; ?>


	<div id="page-container">
		<div id="et-main-area">
			<div id="main-content" class="main-content">
				<div class="section">
					<main class="main-primary">
						<?php
						global $wp_query;

						if ( have_posts() ) :
							while ( have_posts() ) :
								the_post();
								$caweb_post_format = et_pb_post_format();
								?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

										<?php
										$caweb_thumb = '';

										$caweb_width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

										$caweb_height    = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
										$caweb_classtext = 'et_pb_post_main_image';
										$caweb_titletext = get_the_title();
										$caweb_thumbnail = get_thumbnail( $caweb_width, $caweb_height, $caweb_classtext, $caweb_titletext, $caweb_titletext, false, 'Blogimage' );
										$caweb_thumb     = $caweb_thumbnail['thumb'];

										et_divi_post_format_content();

										if ( ! in_array( $caweb_post_format, array( 'link', 'audio', 'quote' ), true ) ) {
											if ( 'video' === $caweb_post_format && false !== ( et_get_first_video() === $caweb_first_video ) ) :
												print esc_html( sprintf( '<div class="et_main_video_container">%1$s</div>', $caweb_first_video ) ); elseif ( ! in_array( $caweb_post_format, array( 'gallery' ), true ) && function_exists( 'et_get_option' ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $caweb_thumb ) :
													?>
							<a href="<?php the_permalink(); ?>">
													<?php print_thumbnail( $caweb_thumb, $caweb_thumbnail['use_timthumb'], $caweb_titletext, $caweb_width, $caweb_height ); ?>
							</a>
													<?php
										elseif ( 'gallery' === $caweb_post_format ) :
											et_pb_gallery_images();
											endif;
										}
										?>

								<?php if ( ! in_array( $caweb_post_format, array( 'link', 'audio', 'quote' ), true ) ) : ?>
									<?php if ( ! in_array( $caweb_post_format, array( 'link', 'audio' ), true ) ) : ?>
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php endif; ?>

									<?php
									et_divi_post_meta();

									if ( function_exists( 'et_get_option' ) && 'on' !== et_get_option( 'divi_blog_style', 'false' ) || ( is_search() && ( 'on' === get_post_meta( get_the_ID(), '_et_pb_use_builder', true ) ) ) ) {
										truncate_post( 270 );
									} else {
										the_content();
									}
									?>
							<?php endif; ?>

						</article> <!-- .et_pb_post -->
								<?php
								endwhile;

							if ( function_exists( 'wp_pagenavi' ) ) {
								wp_pagenavi();
							} else {
								get_template_part( 'includes/navigation', 'index' );
							} else :
								get_template_part( 'includes/no-results', 'index' );
				endif;
							?>
					</main>
				</div> <!-- #main-content -->
			</div>
		</div>
	</div>
	<style>
		#searchform {
			float: right;
		}

		.entry-title a {
			color: #428bca;
		}

		.sform {
			border-bottom: none;
		}

		.searched-for {
			margin-top: 5px;
		}

		.query {
			float: right;
		}

		.count {
			float: left;
		}
		.divider {
			display: block !important;
			width: 100%;
			height: 2px;
			color: #e09900;
			border-color: #e09900;
			background-color: #e09900;
			margin: 0;
		}
	</style>
	<?php get_footer(); ?>
</body>
