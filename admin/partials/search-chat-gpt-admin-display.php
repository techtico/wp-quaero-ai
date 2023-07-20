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
    <h2>Search Chat GPT</h2>

    <div class="scg-tabset">
        <!-- Tab 1 -->
        <input type="radio" name="scg-tabset" id="tab1" aria-controls="settings" checked />
        <label for="tab1">
            <div class="title">
                Settings
            </div>
        </label>

        <!-- Tab 2 -->
        <input type="radio" name="scg-tabset" id="tab2" aria-controls="progress" />
        <label for="tab2">
            <div class="title">
                Progress
            </div>
        </label>

        <!-- Tab 3 -->
        <input type="radio" name="scg-tabset" id="tab3" aria-controls="help" />
        <label for="tab3">
            <div class="title">
                Help
            </div>
        </label>

        <div class="scg-tab-panels">
            <section id="settings" class="tab-panel">
                <form method="post" action="options.php">
                    <?php
                    settings_fields('scg_config_settings_group');
                    do_settings_sections('scg_config_settings_group');
                    submit_button();
                    ?>
                </form>
            </section>
            <section id="progress" class="tab-panel">
                <h2>Sync the website links to App Bot</h2>
                <div class="scg-sync-wrapper">
                    <div class="scg-progress-element" style="display:none">
                        <p class="scg-progress-label">Sync Progress<span class="scg-progress-percentage">0%</span></p>
                        <div class="scg-progress-container" data-progress="0%">
                            <div class="scg-progress-done">
                                <progress id="scg-search-sync" value="0" max="100"> 0% </progress>
                            </div>
                        </div>
                    </div>
                    <button id="sync-website-links" class="button button-primary">Sync website links</button>
            </section>
            <section id="help" class="tab-panel">
                <h2>Help</h2>
                <ul>
                    <li>You can use the Search Bar by pressing <span class="scg-code"> ⌘ + K </span> &nbsp; or &nbsp;<span class="scg-code"> ⊞ + K</span> in the frontend (website)</li>
                    <li>For any queries please mail to ritesh@techtico.io</li>
                </ul>
            </section>
        </div>
    </div>