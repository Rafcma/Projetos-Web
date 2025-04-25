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

class Instant{

    static function menu(){
		global $siteseo;

		Dashbord::admin_header();
		
		$indexing_toggle = isset($siteseo->setting_enabled['toggle-instant-indexing']) ? $siteseo->setting_enabled['toggle-instant-indexing'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');
		
		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_siteseo_general';
		
		$instant_subtabs = [
			'tab_siteseo_general' => esc_html__('General', 'siteseo'),
			'tab_siteseo_settings' => esc_html__('Settings', 'siteseo'),
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';
		wp_nonce_field('siteseo_instant_indexing');

		Dashbord::render_toggle('Instant Indexing - SiteSEO', 'indexing_toggle', $indexing_toggle, $nonce);	
		echo'<div id="siteseo-tabs" class="wrap">
		<div class="nav-tab-wrapper">';

		foreach($instant_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}
		
		echo'</div>
		<div class"tab-content-wrapper">
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseo_general' ? ' active' : '') . '" id="tab_siteseo_general">';
		self::general();
		echo'</div>     
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseo_settings' ? ' active' : '') . '" id="tab_siteseo_settings">';
		self::settings();
		echo'</div>
		</div>';

		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo'</form>';

	}

    static function general(){
        global $siteseo;
		
        echo'<h3 style="siteseo-tabs">Instant Indexing</h3>
        <div class="siteseo_wrap_label">
        <p class="description">You can use the Indexing API to tell Google & Bing to update or remove pages from the Google / Bing index. The process can take a few minutes. You can submit your URLs in batches of 100 (max 200 requests per day for Google).</p>
        </div>

        <div class="siteseo-notice">
		    <span class="dashicons dashicons-info"></span>
            <div><h3>' . esc_html__('How does this work?', 'siteseo') . '</h3>
                <ol><li>' . wp_kses_post(__('Setup your Google / Bing API keys from the <strong>Settings</strong> tab', 'siteseo')) . '</li>
                    <li>' . wp_kses_post(__('<strong>Enter your URLs</strong> to index in the field below', 'siteseo')) . '</li>
                    <li>' . wp_kses_post(__('<strong>Save changes</strong>', 'siteseo')) . '</li>
                    <li>' . wp_kses_post(__('Click <strong>Submit URLs to Google & Bing</strong>', 'siteseo')) . '</li>
                </ol>
		    </div>
	    </div>

        <table class="form-table">
            <tbody >
                <tr>
                    <th scope="row">Select search engines</th>
                    <td> 
                        <div class="siteseo_wrap_label"><label for="siteseo_search_engines">
				            <input id="siteseo_search_engines" name="siteseo_options[search_engine_bring]" type="checkbox"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Google', 'siteseo') . 
			            '</label></div>
                        <label for="siteseo_search_engines">
				            <input id="siteseo_search_engines" name="siteseo_options[search_engine_google]" type="checkbox"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Bing', 'siteseo') . 
			            '</label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Which action to run for Google?</th>
                    <td>
                        <div class="siteseo_wrap_label"><label for="siteseo_search_engines">
                                <input id="siteseo_search_engines" name="siteseo_options[instant_indexing_actions]" type="radio"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Update URLs', 'siteseo') . 
                        '</label></div>
                        <label for="siteseo_search_engines">
                            <input id="siteseo_search_engines" name="siteseo_options[instant_indexing_batch]" type="radio"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove URLs (URL must return a 404 or 410 status code or the page contains <meta name="robots" content="noindex" /> meta tag)', 'siteseo') . 
                        '</label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Submit URLs for indexing</th>
                    <td>
                        <textarea placeholder="Enter one URL per line to submit them to search engines (max 100 URLs)"></textarea>
                    </td>
                </tr>
            </tbody>
        </table><input type="hidden" name="siteseo_options[general]" value="1"/>';
    }

    static function settings(){

        echo'<h3 class="siteseo-tabs">Settings</h3>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Instant Indexing Google API Key</th>
                    <td>    
                        <textarea name="siteseo_options[google_api_key]" placeholder="Paste your Google Json key file here"></textarea>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Instant Indexing Bing API Key</th>
                    <td>
                        <input type="text" name="siteseo_options[bring_api_key]" placeholder="Enter your Bing Instant Indexing API " value="">
                        <p class="description">The Bing Indexing API key is automatically generated. Click Generate key if you want to recreate it, or if it missing.</p>
                        <p class="description">A key should look like this: ZjA2NWI3ZWM3MmNhNDRkODliYmY0YjljMzg5YTk2NGE=</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Automate URL Submission</th>
                    <td> 
                        <label for="siteseo_search_engines">
                            <input id="siteseo_search_engines" name="siteseo_options[auto_submission]" type="checkbox"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Enable automatic URL submission for IndexNow API', 'siteseo') . 
                        '</label>
                        <div class="siteseo_wrap_label">
                            <p class="description">Notify search engines using IndexNow protocol (currently Bing and Yandex) whenever a post is created, updated or deleted.</p>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table><input type="hidden" name="siteseo_options[setting_tab]" value="1"/>';
    }
	
	static function save_settings(){
		global $siteseo;
		
		check_admin_referer('siteseo_instant_indexing');
		
		if(current_user_can('manage_options')|| !is_admin()){
			return;
		}
		
		$options = [];
		
		
		if(empty($_POST['siteseo_options'])){
			return;
		}
		
		if(isset($_POST['siteseo_options']['general'])){
			// general tab
			$options['engines']['bring'] = isset($_POST['siteseo_options']['search_engine_bring']);
			$options['engines']['google'] = isset($_POST['siteseo_options']['search_engine_google']);
			$options['instant_indexing_google_action'] = isset($_POST['siteseo_options']['instant_indexing_actions']);
			$options['instant_indexing_manual_batch'] = isset($_POST['siteseo']['instant_indexing_batch']);
		}
		
		if(isset($_POST['siteseo_options']['settings'])){
			// setting tab
			$options['instant_indexing_google_api_key'] = isset($_POST['siteseo_options']['google_api_key']);
			$options['instant_indexing_bing_api_key'] = isset($_POST['siteseo_options']['bring_api_key']);
			$options['instant_indexing_automate_submission'] = isset($_POST['siteseo_options']['auto_submission']);
		}
		
		upadte_option('siteseo_instant_indexing_option_name',$options);
	}
}
