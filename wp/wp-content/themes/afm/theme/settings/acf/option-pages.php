<?
if (function_exists('acf_add_options_page')) {

  acf_add_options_page(array(
    'page_title'    => 'Quick Questions Settings',
    'menu_title'    => 'Quick Questions',
    'menu_slug'     => 'quick-questions-settings',
    'capability'    => 'edit_posts',
    'redirect'      => false
  ));

  acf_add_options_page(array(
    'page_title' => 'Assistant Settings',
    'menu_title' => 'Assistant',
    'menu_slug' => 'gpt-assistant-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ));

  acf_add_options_page(array(
    'page_title' => 'Embedding Settings',
    'menu_title' => 'Embedding',
    'menu_slug' => 'embedding-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ));
}

add_action('toplevel_page_embedding-settings', 'add_iframe_to_acf_options_page');

function add_iframe_to_acf_options_page()
{
  // Проверяем, что мы на странице ACF с настройками "Embedding Settings"

  $urlparts = wp_parse_url(home_url());
  $domain = $urlparts['host'];
?>
  <div style="margin: 20px 0;">
    <h2>Embed Preview</h2>
    <textarea readonly style="width: 100%; height: 120px;" onclick="this.select();">
<iframe id="afm-embeded"
  src="https://<?= $domain ?>/embeded/"
  style="width: 100%; height: 500px; border: none;">
</iframe>
    </textarea>
    <textarea readonly style="width: 100%; height: 120px;" onclick="this.select();">
<script src="https://<?= $domain ?>/wp-content/themes/afm/theme/js/embed.min.js?ver=<?= AFM_VERSION ?>" ></script>
    </textarea>
  </div>
<?php
}
