<?
function rest_nonce_permission_callback(WP_REST_Request $request)
{
  $nonce = $request->get_header('X-WP-Nonce');
  if (!wp_verify_nonce($nonce, 'wp_rest')) {
    return new WP_Error('rest_forbidden', 'Nonce verification failed', array('status' => 403));
  }

  return true;
}


function assistant_can_run_thread_permission_callback(WP_REST_Request $request)
{
  $nonce = $request->get_header('X-WP-Nonce');
  if (!wp_verify_nonce($nonce, 'wp_rest')) {
    return new WP_Error('rest_forbidden', 'Nonce verification failed', array('status' => 403));
  }

  if (ThreadLimitService::is_thread_locked()) {
    return new WP_Error(
      "rest_forbidden",
      __('Message quota exceeded.', 'afm'),
      array('status' => 403)
    );
  }

  return true;
}

function assistant_add_msg_permission_callback(WP_REST_Request $request)
{
  $nonce = $request->get_header('X-WP-Nonce');
  if (!wp_verify_nonce($nonce, 'wp_rest')) {
    return new WP_Error('rest_forbidden', 'Nonce verification failed', array('status' => 403));
  }

  if (!ThreadLimitService::can_add_message()) {
    return new WP_Error(
      "rest_forbidden",
      __('Message quota exceeded.', 'afm'),
      array('status' => 403)
    );
  }

  return true;
}

