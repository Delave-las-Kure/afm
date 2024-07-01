<?
function get_message_count()
{
    $user_id = get_current_user_id();

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

    return $message_count;
}


function increment_message_count()
{
    $user_id = get_current_user_id();

    if ($user_id) {
        $message_count = get_message_count();
        update_field('openai_message_count', $message_count + 1, 'user_' . $user_id);
    } else {
        $cookie_identifier = get_guest_cookie_identifier();
        $ip_identifier = get_user_ip_identifier();

        $cookie_transient_key = 'openai_message_count_cookie_' . hash('sha256', $cookie_identifier);
        $ip_transient_key = 'openai_message_count_ip_' . hash('sha256', $ip_identifier);

        $message_count = get_message_count();

        set_transient($cookie_transient_key, $message_count + 1, DAY_IN_SECONDS);
        set_transient($ip_transient_key, $message_count + 1, DAY_IN_SECONDS);
    }
}

function check_message_quota()
{
    $message_count = get_message_count();
    $max_messages = get_assistant_message_limit();

    if ($message_count >= $max_messages) {
        return false;
    }
    
    return true;
}

function get_assistant_message_limit()
{
    $user_id = get_current_user_id();
    $max_messages = $user_id ? get_field('question_limit_for_users', 'option') : get_field('question_limit_for_guests', 'option');
    return isset($max_messages) ? $max_messages : 0;
}
