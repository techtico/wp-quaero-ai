<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Search_Chat_Gpt
 * @subpackage Search_Chat_Gpt/includes
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
 * @package    Search_Chat_Gpt
 * @subpackage Search_Chat_Gpt/includes
 * @author     Techtico <ritesh@techtico.io>
 */
class Search_Chat_Gpt
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Search_Chat_Gpt_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * API link of the App.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    API link of the App.
	 */
	protected $api_link;

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
		if (defined('SEARCH_CHAT_GPT_VERSION')) {
			$this->version = SEARCH_CHAT_GPT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'search-chat-gpt';
		$this->api_link = 'https://dnhxlzwhtaucjskdlxkn.supabase.co/functions/v1/';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Search_Chat_Gpt_Loader. Orchestrates the hooks of the plugin.
	 * - Search_Chat_Gpt_i18n. Defines internationalization functionality.
	 * - Search_Chat_Gpt_Admin. Defines all hooks for the admin area.
	 * - Search_Chat_Gpt_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-search-chat-gpt-loader.php';

		/**
		 * The class responsible for defining all Curl Methods.
		 */

		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-search-chat-gpt-api-curl.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-search-chat-gpt-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-search-chat-gpt-public.php';

		$this->loader = new Search_Chat_Gpt_Loader();
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
		$plugin_admin = new Search_Chat_Gpt_Admin($this->get_plugin_name(), $this->get_version(), $this->get_api_link());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		// Add the options page and menu item.
		$this->loader->add_action('admin_menu', $plugin_admin, 'scg_add_admin_menu');
		$this->loader->add_action('admin_init', $plugin_admin, 'scg_config_page_init');
		$this->loader->add_filter("plugin_action_links_search-chat-gpt/search-chat-gpt.php", $plugin_admin, 'scg_settings_link', 10, 3);

		// Add Action when Post is published
		$this->loader->add_action('draft_to_publish', $plugin_admin, 'scg_add_page_to_crawl', 10, 3);
		$this->loader->add_action('future_to_publish', $plugin_admin, 'scg_add_page_to_crawl', 10, 3);
		$this->loader->add_action('private_to_publish', $plugin_admin, 'scg_add_page_to_crawl', 10, 3);
		// Add Action when post is saved
		$this->loader->add_action('save_post', $plugin_admin, 'scg_page_recrawl', 10, 3);
		// Add Action when post is moved to trash
		$this->loader->add_action('wp_trash_post', $plugin_admin, 'scg_delete_page_from_app', 10, 3);
		// Add Action when post is restored
		$this->loader->add_action('untrashed_post', $plugin_admin, 'scg_add_page_to_app_on_restore', 10, 3);
		// Sync the website links to app
		$this->loader->add_action('wp_ajax_sync_website_links', $plugin_admin, 'scg_sync_website_links', 10, 3);
		$this->loader->add_action('wp_ajax_get_total_posts', $plugin_admin, 'scg_total_posts', 10, 3);
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

		$plugin_public = new Search_Chat_Gpt_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('wp_footer', $plugin_public, 'scg_load_search_script');
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
	 * @return    Search_Chat_Gpt_Loader    Orchestrates the hooks of the plugin.
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

	/**
	 * Retrieve the API link of App.
	 *
	 * @since     1.0.0
	 * @return    string    Retrieve the API link of App.
	 */
	public function get_api_link()
	{
		return $this->api_link;
	}
}
