<?php

/**
 * Template Name: Profile Template
 *
 */
get_header(); ?>
<div class="px-content py-10 sm:py-12 grow flex flex-col">
  <div class="max-w-lg mx-auto w-full">
    <? while (have_posts()) :
      the_post();

      get_template_part('template-parts/content/content', 'page');

    endwhile; ?>
  </div>
</div>

<? get_footer(); ?>