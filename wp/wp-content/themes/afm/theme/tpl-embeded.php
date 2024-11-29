<?
/* Template Name: Embedding Template */
define('AFM_DISABLE_HEADER', true);
define('AFM_DISABLE_FOOTER', true);
get_header(); ?>
<div class="grow flex flex-col">
  <div class="max-w-3xl mx-auto w-full">
    <? get_template_part('template-parts/assistant/page', null, [
      'assistant_thread_id' => get_the_ID()
    ]); ?>
  </div>
</div>
<? get_footer(); ?>