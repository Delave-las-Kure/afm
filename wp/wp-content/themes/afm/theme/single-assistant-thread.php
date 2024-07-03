<?
get_header();

get_template_part('template-parts/assistant/page', null, [
  'assistant_thread_id' => get_the_ID()
]);
get_footer();
