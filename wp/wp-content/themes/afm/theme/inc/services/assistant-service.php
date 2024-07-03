<?


class AssistantThreadService {
  static function create(String $thread_id, String $title)
  {
    $max_title_length = 50; // Максимальная длина заголовка

    if (empty($thread_id) || empty($title)) {
      throw new Exception(__('Both thread_id and title are required.', 'afm'), 404);
    }

    // Проверка уникальности thread_id
    $query = new WP_Query();
    $threads = $query->query(array(
      'post_type' => 'assistant-thread',
      'meta_query' => array(
        array(
          'key' => 'thread_id',
          'value' => $thread_id,
          'compare' => '='
        )
      )
    ));

    if ($threads) {
      foreach ($threads as $thread) {
        return $thread->ID;
      }
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
      throw new Exception(__('Unable to create thread.', 'afm'), 500);
    }

    // Сохранение thread_id в ACF поле
    update_field('thread_id', $thread_id, $post_id);

    return $post_id;
  }

  static function update(String $post_id, String $title)
  {
    $max_title_length = 50; // Максимальная длина заголовка

    if (empty($thread_id) || empty($title)) {
      throw new Exception('Both thread_id and title are required.', 404);
    }

    // Проверка уникальности thread_id
    $post = get_post($post_id);

    if (
      !$post ||
      get_post_field('post_author', $post->ID) != get_current_user_id() ||
      get_post_field('post_author', $post->ID) != get_field('default_thread_user', 'option')
    ) {
      throw new Exception(__('Unable to update thread.', 'afm'), 500);
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

    return $post_id;
  }

  static function get_thread_id(Int $post_id) {
    $thread_id = get_field('thread_id', $post_id);

    return $thread_id;
  }
}
