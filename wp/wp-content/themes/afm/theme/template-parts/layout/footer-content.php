<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package afm
 */

?>

<footer id="colophon" class="px-content bg-gray-highest">
	<div class="max-w-content mx-auto w-full">
		<?php if (has_nav_menu('menu-2')) : ?>
			<div class="pt-10 pb-5 flex justify-center">
				<nav aria-label="<?php esc_attr_e('Footer Menu', 'afm'); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-2',
							'menu_class'     => 'footer-menu',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			</div>
		<?php endif; ?>

		<div class="py-3 text-center text-body-xs text-on-gray-min">
			<?php echo get_theme_mod('afm_footer_copyright', '© ' . date('Y') . ' ' . get_bloginfo('name') . '.'); ?>
		</div>
	</div>


</footer><!-- #colophon -->