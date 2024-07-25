<?

class ThreadLimitService
{
    static function is_limit_disabled()
    {
        return get_field('question_disable_limits', 'option');
    }

    static function get_assistant_message_limit(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();
        $max_messages = $user_id ? get_field('question_limit_for_users', 'option') : get_field('question_limit_for_guests', 'option');
        return isset($max_messages) ? $max_messages : 0;
    }

    static function get_message_count(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();

        if ($user_id) {
            // Для авторизованных пользователей используем ACF поля
            $message_count = get_field('openai_message_count', 'user_' . $user_id);
            if (!$message_count) {
                $message_count = 0;
            }
        } else {
            // Для неавторизованных пользователей используем трансиенты
            $cookie_identifier = get_guest_cookie_identifier();
            $ip_identifier = get_user_ip_identifier();

            $cookie_transient_key = 'openai_message_count_cookie_' . hash('sha256', $cookie_identifier);
            $ip_transient_key = 'openai_message_count_ip_' . hash('sha256', $ip_identifier);

            $cookie_message_count = get_transient($cookie_transient_key);
            $ip_message_count = get_transient($ip_transient_key);

            $cookie_message_count = ($cookie_message_count === false) ? 0 : $cookie_message_count;
            $ip_message_count = ($ip_message_count === false) ? 0 : $ip_message_count;

            $message_count = max($cookie_message_count, $ip_message_count);
        }

        return (int) $message_count;
    }

    static function increment_message_count(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();

        if ($user_id) {
            $message_count = ThreadLimitService::get_message_count($user_id);
            update_field('openai_message_count', $message_count + 1, 'user_' . $user_id);
        } else {
            $cookie_identifier = get_guest_cookie_identifier();
            $ip_identifier = get_user_ip_identifier();

            $cookie_transient_key = 'openai_message_count_cookie_' . hash('sha256', $cookie_identifier);
            $ip_transient_key = 'openai_message_count_ip_' . hash('sha256', $ip_identifier);

            $message_count = ThreadLimitService::get_message_count($user_id);

            set_transient($cookie_transient_key, $message_count + 1, DAY_IN_SECONDS);
            set_transient($ip_transient_key, $message_count + 1, DAY_IN_SECONDS);
        }
    }

    static function can_add_message(Int|String $user_id = null)
    {
        if (ThreadLimitService::is_limit_disabled()) return true;

        $message_count = ThreadLimitService::get_message_count($user_id);
        $max_messages = ThreadLimitService::get_assistant_message_limit($user_id);

        if ($message_count >= $max_messages) {
            return false;
        }

        return true;
    }

    static function lock_thread(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();

        if ($user_id) {
            update_field('openai_lock_thread', true, 'user_' . $user_id);
        } else {
            // Для неавторизованных пользователей используем трансиенты
            $cookie_identifier = get_guest_cookie_identifier();
            $ip_identifier = get_user_ip_identifier();

            $cookie_transient_key = 'openai_lock_thread_cookie_' . hash('sha256', $cookie_identifier);
            $ip_transient_key = 'openai_lock_thread_ip_' . hash('sha256', $ip_identifier);

            set_transient($cookie_transient_key, 1, DAY_IN_SECONDS);
            set_transient($ip_transient_key, 1, DAY_IN_SECONDS);
        }
    }

    static function release_thread(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();

        if ($user_id) {
            update_field('openai_lock_thread', false, 'user_' . $user_id);
        } else {
            // Для неавторизованных пользователей используем трансиенты
            $cookie_identifier = get_guest_cookie_identifier();
            $ip_identifier = get_user_ip_identifier();

            $cookie_transient_key = 'openai_lock_thread_cookie_' . hash('sha256', $cookie_identifier);
            $ip_transient_key = 'openai_lock_thread_ip_' . hash('sha256', $ip_identifier);

            set_transient($cookie_transient_key, 0, DAY_IN_SECONDS);
            set_transient($ip_transient_key, 0, DAY_IN_SECONDS);
        }
    }

    static function is_thread_locked(Int|String $user_id = null)
    {
        $user_id = !is_null($user_id) ? $user_id : get_current_user_id();

        if ($user_id) {
            return !!get_field('openai_lock_thread', 'user_' . $user_id);
        } else {
            // Для неавторизованных пользователей используем трансиенты
            $cookie_identifier = get_guest_cookie_identifier();
            $ip_identifier = get_user_ip_identifier();

            $cookie_transient_key = 'openai_lock_thread_cookie_' . hash('sha256', $cookie_identifier);
            $ip_transient_key = 'openai_lock_thread_ip_' . hash('sha256', $ip_identifier);

            $cookie_lock = get_transient($cookie_transient_key);
            $ip_lock = get_transient($ip_transient_key);

            $cookie_lock = ($cookie_lock === false) ? false : (int) $cookie_lock;
            $ip_lock = ($ip_lock === false) ? false : (int) $ip_lock;

            return $cookie_lock || $ip_lock;
        }
    }
}
