<?
function afm_footer_customize_register($wp_customize) {
  $wp_customize->add_section('afm_footer_section', array(
      'title' => __('Footer Settings', 'afm'),
      'priority' => 30,
  ));

  $wp_customize->add_setting('afm_footer_copyright', array(
      'default' => '© ' . date('Y') . ' My Website.',
      'sanitize_callback' => 'sanitize_text_field',
      'transport' => 'postMessage',
  ));

  $wp_customize->add_control('afm_footer_copyright_control', array(
      'label' => __('Copyright Text', 'afm'),
      'section' => 'afm_footer_section',
      'settings' => 'afm_footer_copyright',
      'type' => 'text',
  ));
}
add_action('customize_register', 'afm_footer_customize_register');