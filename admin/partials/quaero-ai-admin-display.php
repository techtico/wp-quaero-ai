<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Quaero_Ai
 * @subpackage Quaero_Ai/admin/partials
 */
?>

<div class="wrap">
    <h2>Welcome to QuaeroAI</h2>
    <p>AI powered search engine and command line for content intensive websites.</p>
    <div class="qai-content-panel">
        <section id="settings">
            <h3>Setup</h3>
            <form method="post" action="options.php">
                <ol class="qai-setup-list">
                    <li>To start, create a QuaeroAI account <a href="https://app.quaeroai.io/signup" target="_blank">here</a></li>
                    <li>Then create a new bot, select "is WordPress site"</li>
                    <li>Copy the API key and paste here
                        <?php
                        settings_fields('qai_config_settings_group');
                        do_settings_sections('qai_config_settings_group');
                        ?>
                    </li>
                    <li>
                        Copy the bot ID and paste here
                        <?php
                        settings_fields('qai_config_settings_group_2');
                        do_settings_sections('qai_config_settings_group_2');
                        ?>
                    </li>
                    <li><?php submit_button('Save'); ?></li>
                    <li><button id="sync-website-links" class="button button-primary">Sync all articles</button></li>
                </ol>
            </form>

            <div class="qai-sync-wrapper">
                <div class="qai-progress-element" style="display:none">
                    <h2>Sync Progress</h2>
                    <p class="qai-progress-label">Sync Progress<span class="qai-progress-percentage">0%</span></p>
                    <div class="qai-progress-container" data-progress="0%">
                        <div class="qai-progress-done">
                            <progress id="qai-search-sync" value="0" max="100"> 0% </progress>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    <div class="qai-content-panel">
        <h3>Embed command line</h3>
        <ol class="qai-setup-list">
            <?php
            $bot_id = get_option("qai_bot_id");
            if ($bot_id) {
                $installation_guide =  'https://app.quaeroai.io/bots/' . $bot_id . '#embeddings';
            } else {
                $installation_guide = 'https://app.quaeroai.io/bots/';
            }
            ?>
            <li>Follow the installation guide <a href="<?php echo $installation_guide; ?>" target="_blank">here</a></li>
            <li>When installed, a use can click <span class="qai-code">cmd/control</span> + <span class="qai-code">K</span> to open the command line. Check the installation guide for more options.
            </li>
        </ol>
    </div>

    <div class="qai-content-panel">
        <h3>Help</h3>
        <p>Please refer Quaeroai documentation or <a href="mailto:shneor@techtico.io">Contact us</a> for any queries.</p>
    </div>

    <?php $bot_synced = get_option("qai_bot_synced");
    if ($bot_synced && $bot_synced != 'synced') { ?>
        <script>
            jQuery(document).ready(function() {
                jQuery("#sync-website-links").trigger("click");
            });
        </script>
    <?php } ?>
</div>