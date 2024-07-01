<?
function create_assistant_thread_cpt() {
  $labels = array(
      'name' => __('Assistant Threads', 'afm'),
      'singular_name' => __('Assistant Thread', 'afm'),
  );

  $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => false,
      'exclude_from_search' => true,
      'supports' => array('title', 'editor', 'author'),
      'rewrite' => false,
  );

  register_post_type('assistant-thread', $args);
}
add_action('init', 'create_assistant_thread_cpt');