<?php

namespace DCJB;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Dcjb
 * @subpackage Dcjb/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dcjb
 * @subpackage Dcjb/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Front
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Display apply button in job detail page
   * Hooked via filter the_content, priority 999
   * @since 1.0.0
   * @param  string $content
   * @return string
   */
  public function display_apply_button($content)
  {

    if (is_singular('job')) :
      ob_start();
      if (is_user_logged_in() && current_user_can('apply_job')) :
        global $post;

        $jobs_applied = (array) get_user_meta(get_current_user_id(), 'jobs_applied', true);

        if (in_array($post->ID, $jobs_applied)) :
          include DCJB_PLUGIN_DIR . 'public/partials/apply/already-applied.php';
        else :
          include DCJB_PLUGIN_DIR . 'public/partials/apply/available.php';
        endif;
      else :
        include DCJB_PLUGIN_DIR . 'public/partials/apply/not-capable.php';
      endif;

      $button = ob_get_contents();
      ob_end_clean();

      $content .= $button;
    endif;

    return $content;
  }
}