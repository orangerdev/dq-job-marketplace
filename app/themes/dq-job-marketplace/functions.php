<?php
add_action('wp_enqueue_scripts', 'register_css_files');

/**
 * Register needed CSS files
 * Hooked via action wp_enqueue_scripts, priority 10
 * @since   1.0.0
 * @return  void
 */
function register_css_files()
{
  $parentTheme = 'parent-style';
  $theme        = wp_get_theme();

  wp_enqueue_style(
    $parentTheme,
    get_template_directory_uri() . '/style.css',
    [],
    $theme->parent()->get('Version')
  );

  wp_enqueue_style(
    'child-style',
    get_stylesheet_uri(),
    [$parentTheme],
    $theme->get('Version')
  );
}