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
   * Enqueue the js and css for the public-facing side of the site.
   * Hooked via action wp_enqueue_scripts, priority 10
   * @since   1.0.0
   * @return  void
   */
  public function enqueue_scripts()
  {
    if (is_singular(DCJB_CPT_JOB)) :

      wp_enqueue_style(
        'fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        [],
        '4.7.0',
        'all'
      );

      wp_enqueue_script(
        $this->plugin_name,
        DCJB_PLUGIN_URL . 'public/js/dcjb-public.js',
        ['jquery'],
        $this->version,
        'all'
      );

      wp_localize_script(
        $this->plugin_name,
        'dcjb',
        [
          'rest' => array(
            'nonce' => wp_create_nonce('wp_rest'),
            'apply_job' => rest_url('dcjb/v1/apply-job'),
          ),
        ]
      );
    endif;
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

    if (is_singular(DCJB_CPT_JOB)) :

      ob_start();

      // Detect if current user is candidate
      if (is_user_logged_in() && current_user_can('apply_job')) :
        global $post;

        $jobs_applied = (array) get_user_meta(get_current_user_id(), 'jobs_applied', true);

        // Check if current candidate has applied to this job
        if (in_array($post->ID, $jobs_applied)) :
          include DCJB_PLUGIN_DIR . 'public/partials/apply/already-applied.php';

        // Current candidate has not applied to this job
        else :
          include DCJB_PLUGIN_DIR . 'public/partials/apply/available.php';
        endif;

      // Current user is not candidate or not logged in
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