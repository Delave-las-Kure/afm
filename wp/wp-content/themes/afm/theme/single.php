<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package afm
 */

get_header();
?>
	<section id="primary" class="px-content py-10 sm:py-32 grow flex flex-col">
		<main id="main" class="max-w-content mx-auto w-full">
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/content', 'single' );

				// End the loop.
			endwhile;
			?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();
