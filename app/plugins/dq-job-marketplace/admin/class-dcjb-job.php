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
}