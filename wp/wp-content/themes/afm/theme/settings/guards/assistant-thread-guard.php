<?
function restrict_assistant_thread_access() {
  if (is_singular('assistant-thread')) {
      global $post;
      $current_user = wp_get_current_user();

      // Проверка, является ли текущий пользователь администратором или автором поста
      if (!current_user_can('administrator') && $post->post_author != $current_user->ID) {
          if (!$current_user->exists()) {
            wp_redirect(wp_login_url(get_permalink()));
          } else {
            wp_redirect(home_url());
          }
          
          exit;
      }
  }
}
add_action('template_redirect', 'restrict_assistant_thread_access');

function restrict_assistant_chat_template_access() {
  if (is_page_template('page-templates/assistant-chat.php') && !is_user_logged_in()) {
      wp_redirect(wp_login_url(get_permalink()));
      exit;
  }
}
add_action('template_redirect', 'restrict_assistant_chat_template_access');