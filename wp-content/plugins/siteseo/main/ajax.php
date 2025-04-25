<?php
/*
* SITESEO
* https://siteseo.io
* (c) SiteSEO Team
*/

namespace SiteSEO;

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

class Ajax{
	
	static function hooks(){

		add_action('wp_ajax_save_titles_meta_toggle', '\siteSEO\Ajax::save_toggle_state');
		add_action('wp_ajax_save_sitemap_toggle', '\siteSEO\Ajax::save_toggle_state');
		add_action('wp_ajax_save_indexing_toggle', '\siteSEO\Ajax::save_toggle_state');
		add_action('wp_ajax_save_advanced_toggle', '\siteSEO\Ajax::save_toggle_state');
		add_action('wp_ajax_save_social_toggle', '\siteSEO\Ajax::save_toggle_state');
		add_action('wp_ajax_save_analytics_toggle', '\siteSEO\Ajax::save_toggle_state');
		
		add_action('wp_ajax_siteseo_update_htaccess', '\siteSEO\Ajax::siteseo_update_htaccess');
		add_action('wp_ajax_siteseo_create_robots', 'siteSEO\Ajax::siteseo_create_robots');
		add_action('wp_ajax_siteseo_update_robots', 'siteSEO\Ajax::siteseo_update_robots');
	}
	
	static function siteseo_create_robots(){
		
		if(!current_user_can('manage_options')){
			wp_send_json_error(__('You do not have required permission to create robots.txt file.', 'siteseo'));
		}

		ob_start();
		do_robots();
		$robots_txt = ob_get_clean();
		
		$is_public = absint(get_option('blog_public'));
		$robots_txt = apply_filters('robots_txt', $robots_txt, $is_public);
		
		if(file_put_contents(ABSPATH . 'robots.txt', $robots_txt)){
			wp_send_json_success(__('Successfully create the robots.txt file', 'siteseo'));
		}

		wp_send_json_error();
	}
	
	static function save_toggle_state(){
		
		check_ajax_referer('siteseo_toggle_nonce', 'nonce');
		//toggle-instant-indexing
		
		$action = $_POST['action'];
		switch($action){
			case 'save_titles_meta_toggle':
				$toggle_key = 'toggle-titles';
				break;
			case 'save_sitemap_toggle':
				$toggle_key = 'toggle-xml-sitemap';
				break;
			case 'save_indexing_toggle':
				$toggle_key = 'toggle-instant-indexing';
				break;
			case 'save_advanced_toggle':
				$toggle_key = 'toggle-advanced';
				break;
			case 'save_social_toggle':
				$toggle_key = 'toggle-social';
				break;
			case 'save_analytics_toggle':
				$toggle_key = 'toggle-google-analytics';
				break;
			default:
				wp_send_json_error(['message' => 'Invalid action']);
				return;
		}

		$toggle_value = isset($_POST['toggle_value']) ? sanitize_text_field($_POST['toggle_value']) : '0';

		$options = get_option('siteseo_toggle', []);
		$options[$toggle_key] = $toggle_value;
		$updated = update_option('siteseo_toggle', $options);

		if($updated){
			wp_send_json_success([
				'message' => ucfirst($toggle_key) . ' toggle state saved successfully',
				'value' => $toggle_value
			]);
		} else{
			wp_send_json_error(['message' => 'Failed to save toggle state']);
		}
	}
	
	static function siteseo_update_htaccess(){
		
		if(!current_user_can('manage_options')){
			wp_send_json_error(__('You do not have required permission to edit this file.', 'siteseo'));
		}

		$htaccess_enable = isset($_POST['htaccess_enable']) ? intval(sanitize_text_field(wp_unslash($_POST['htaccess_enable']))) : 0;
		$htaccess_rules = isset($_POST['htaccess_code']) ? sanitize_textarea_field(wp_unslash($_POST['htaccess_code'])) : '';

		if(empty($htaccess_enable)){
			wp_send_json_error(__('Please accept the warning first before proceeding with saving the htaccess', 'siteseo'));
		}

		$htaccess_file = ABSPATH . '.htaccess';
		$backup_file = ABSPATH . '.htaccess_backup.siteseo';
		
		if(!is_writable($htaccess_file)){
			wp_send_json_error(__('.htaccess file is not writable so the ', 'siteseo'));
		}

		// Backup .htaccess file
		if(!copy($htaccess_file, $backup_file)){
			wp_send_json_error(__('Failed to create backup of .htaccess file.', 'siteseo'));
		}

		// Update the .htaccess file
		if(file_put_contents($htaccess_file, $htaccess_rules) === false){
			wp_send_json_error(__('Failed to update .htaccess file.', 'siteseo'));
		}

		$response = wp_remote_get(site_url());
		$response_code = wp_remote_retrieve_response_code($response);
		
		// Restore the backup if something goes wrong.
		if($response_code > 299){
			copy($backup_file, $htaccess_file);
			wp_send_json_error(__('There was a syntax error in the htaccess rules you provided as the response to your website with the new htaccess gave response code of', 'siteseo') . ' ' . $response_code);
		}

		wp_send_json_success(__('Successfully updated .htaccess file', 'siteseo'));
	}

	static function siteseo_update_robots(){
		siteseo_check_ajax_referer('siteseo_admin_nonce');
		
		if(!current_user_can('manage_options')){
			wp_send_json_error(__('You do not have required permission to edit this file.', 'siteseo'));
		}
		
		$robots_txt = '';
		if(!empty($_POST['robots'])){
			$robots_txt = sanitize_textarea_field(wp_unslash($_POST['robots']));
		}

		if(empty($robots_txt)){
			wp_send_json_error(__('You have supplied empty robots rules', 'siteseo'));
		}
		
		if(!is_writable(ABSPATH . 'robots.txt')){
			wp_send_json_error(__('robots.txt file is not writable', 'siteseo'));
		}
		
		if(file_put_contents(ABSPATH . 'robots.txt', $robots_txt)){
			wp_send_json_success(__('Successfully update the robots.txt file', 'siteseo'));
		}

		wp_send_json_error(__('Unable to update the robots.txt file', 'siteseo'));
		
	}
}
