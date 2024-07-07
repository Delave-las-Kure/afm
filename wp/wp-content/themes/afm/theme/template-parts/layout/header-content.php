<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package afm
 */

?>

<header id="masthead" class="py-4 md:py-5 px-content shadow">

	<div class="w-full mx-auto max-w-content flex justify-between">
		<div class="flex items-center justify-between gap-x-5 sm:gap-x-10">
			<div>
				<?php
				$site_name = esc_attr(get_bloginfo('name'));
				$custom_logo_id = get_theme_mod('custom_logo');

				if (has_custom_logo()) { ?>
					<a href="<?= esc_url(home_url('/')) ?>" class="" title="<?= $site_name ?>">
						<?= wp_get_attachment_image($custom_logo_id, [0, 48], false, array("alt" => $site_name, 'class' => '')) ?>
					</a>
				<? } else { ?>
					<a href="<?= esc_url(home_url('/')) ?>" title="<?= esc_attr(get_bloginfo('name')) ?>" class="">
						<?= esc_html(get_bloginfo('name')) ?>
					</a>
				<? } ?>
			</div>

			<nav id="site-navigation" class="header__menu" aria-label="<?php esc_attr_e('Main Navigation', 'afm'); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</div>
		<div class="flex items-center gap-x-3">
			<? if (is_user_logged_in()) { ?>
				<? get_template_part('template-parts/atoms/icon-button', null, [
					"href" => get_permalink(UM()->options()->get('core_account')),
					"icon" => 'person',
					"color" => "primary",
					"rounded" => true
				]) ?>
				<? get_template_part('template-parts/atoms/icon-button', null, [
					"href" => get_permalink(UM()->options()->get('core_logout')),
					"icon" => 'logout',
					"color" => "error",
					"rounded" => true
				]) ?>
			<? } else { ?>
				<? get_template_part('template-parts/atoms/icon-button', null, [
					"href" => get_permalink(UM()->options()->get('core_login')),
					"icon" => 'login',
					"color" => "primary",
					"rounded" => true
				]) ?>
			<? } ?>
		</div>
	</div>


</header><!-- #masthead -->