<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Dcjb
 * @subpackage Dcjb/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dcjb
 * @subpackage Dcjb/includes
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Dcjb
{

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Dcjb_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct()
  {
    if (defined('DCJB_VERSION')) {
      $this->version = DCJB_VERSION;
    } else {
      $this->version = '1.0.0';
    }
    $this->plugin_name = 'dcjb';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Dcjb_Loader. Orchestrates the hooks of the plugin.
   * - Dcjb_i18n. Defines internationalization functionality.
   * - Dcjb_Admin. Defines all hooks for the admin area.
   * - Dcjb_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies()
  {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-dcjb-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-dcjb-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-dcjb-admin.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-dcjb-user.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-dcjb-job.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-dcjb-public.php';

    $this->loader = new Dcjb_Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Dcjb_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale()
  {

    $plugin_i18n = new Dcjb_i18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks()
  {

    $admin = new DCJB\Admin($this->get_plugin_name(), $this->get_version());

    $user = new DCJB\Admin\User($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('init', $user, 'register_roles', 10);
    $this->loader->add_filter('manage_users_columns', $user, 'add_columns', 10);
    $this->loader->add_filter('manage_users_custom_column', $user, 'add_columns_content', 10, 3);

    $job = new DCJB\Admin\Job($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('rest_api_init', $job, 'register_rest_routes', 10);
    $this->loader->add_action('init', $job, 'register_post_type', 10);
    $this->loader->add_filter('manage_' . DCJB_CPT_JOB . '_posts_columns', $job, 'add_columns', 10);
    $this->loader->add_action('manage_' . DCJB_CPT_JOB . '_posts_custom_column', $job, 'add_columns_content', 10, 2);
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks()
  {

    $public = new DCJB\Front($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts', 10);
    $this->loader->add_filter('the_content', $public, 'display_apply_button', 999);
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run()
  {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Dcjb_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader()
  {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version()
  {
    return $this->version;
  }
}