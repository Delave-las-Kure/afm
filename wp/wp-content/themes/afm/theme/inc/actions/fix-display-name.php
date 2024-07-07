<?
function afm_set_display_name_on_registration($user_id) {
  // Получаем объект пользователя
  $user = get_userdata($user_id);

  // Проверяем, если display_name пуст (пользователь его не задал)
  if (empty($user->display_name)) {
      // Используем имя и фамилию, если они существуют, иначе - логин
      if (!empty($user->first_name) && !empty($user->last_name)) {
          $display_name = $user->first_name . ' ' . $user->last_name;
      } else {
          $display_name = $user->user_login;
      }

      // Обновляем данные пользователя
      wp_update_user([
          'ID' => $user_id,
          'display_name' => $display_name
      ]);
  }
}

// Hook into user registration
add_action('user_register', 'afm_set_display_name_on_registration');