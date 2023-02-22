<?php

namespace DCJB\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Dcjb
 * @subpackage Dcjb/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dcjb
 * @subpackage Dcjb/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class User
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
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Register user olres
   * Hooked via action init, priority 10
   * @since   1.0.0
   * @return  void
   */
  public function register_roles()
  {
    global $wp_roles;

    if (!isset($wp_roles)) :
      $wp_roles = new \WP_Roles();
    endif;

    $wp_roles->add_cap('administrator', 'edit_job');
    $wp_roles->add_cap('administrator', 'read_job');
    $wp_roles->add_cap('administrator', 'delete_job');
    $wp_roles->add_cap('administrator', 'edit_jobs');
    $wp_roles->add_cap('administrator', 'edit_others_jobs');
    $wp_roles->add_cap('administrator', 'publish_jobs');
    $wp_roles->add_cap('administrator', 'delete_jobs');
    $wp_roles->add_cap('administrator', 'edit_private_jobs');
    $wp_roles->add_cap('administrator', 'read_private_jobs');
    $wp_roles->add_cap('administrator', 'delete_private_jobs');
    $wp_roles->add_cap('administrator', 'delete_published_jobs');
    $wp_roles->add_cap('administrator', 'delete_others_jobs');
    $wp_roles->add_cap('administrator', 'edit_private_posts');
    $wp_roles->add_cap('administrator', 'edit_published_posts');

    /**
     * Create employer admin role
     */

    $employer = $wp_roles->get_role('subscriber');

    $wp_roles->add_role(DCJB_ROLE_EMPLOYER, 'Employer', $employer->capabilities);

    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'edit_job');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'read_job');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'delete_job');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'edit_jobs');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'publish_jobs');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'delete_published_jobs');
    $wp_roles->add_cap(DCJB_ROLE_EMPLOYER, 'edit_published_posts');

    /**
     * Create CS role
     */

    $candidate = $wp_roles->get_role('subscriber');

    $wp_roles->add_role(DCJB_ROLE_CANDIDATE, 'Candidate', $candidate->capabilities);

    $wp_roles->add_cap(DCJB_ROLE_CANDIDATE, 'read_job');
    $wp_roles->add_cap(DCJB_ROLE_CANDIDATE, 'apply_job');
  }

  /**
   * Add custom columns to user list
   * Hooked via filter manage_users_columns, priority 10
   * @since   1.0.0
   * @param   array $columns
   * @return  array
   */
  public function add_columns(array $columns)
  {
    $columns['jobs_applied'] = 'Jobs Applied';
    return $columns;
  }

  /**
   * Display custom columns content
   * Hooked via filter manage_users_custom_column, priority 10
   * @since   1.0.0
   * @param   string $display
   * @param   string $column
   * @param   int $user_id
   * @return  string
   */
  public function add_columns_content($display, $column, $user_id)
  {
    switch ($column):
      case 'jobs_applied':
        $jobs_applied = get_user_meta($user_id, 'jobs_applied', true);
        if (is_array($jobs_applied) && count($jobs_applied) > 0) :
          $display = count($jobs_applied);
        else :
          $display = 0;
        endif;
        break;
    endswitch;
    return $display;
  }
}