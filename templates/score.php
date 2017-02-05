<?php
/**
 * The template for displaying quiz scores
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package QuizMaster
 * @since 1.0
 * @version 1.0
 */

$scoreCtr = QuizMaster_Controller_Score::loadById( $post->ID );

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">



		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
