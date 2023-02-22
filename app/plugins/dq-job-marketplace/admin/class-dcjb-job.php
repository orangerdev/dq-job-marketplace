<?php

namespace DCJB\Admin;

use WP_REST_Response;

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
class Job
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
   * Register REST routes related to job post type
   * Hooked via action rest_api_init, priority 10
   * @since   1.0.0
   * @return  void
   */
  public function register_rest_routes()
  {
    register_rest_route(
      'dcjb/v1',
      'apply-job',
      array(
        'methods' => 'POST',
        'callback' => array($this, 'apply_job'),
        'permission_callback' => function () {
          return is_user_logged_in() && current_user_can('apply_job');
        },
        'args' => array(
          'job_id' => array(
            'required' => true,
            'validate_callback' => function ($param, $request, $key) {
              return is_numeric($param) && !empty($param);
            }
          )
        )
      )
    );
  }

  /**
   * Register custom post type
   * Hooked via action init, priority 10
   * @since 1.0.0
   * @return void
   */
  public function register_post_type()
  {
    $labels = array(
      'name'                  => _x('Jobs', 'Post type general name', 'dcjb'),
      'singular_name'         => _x('Job', 'Post type singular name', 'dcjb'),
      'menu_name'             => _x('Jobs', 'Admin Menu text', 'dcjb'),
      'name_admin_bar'        => _x('Job', 'Add New on Toolbar', 'dcjb'),
      'add_new'               => __('Add New', 'dcjb'),
      'add_new_item'          => __('Add New Job', 'dcjb'),
      'new_item'              => __('New Job', 'dcjb'),
      'edit_item'             => __('Edit Job', 'dcjb'),
      'view_item'             => __('View Job', 'dcjb'),
      'all_items'             => __('All Jobs', 'dcjb'),
      'search_items'          => __('Search Jobs', 'dcjb'),
      'parent_item_colon'     => __('Parent Jobs:', 'dcjb'),
      'not_found'             => __('No Jobs found.', 'dcjb'),
      'not_found_in_trash'    => __('No Jobs found in Trash.', 'dcjb'),
      'filter_items_list'     => _x('Filter Jobs list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dcjb'),
      'items_list_navigation' => _x('Jobs list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dcjb'),
      'items_list'            => _x('Jobs list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'dcjb'),
    );

    $args = array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'show_in_nav_menu' => false,
      'exclude_from_search' => false,
      'query_var'          => true,
      'rewrite'            => array('slug' => 'job'),
      'capability_type'    => 'post',
      'has_archive'        => false,
      'hierarchical'       => false,
      'capability_type'    => 'job',
      'supports'           => array('title', 'editor'),
    );

    register_post_type(DCJB_CPT_JOB, $args);
  }

  /**
   * Apply a job method
   * Requested from dcjs/v1/apply-job
   * @since 1.0.0
   * @return void;
   */
  public function apply_job(\WP_REST_Request $request)
  {
    $params = $request->get_body_params();
    $job_id = absint($params['job_id']);

    $jobs_applied = get_user_meta(get_current_user_id(), 'jobs_applied', true);
    $users_applied = get_post_meta($job_id, 'users_applied', true);

    $users_applied = is_array($users_applied) ? $users_applied : [];
    $jobs_applied = is_array($jobs_applied) ? $jobs_applied : [];

    $jobs_applied[] = $job_id;
    $users_applied[] = get_current_user_id();

    update_user_meta(get_current_user_id(), 'jobs_applied', $jobs_applied);
    update_post_meta($job_id, 'users_applied', $users_applied);

    return [
      'success' =>  true,
      'message' => __('Job applied successfully', 'dcjb'),
      'data' => array(
        'job_id' => $job_id,
        'user_id' => get_current_user_id()
      ),
    ];
  }

  /**
   * Add custom columns to job post type
   * Hooked via filter manage_job_posts_columns, priority 10
   * @since 1.0.0
   * @param array $columns
   * @return array
   */
  public function add_columns(array $columns)
  {
    unset($columns['date']);
    $columns['author'] = __('Owner', 'dcjb');
    $columns['users_applied'] = __('Users Applied', 'dcjb');
    $columns['date'] = __('Date', 'dcjb');
    return $columns;
  }

  /**
   * Display custom columns content
   * Hooked via action manage_job_posts_custom_column, priority 10
   * @since 1.0.0
   * @param string $column
   * @param int $post_id
   * @return void
   */
  public function add_columns_content(string $column, int $post_id)
  {

    switch ($column):
      case 'users_applied':
        $users_applied = get_post_meta($post_id, 'users_applied', true);
        do_action('qm/info', $users_applied);
        if (is_array($users_applied)) :

          echo count($users_applied);
        else :
          echo 0;
        endif;

        break;

      case 'author':
        $author_id = get_post_field('post_author', $post_id);
        $author = get_user_by('id', $author_id);
        echo $author->display_name;
        break;

    endswitch;
  }
}