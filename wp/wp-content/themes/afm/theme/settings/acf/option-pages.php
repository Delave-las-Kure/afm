<?
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
      'page_title'    => 'Quick Questions Settings',
      'menu_title'    => 'Quick Questions',
      'menu_slug'     => 'quick-questions-settings',
      'capability'    => 'edit_posts',
      'redirect'      => false
  ));

  acf_add_options_page(array(
    'page_title'    => 'Assistant Settings',
    'menu_title'    => 'Assistant',
    'menu_slug'     => 'gpt-assistant-settings',
    'capability'    => 'edit_posts',
    'redirect'      => false
));
}