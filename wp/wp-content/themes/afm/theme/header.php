<?php

/**
 * The header for our theme
 *
 * This is the template that displays the `head` element and everything up
 * until the `#content` element.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package afm
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<meta name="rest-nonce" content="<?= wp_create_nonce("wp_rest") ?>">
	
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<div id="page" class="flex flex-col grow">
		<a href="#content" class="sr-only"><?php esc_html_e('Skip to content', 'afm'); ?></a>

		<?php get_template_part('template-parts/layout/header', 'content'); ?>

		<div id="content" class="grow flex flex-col">