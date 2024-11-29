<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the `#content` element and all content thereafter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package afm
 */

?>

</div><!-- #content -->

<?php
if (defined('AFM_DISABLE_FOOTER') && !AFM_DISABLE_FOOTER) {
	get_template_part('template-parts/layout/footer', 'content');
}
?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>