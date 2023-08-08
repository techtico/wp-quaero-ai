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
	 * API link of the AI App.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    API link of the AI App.
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
		$this->api_key = get_option('qai_api_key');
		$this->bot_id = get_option('qai_bot_id');
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quaero-ai-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/quaero-ai-admin.js', array('jquery'), $this->version, false);
	}

	/*
	* Add a settings page for this plugin to the main menu
	*
	* @since    1.0.0
	* @return void
	*/
	public function qai_add_admin_menu()
	{
		add_submenu_page(
			'options-general.php',
			'API Key Config',
			'Quaero AI Settings',
			'administrator',
			'quaero-ai-settings',
			array($this, 'qai_options_page')
		);
	}

	/*
	* Add a settings link in the plugin listing page.
	*
	* @since  1.0.0
	* @return links
	*/
	public function qai_settings_link($links)
	{
		$settings_link = '<a href="options-general.php?page=quaero-ai-settings">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function qai_options_page()
	{
		$this->api_configuration_options = get_option('qai_api_config');
		include_once plugin_dir_path(__FILE__)  . 'partials/quaero-ai-admin-display.php';
	}

	/**
	 * Preapare settings fields for plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function qai_config_page_init()
	{

		register_setting("qai_config_settings_group", "qai_api_key");
		add_settings_section("qai_config_setting_section", "", "", "qai_config_settings_group");

		add_settings_field(
			'qai_api_key', // id
			'', // title
			array($this, 'qai_api_key_callback'), // callback
			'qai_config_settings_group', // page
			'qai_config_setting_section', // section
			array('class' => 'qai-field-label') // add class
		);

		register_setting("qai_config_settings_group_2", "qai_bot_id");
		add_settings_section("qai_config_setting_section_2", "", "", "qai_config_settings_group_2");

		add_settings_field(
			'qai_bot_id', // id
			'', // title
			array($this, 'qai_bot_id_callback'), // callback
			'qai_config_settings_group_2', // page
			'qai_config_setting_section_2', // section
			array('class' => 'qai-field-label') // add class
		);
	}

	/**
	 * Callback function for api key field.
	 *
	 * @since    1.0.0
	 */
	public function qai_api_key_callback()
	{
		printf(
			"<input class=\"regular-text\" type=\"text\" name=\"qai_api_key\" id=\"qai_api_key\" value=\"" . get_option("qai_api_key") . "\"\">"
		);
	}

	/**
	 * Callback function for Bot ID field.
	 *
	 * @since    1.0.0
	 */
	public function qai_bot_id_callback()
	{
		printf(
			"<input class=\"regular-text\" type=\"text\" name=\"qai_bot_id\" id=\"qai_bot_id\" value=\"" . get_option("qai_bot_id") . "\"\">"
		);
	}


	/**
	 * Push the content of the posts when post status changed to published.
	 *
	 * @since    1.0.0
	 */
	public function qai_add_page_to_crawl($post)
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

	/**
	 * Remove the page from App when post moved to trash.
	 *
	 * @since    1.0.0
	 */
	public function qai_delete_page_from_app($post_id)
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

	/**
	 * Push the content of the posts when post status changed from trashed to published.
	 *
	 * @since    1.0.0
	 */
	public function qai_add_page_to_app_on_restore($post_id)
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

	/**
	 * Returns total number of posts/pages in website.
	 *
	 * @since    1.0.0
	 */
	public function qai_total_posts()
	{
		$total_posts = 0;
		$total_posts += (int) wp_count_posts('post')->publish;
		$total_posts += (int) wp_count_posts('page')->publish;
		echo $total_posts;
		wp_die();
	}

	/**
	 * Returns website links based on the pagination.
	 *
	 * @since    1.0.0
	 */
	public function qai_sync_website_links()
	{
		$args = array(
			'post_type' => array('post', 'page'),
			'orderby'    => 'ID',
			'post_status' => 'publish',
			'order'    => 'DESC',
			'posts_per_page' => 10,
			'paged' => $_POST['page']
		);

		$result = new WP_Query($args);
		$api_input = array();

		if ($result->have_posts()) {
			while ($result->have_posts()) {
				$result->the_post();
				$post = $result->post;
				array_push(
					$api_input,
					array("id" => strval($post->ID), "url" => get_the_permalink($post->ID), "content" => $post->post_content)
				);
			}

			if (count($api_input) > 0) {
				$result = $this->qai_add_pages_to_crawl($api_input);
				echo $result;
			}
		}
		wp_die();
	}

	public function qai_add_pages_to_crawl($posts)
	{
		$posts = wp_json_encode(array('pages' => $posts), JSON_HEX_TAG);
		$posts = str_replace(array("\n", "\r"), '', $posts);

		$auth = $this->bot_id . ':' . $this->api_key;
		$curl = new Quaero_Ai_Curl();
		$curl->url($this->app_link . 'insert-pages')
			->method('post')
			->headers(array('Authorization: ' . $auth))
			->data($posts)
			->send();

		// check status code of our request
		if ($curl->info['http_code'] == 200) {
			$curl->close();
			// API Route Added
			return true;
		} else {
			return $curl->info['http_code'];
		}
		$curl->close();
	}

	public function qai_page_recrawl($post_id)
	{
		global $post;
		if ($post->post_type == 'post' || $post->post_type == 'page') {
			$this->qai_add_page_to_crawl($post);
		}
	}
}
