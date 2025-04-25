<?php
/*
* SITESEO
* https://siteseo.io
* (c) SITSEO Team
*/

namespace SiteSEOPro;

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

class Ajax{

	static function hooks(){
		add_action('wp_ajax_siteseo_pro_get_pagespeed_insights', '\SiteSEOPro\Ajax::get_pagespeed');
		add_action('wp_ajax_siteseo_pro_pagespeed_insights_remove_results', '\SiteSEOPro\Ajax::delete_speed_scores');
	}

	static function get_pagespeed(){
		check_ajax_referer('siteseo_pro_nonce', 'nonce');
		
		if(!current_user_can('manage_options')){
			wp_send_json_error(__('You do not have enough privilege to use this feature', 'siteseo-pro'));
		}

		global $siteseo;

		$api_url = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
		$api_key = $siteseo->pro['ps_api_key'];
		$site_url = isset($_POST['test_url']) ? sanitize_url($_POST['test_url']) : site_url();
		
		if(empty($api_key)){
			wp_send_json_error(__('You have not saved the API key', 'siteseo-pro'));
		}
		
		if(empty($site_url)){
			wp_send_json_error(__('The URL you have provided is not valid', 'siteseo-pro'));
		}
		
		$device = (!empty($_REQUEST['is_mobile']) && $_REQUEST['is_mobile'] != 'false') ? 'mobile' : 'desktop';
		$request_url = $api_url . '?url=' . urlencode($site_url) . '&strategy='.$device.'&key='.$api_key;

		$response = wp_remote_get($request_url, array('timeout' => 60)); // 60 sec wait time 

		if(is_wp_error($response)){
			$error_message = is_wp_error($response) ? $response->get_error_message() : $response->get_error_message();

			wp_send_json_error($error_message);
		}

		$body = wp_remote_retrieve_body($response);

		if(empty($body)){
			wp_send_json_error(__('Response body is empty', 'siteseo-pro'));
		}

		$result = json_decode($body, true);
		
		$page_speed = get_option('siteseo_pro_page_speed', []);

		// Handling Pagespeed insight result.
		foreach($result['lighthouseResult']['audits'] as $key => $audit){

			if(isset($audit['title']) && isset($audit['description']) && !isset($audit['details']['type'])){
				$page_speed[$device][$key] = [
					'id' => $audit['id'],
					'score' => $audit['score'],
					'title' => $audit['title'],
					'description' => $audit['description']
				];
			}

			if(isset($audit['details']['type']) && $audit['details']['type'] === 'opportunity'){
				$page_speed[$device]['opportunities'][] = [
					'title' => $audit['title'],
					'description' => $audit['description'],
					'score' => isset($audit['score']) ? $audit['score'] : null
				];
			}

			if(!isset($page_speed[$device]['diagnostics'])){
				$page_speed[$device]['diagnostics'] = [];
			}

			if(isset($audit['score']) && isset($audit['details']['type']) && $audit['score'] <= 0.89 && $audit['details']['type'] != 'opportunity'){
				$page_speed[$device]['diagnostics'][] = [
					'title' => $audit['title'],
					'description' => $audit['description'],
					'score' => isset($audit['score']) ? $audit['score'] : null
				];
			}
		}

		$page_speed[$device]['fetchTime'] = $result['lighthouseResult']['fetchTime'];
		$page_speed[$device]['score'] = $result['lighthouseResult']['categories']['performance']['score'];
		
		update_option('siteseo_pro_page_speed', $page_speed);
		
		wp_send_json_success();
	}
	
	static function delete_speed_scores(){
		check_ajax_referer('siteseo_pro_nonce', 'nonce');
		
		if(!current_user_can('manage_options')){
			wp_send_json_error(__('You do not have enough privilege to use this feature', 'siteseo-pro'));
		}
		
		delete_option('siteseo_pro_page_speed');
		wp_send_json_success();
	}
	
}


