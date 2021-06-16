<?php
/**
 * The template for displaying date pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#date
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
								?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
							<a class="date-link no-underline" href="<?php the_permalink(); ?>">
								<?php
								if ( has_post_thumbnail() ) {
									$caweb_thumb_id  = get_post_thumbnail_id( get_the_ID() );
									$caweb_thumb_alt = get_post_meta( $caweb_thumb_id, 'wp_attachment_image_alt', true );

									the_post_thumbnail( 'medium', '', array( 'alt' => $caweb_thumb_alt ) );
								}
								?>
								<span class="sr-only">Read more about <?php the_title(); ?></span>
							</a>
								<?php

								et_divi_post_format_content();
								?>
							<div class="date-info">
								<a class="title" href="<?php the_permalink(); ?>">
									<h2><?php ( ! empty( the_title( '', '', false ) ) ? the_title() : print 'No Title' ); ?></h2>
								</a>
								<?php et_divi_post_meta(); ?>
							</div>
							<p><?php truncate_post( 270 ); ?>
								<a class="btn btn-default" href="<?php the_permalink(); ?>">Read More<span class="sr-only">Read more about <?php the_title(); ?></span></a>
							</p>
						</article> <!-- .et_pb_post -->
								<?php
								endwhile;
							?>
						<div class="pagination clearfix">
							<div class="alignleft"><?php next_posts_link( esc_html__( '&laquo; Older Entries', 'Divi' ) ); ?></div>
							<div class="alignright"><?php previous_posts_link( esc_html__( 'Next Entries &raquo;', 'Divi' ) ); ?></div>
						</div>
							<?php
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
				?>
					</main>

					<?php
					if ( is_active_sidebar( 'sidebar-1' ) ) :
						?>
					<aside id="non_divi_sidebar" class="col-lg-3">
						<?php
						print esc_html( get_sidebar( 'sidebar-1' ) );
						?>
					</aside>
						<?php
					endif;
					?>
				</div> <!-- #main-content -->
			</div>
		</div>
	</div>
	<?php get_footer(); ?>
</body>

</html>
