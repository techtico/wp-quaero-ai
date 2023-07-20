<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://#
 * @since             1.0.0
 * @package           Quaero_Ai
 *
 * @wordpress-plugin
 * Plugin Name:       Quaero Ai
 * Plugin URI:        https://#
 * Description:       AI-Powered Search Engine for content intensive websites.
 * Version:           1.0.0
 * Author:            Techtico
 * Author URI:        https://#
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quaero-ai
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('QUAERO_AI_VERSION', '1.0.0');

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quaero-ai-deactivator.php
 */
function deactivate_quaero_ai()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-quaero-ai-deactivator.php';
	Quaero_Ai_Deactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_quaero_ai');

/**
 * The core plugin class that is used to define admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-quaero-ai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quaero_ai()
{

	$plugin = new Quaero_Ai();
	$plugin->run();
}
run_quaero_ai();
