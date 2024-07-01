<?
function nonce_permission_callback(WP_REST_Request $request) {
  $nonce = $request->get_header('X-WP-Nonce');
  if (!wp_verify_nonce($nonce, 'wp_rest')) {
      return new WP_Error('rest_forbidden', 'Nonce verification failed', array('status' => 403));
  }

  return true;
}