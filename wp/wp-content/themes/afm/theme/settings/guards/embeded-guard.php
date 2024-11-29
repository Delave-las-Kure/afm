<?
function allow_embedding_only_for_whitelisted_domains_csp() {
  // Проверяем, является ли текущий шаблон tpl-embeded.php
  if (is_page_template('tpl-embeded.php')) {
    // Получаем список разрешенных доменов из ACF
    $domain_white_list = get_field('domain_white_list', 'option');

    $allowed_domains = [];

    // Если список разрешенных доменов не пуст, добавляем их в список для CSP
    if (!empty($domain_white_list)) {
      foreach ($domain_white_list as $domain_entry) {
        $domain = $domain_entry['domain'];
        // Поддержка подстановочных знаков для поддоменов
        $allowed_domains[] = sprintf('https://%s', $domain);
      }
    }

    // Создаем строку для директивы frame-ancestors
    $frame_ancestors = !empty($allowed_domains) ? implode(' ', $allowed_domains) : "'none'";

    // Устанавливаем заголовок Content-Security-Policy
    header("Content-Security-Policy: frame-ancestors 'self' $frame_ancestors;");
  }
}

// Хук на момент отправки заголовков
add_action('send_headers', 'allow_embedding_only_for_whitelisted_domains_csp');