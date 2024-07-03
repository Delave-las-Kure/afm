<?
function reset_daily_message_count() {
  // Сбрасываем счетчики для всех авторизованных пользователей
  $args = array(
      'meta_key' => 'openai_message_count',
      'meta_value' => '',
      'number' => -1,
      'meta_compare' => 'EXISTS'
  );

  $users = get_users($args);
  foreach ($users as $user) {
      update_field('openai_message_count', 0, 'user_' . $user->ID);
      update_field('openai_lock_thread', false, 'user_' . $user->ID);
  }

  // Сбрасываем трансиенты
  global $wpdb;
  $wpdb->query(
      $wpdb->prepare(
          "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
          $wpdb->esc_like('_transient_openai_message_count_cookie_') . '%',
          $wpdb->esc_like('_transient_openai_message_count_ip_') . '%'
      )
  );

  $wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
        $wpdb->esc_like('_transient_openai_lock_thread_cookie_') . '%',
        $wpdb->esc_like('_transient_openai_lock_thread_ip_') . '%'
    )
);
}

if (!wp_next_scheduled('reset_daily_message_count')) {
  wp_schedule_event(time(), 'daily', 'reset_daily_message_count');
}

add_action('reset_daily_message_count', 'reset_daily_message_count');

function remove_daily_reset_message_count_schedule() {
  $timestamp = wp_next_scheduled('reset_daily_message_count');
  wp_unschedule_event($timestamp, 'reset_daily_message_count');
}

add_action('switch_theme', 'remove_daily_reset_message_count_schedule'); 