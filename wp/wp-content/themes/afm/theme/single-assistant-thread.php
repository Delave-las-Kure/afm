<?
get_header(); ?>
<div class="px-content py-10 sm:py-14 grow flex flex-col">
  <div class="max-w-3xl mx-auto w-full">
    <? get_template_part('template-parts/assistant/page', null, [
      'assistant_thread_id' => get_the_ID()
    ]); ?>
  </div>
</div>
<? get_footer(); ?>