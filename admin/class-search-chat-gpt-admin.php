<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/admin
 */

class Quaero_Ai_Admin
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
	 * API link of the App.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    API link of the App.
	 */
	private $app_link;

	/**
	 * API Key of the App.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    API Key of the App.
	 */
	private $api_key;

	/**
	 * Bot ID created on app.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    Bot ID created on app.
	 */
	private $bot_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version, $app_link)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->app_link = $app_link;
		$this->api_key = get_option('scg_api_key');
		$this->bot_id = get_option('scg_bot_id');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quaero_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quaero_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/search-chat-gpt-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/search-chat-gpt-admin.js', array('jquery'), $this->version, false);
	}

	/*
	* Add a settings page for this plugin to the main menu
	*
	*/
	public function scg_add_admin_menu()
	{


		add_submenu_page(
			'options-general.php',
			'API Key Config',
			'Search Chat GPT',
			'administrator',
			'search-chat-gpt-settings',
			array($this, 'scg_options_page')
		);
	}

	public function scg_settings_link($links)
	{
		$settings_link = '<a href="options-general.php?page=search-chat-gpt-settings">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since {{plugin_version}}
	 * @return void
	 */
	public function scg_options_page()
	{
		$this->api_configuration_options = get_option('scg_api_config');
		include_once plugin_dir_path(__FILE__)  . 'partials/search-chat-gpt-admin-display.php';
	}

	public function scg_config_page_init()
	{

		register_setting("scg_config_settings_group", "scg_api_key");
		register_setting("scg_config_settings_group", "scg_bot_id");


		add_settings_section("scg_config_setting_section", "Settings", array($this, 'scg_config_section_info_callback'), "scg_config_settings_group");

		add_settings_field(
			'scg_api_key', // id
			'API Key', // title
			array($this, 'scg_api_key_callback'), // callback
			'scg_config_settings_group', // page
			'scg_config_setting_section' // section
		);

		add_settings_field(
			'scg_bot_id', // id
			'Bot Id', // title
			array($this, 'scg_bot_id_callback'), // callback
			'scg_config_settings_group', // page
			'scg_config_setting_section' // section
		);
	}

	function scg_config_section_info_callback()
	{

		echo __('Connect to Search Chat GPT via API', 'search-chat-gpt');
	}

	public function scg_api_key_callback()
	{
		printf(
			"<input class=\"regular-text\" type=\"text\" name=\"scg_api_key\" id=\"scg_api_key\" value=\"" . get_option("scg_api_key") . "\"\">"
		);
	}

	public function scg_bot_id_callback()
	{
		printf(
			"<input class=\"regular-text\" type=\"text\" name=\"scg_bot_id\" id=\"scg_bot_id\" value=\"" . get_option("scg_bot_id") . "\"\">"
		);
	}


	public function scg_add_page_to_crawl($post)
	{
		$auth = $this->bot_id . ':' . $this->api_key;
		$curl = new Quaero_Ai_Curl();
		$curl->url($this->app_link . 'create-update-page')
			->method('post')
			->headers(array('Authorization: ' . $auth))
			->data(json_encode(array('content' => $post->post_content, 'url' => get_permalink($post), 'id' => strval($post->ID))))
			->send();

		// check status code of our request
		if ($curl->info['http_code'] == 200) {
			// API Route Added
		}
		$curl->close();
	}

	public function scg_delete_page_from_app($post_id)
	{
		$auth = $this->bot_id . ':' . $this->api_key;
		$post = get_post($post_id);
		$post_url = get_site_url() . '/' . $post->post_name . '/';

		$curl = new Quaero_Ai_Curl();
		$curl->url($this->app_link . 'delete-page')
			->method('delete')
			->headers(array('Authorization: ' . $auth))
			->data(json_encode(array('url' => $post_url, 'id' => strval($post->ID))))
			->send();

		// check status code of our request
		if ($curl->info['http_code'] == 200) {
			// API Route Added
		}
		$curl->close();
	}

	public function scg_add_page_to_app_on_restore($post_id)
	{
		$auth = $this->bot_id . ':' . $this->api_key;
		$post = get_post($post_id);
		$post_url = get_site_url() . '/' . $post->post_name . '/';

		$curl = new Quaero_Ai_Curl();
		$curl->url($this->app_link . 'create-update-page')
			->method('post')
			->headers(array('Authorization: ' . $auth))
			->data(json_encode(array('content' => $post->post_content, 'url' => $post_url, 'id' => strval($post->ID))))
			->send();

		// check status code of our request
		if ($curl->info['http_code'] == 200) {
			// API Route Added
		}
		$curl->close();
	}

	public function scg_total_posts()
	{
		$total_posts = 0;
		$total_posts += (int) wp_count_posts('post')->publish;
		$total_posts += (int) wp_count_posts('page')->publish;
		echo $total_posts;
		wp_die();
	}

	public function scg_sync_website_links()
	{

		$args = array(
			'post_type' => 'post',
			'orderby'    => 'ID',
			'post_status' => 'publish',
			'order'    => 'DESC',
			'posts_per_page' => $_POST['page']
		);

		$result = new WP_Query($args);
		$api_input = array();

		if ($result->have_posts()) {
			while ($result->have_posts()) {
				$result->the_post();
				$post = $result->post;
				array_push(
					$api_input,
					array("id" => strval($post->ID), "url" => get_the_permalink($post->ID), "content" => "test")
				);
			}
			if (count($api_input) > 0) {
				$this->scg_add_pages_to_crawl($api_input);
				echo true;
			}
		}
		wp_die();
	}

	public function scg_add_pages_to_crawl($posts)
	{
		$auth = $this->bot_id . ':' . $this->api_key;
		$curl = new Quaero_Ai_Curl();
		$curl->url($this->app_link . 'insert-pages')
			->method('post')
			->headers(array('Authorization: ' . $auth))
			->data(json_encode(array('pages' => $posts)))
			->send();

		// check status code of our request
		if ($curl->info['http_code'] == 200) {
			$curl->close();
			// API Route Added
			return true;
		}
		$curl->close();
	}
}
