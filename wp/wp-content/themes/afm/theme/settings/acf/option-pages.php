<?
if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
      'page_title'    => 'Quick Questions Settings',
      'menu_title'    => 'Quick Questions',
      'menu_slug'     => 'quick-questions-settings',
      'capability'    => 'edit_posts',
      'redirect'      => false
  ));
}