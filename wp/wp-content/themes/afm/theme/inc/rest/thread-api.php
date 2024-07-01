<?
add_action('rest_api_init', function () {
  register_rest_route('thread/v1', '', array(
    'methods' => 'POST',
    'callback' => 'api_create_thread',
    'permission_callback' => 'nonce_permission_callback',
  ));
});

function api_create_thread(WP_REST_Request $request)
{
  $thread_id = sanitize_text_field($request->get_param('thread_id'));
  $title = sanitize_text_field($request->get_param('title'));
  $max_title_length = 50; // Максимальная длина заголовка

  if (empty($thread_id) || empty($title)) {
    return new WP_REST_Response(array('error' => 'Both thread_id and title are required.'), 400);
  }

  // Проверка уникальности thread_id
  $query = new WP_Query(array(
    'post_type' => 'assistant-thread',
    'meta_query' => array(
      array(
        'key' => 'thread_id',
        'value' => $thread_id,
        'compare' => '='
      )
    )
  ));

  if ($query->have_posts()) {
    return new WP_REST_Response(array('message' => __('Thread already exists, using existing.', 'afm')), 200);
  }

  // Усечение заголовка, если он слишком длинный
  $short_title = (strlen($title) > $max_title_length) ? substr($title, 0, $max_title_length) . '...' : $title;

  $user_id = get_current_user_id();
  $default_user_id = get_field('default_thread_user', 'option'); // Предполагается, что это ACF поле находится в опциях
  if (!$user_id) {
    $user_id = $default_user_id;
  }

  // Создание нового поста истории
  $new_post = array(
    'post_type' => 'assistant-thread',
    'post_title' => wp_strip_all_tags($short_title),
    'post_content' => $title,
    'post_status' => 'publish',
    'post_author' => $user_id,
  );

  $post_id = wp_insert_post($new_post);

  if (is_wp_error($post_id)) {
    return new WP_REST_Response(array('error' => __('Unable to create thread.', 'afm')), 500);
  }

  // Сохранение thread_id в ACF поле
  update_field('thread_id', $thread_id, $post_id);

  return new WP_REST_Response(array('message' => __('History created successfully.', 'afm'), 'post_id' => $post_id), 201);
}
