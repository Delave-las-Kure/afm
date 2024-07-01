<?
function set_assistant_guest_cookie() {
  if (!isset($_COOKIE['assistant_guest_cookie'])) {
      $cookie_value = bin2hex(random_bytes(16)); // Уникальное значение
      set_guest_cookie_identifier($cookie_value);
  }
}
add_action('init', 'set_assistant_guest_cookie');