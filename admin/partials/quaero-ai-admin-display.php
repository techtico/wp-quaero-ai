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
    <h2>Quaero AI Settings</h2>

    <div class="qai-tabset">
        <!-- Tab 1 -->
        <input type="radio" name="qai-tabset" id="tab1" aria-controls="settings" checked />
        <label for="tab1">
            <div class="title">
                Settings
            </div>
        </label>

        <!-- Tab 2 -->
        <input type="radio" name="qai-tabset" id="tab3" aria-controls="help" />
        <label for="tab3">
            <div class="title">
                Help
            </div>
        </label>

        <div class="qai-tab-panels">
            <section id="settings" class="tab-panel">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('qai_config_settings_group');
                    do_settings_sections('qai_config_settings_group');
                    submit_button('Save & Sync Posts');
                    ?>
                </form>

                <div class="qai-sync-wrapper">
                    <button id="sync-website-links" class="button button-primary" style="display:none;">Resync the website links</button>
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
            <section id="help" class="tab-panel">
                <h2>Help</h2>
                <ul>
                    <li>You can use the Search Bar by pressing <span class="qai-code"> ⌘ + K </span> &nbsp; or &nbsp;<span class="qai-code"> ⊞ + K</span> in the frontend (website)</li>
                    <li>For any queries please mail to ritesh@techtico.io</li>
                </ul>
            </section>
        </div>
    </div>
    <?php if (isset($_GET['settings-updated'])) { ?>
        <script>
            jQuery(document).ready(function() {
                jQuery("#sync-website-links").trigger("click");
            });
        </script>
    <?php } ?>
</div>