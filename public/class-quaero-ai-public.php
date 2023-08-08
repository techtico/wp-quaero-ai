<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/public
 * @author     Techtico <ritesh@techtico.io>
 */
class Quaero_Ai_Public
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
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script($this->plugin_name, 'https://search-gpt-sdk.pages.dev/main-bundle.js', array('jquery'), $this->version, false);
	}

	/**
	 * Register the Custom JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function qai_load_search_script()
	{
		$api_key = get_option('qai_api_key');
		$bot_code = get_option('qai_bot_id');
?>
		<script>
			<?php if ($api_key != '' && $bot_code != '') : ?>
				jQuery(document).ready(function() {
					window.Quaeroai.init({
						apiKey: '<?= $api_key ?>',
						botCode: '<?= $bot_code ?>'
					})
				});
			<?php else : ?> console.log('Api Key or Bot Id is incorrect');
			<?php endif; ?>
		</script>
<?php
	}
}
