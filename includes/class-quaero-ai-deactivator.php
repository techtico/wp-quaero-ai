<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/includes
 * @author     Techtico <ritesh@techtico.io>
 */
class Quaero_Ai_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		delete_option('scg_api_key');
		delete_option('scg_bot_id');
	}
}
