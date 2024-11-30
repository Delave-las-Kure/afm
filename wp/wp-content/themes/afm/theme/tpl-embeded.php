<?
/* Template Name: Embedding Template */
// Проверяем, что страница загружена в iframe
if (!current_user_can('administrator') && !(isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe')) { // замените 'example.com' на ваш домен или оставьте пустым для отслеживания только присутствия заголовка
  // Перенаправление на главную страницу или вывод сообщения об ошибке
  wp_redirect(home_url());
  exit;
}

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