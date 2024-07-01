<?
add_action('rest_api_init', function () {
  register_rest_route('assistant/v1', '/jwt/', array(
    'methods' => 'GET',
    'callback' => 'assistant_get_token',
    'permission_callback' => 'nonce_permission_callback',
  ));

  register_rest_route('assistant/v1', '/increment-msg-count/', array(
    'methods' => 'PUT',
    'callback' => 'assistant_increment_msg_count',
    'permission_callback' => 'nonce_permission_callback',
  ));
});

function assistant_get_token(WP_REST_Request $request)
{
  if (!check_message_quota()) {
    return new WP_REST_Response(array(
      'error' => __('Message quota exceeded.', 'afm'),
      'error_code' => 403
    ), 403);
  }

  // 1. Получение JWT токена
  $jwt_url =  get_field('api_domain', 'option') . '/apisix/plugin/jwt/sign?key=' . get_field('jwt_key', 'option');
  $response = wp_remote_get($jwt_url);

  if (is_wp_error($response)) {
    return new WP_REST_Response(array(
      'error' => __('Unable to authorize. Failed to get token.', 'afm'),
      'error_code' => 500
    ), 500);
  }

  $jwt_token = wp_remote_retrieve_body($response);

  return new WP_REST_Response(array(
    'token' => $jwt_token,
  ));
}

function assistant_increment_msg_count(WP_REST_Request $request)
{
  if (!check_message_quota()) {
    return new WP_REST_Response(array(
      'error' => __('Message quota exceeded.', 'afm'),
      'error_code' => 403
    ), 403);
  }

  increment_message_count();

  // Получаем текущее значение счетчика и максимальное количество сообщений
  $message_count = get_message_count();
  $max_messages = get_assistant_message_limit();

  return new WP_REST_Response(array(
    'message_count' => $message_count,
    'max_messages' => $max_messages,
  ));
}