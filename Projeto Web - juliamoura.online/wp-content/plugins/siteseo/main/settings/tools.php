<?php
/*
* SITESEO
* https://siteseo.io
* (c) SiteSEO Team
*/

namespace SiteSEO\settings;

if(!defined('ABSPATH')){
	die('Hacking Attempt !');
}


class Tools{

    static function menu(){

		Dashbord::admin_header();

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_settings'; // Default tab

		$tools_subtabs = [
			'tab_settings' => esc_html__('Settings', 'siteseo'),
			'tab_plugins' => esc_html__('Plugins', 'siteseo'),
			'tab_reset' => esc_html__('Reset', 'siteseo')
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';
		wp_nonce_field('sitseo_title_settings');

		echo'<div class="siteseo-toggle-container">
		<span id="siteseo-tab-title"><strong>Tools - SiteSEO</strong></span></div>';

        echo'<div id="siteseo-tabs" class="wrap">
        <div class="nav-tab-wrapper">';

		foreach($tools_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo'</div>
		<div class="tab-content-wrapper">
		<div class="siteseo-tab' . ($current_tab == 'tab_settings' ? ' active' : '') . '" id="tab_settings">';
		self::settings();
		echo'</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_plugins' ? ' active' : '') . '" id="tab_plugins">';
		self::plugins();
		echo'</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_reset' ? ' active' : '') . '" id="tab_reset">';
		self::reset();
		echo'</div>
		</div>';

	}

    static function settings(){

        echo'<h3>Settings</h3>
        <h4>Export plugin settings</h4>
		<div class="siteseo_wrap_label">
			<p class="description">'.esc_html('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site','siteseo').'</p>
        </div>
		<div class="siteseo_wrap_label">
			<button class="btn btnSecondary">' . esc_html('Export', 'siteseo') . '</button>
        </div>
        <span class="line"></span>
        <h3>Import plugin settings</h3>
		<div class="siteseo_wrap_label">
			<p class="description">Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.</p>
        </div>
		<div class=siteseo_wrap_label>
		<input type="file" />
		</div>
        <div class="siteseo_wrap_label"><button class="brn btnSecondary">' . esc_html('Import', 'siteseo') . '</button>
        </div>';
    }

    static function plugins(){

        echo'<h3>Plugins</h3>
        <h4>Import posts and terms metadata from</h4>';

    }

    static function reset(){

        echo'<h3>Reset All Settings</h3>
		<button class="btn btnSecondary">'.esc_html('Reset notices','siteseo').'</button>';
    }


}
