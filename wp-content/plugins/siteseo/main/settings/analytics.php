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

class Analytics{
	
	static function menu(){
		global $siteseo;

		Dashbord::admin_header();

		$analytics_toggle = isset($siteseo->setting_enabled['toggle-google-analytics']) ? $siteseo->setting_enabled['toggle-google-analytics'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_google_analytics'; // Default tab

		$analytics_sub_tags = [
			'tab_google_analytics' => esc_html__('Google Analytics', 'siteseo'),
			'tab_matomo' => esc_html__('Matomo', 'siteseo'),
			'tab_clarity' => esc_html__('Clarity', 'siteseo'),
			'tab_advanced' => esc_html__('Advanced', 'siteseo'),
			'tab_cookie' => esc_html('Cookie','siteseo'),
			'tab_custom_tracking' => esc_html('Custom Tracking'),
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';
		wp_nonce_field('sitseo_title_settingss');

		Dashbord::render_toggle('Analytics - SiteSEO', 'analytics_toggle', $analytics_toggle, $nonce);
        
		echo'<div id="siteseo-tabs" class="wrap">
			<div class="nav-tab-wrapper">';

		foreach($analytics_sub_tags as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo'</div>
		<div class="tab-content-wrapper">
		<div class="siteseo-tab' . ($current_tab == 'tab_google_analytics' ? ' active' : '') . '" id="tab_google_analytics">';
		self::google_anlytics();
		echo'</div>     
		<div class="siteseo-tab' . ($current_tab == 'tab_matomo' ? ' active' : '') . '" id="tab_matomo">';
		self::matomo();
		echo'</div>     
		<div class="siteseo-tab' . ($current_tab == 'tab_clarity' ? ' active' : '') . '" id="tab_clarity">';
		self::clarity();
		echo'</div>     
		<div class="siteseo-tab' . ($current_tab == 'tab_advanced' ? ' active' : '') . '" id="tab_advanced">';
		self::advanced();
		echo'</div>     
		<div class="siteseo-tab' . ($current_tab == 'tab_cookie' ? ' active' : '') . '" id="tab_cookie">';
		self::cookies();
		echo'</div>  
		<div class="siteseo-tab' . ($current_tab == 'tab_custom_tracking' ? 'active' : '').'" id="tab_custom_tracking">';
		self::custom_tracking();
		echo'</div>
		</div>';
		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo'</form>';
	}
	
	static function custom_tracking(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		echo'<h3 class="siteseo-tabs">Custom Tracking</h3>
			<P class="description">'.esc_html('Add your own scripts like GTM or Facebook Pixel by copy and paste the provided code to the HEAD/BODY or FOOTER.','siteseo').'</p>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">[HEAD] Add an additional tracking code (like Facebook Pixel, Hotjar...)</th>
						<td>
							<textarea name="siteseo_options" rows="16" placeholder="Paste your tracking code here like Google Tag Manager (head). Do NOT paste GA4 or Universal Analytics codes here. They are automatically added to your source code"></textarea>
							<p class="description">This code will be added in the head section of your page.</p>
						</td>
					</tr>
					
					<tr>
						<th scope="row">[BODY] Add an additional tracking code (like Google Tag Manager...)</th>
						<td>
							<textarea name="siteseo_options[]" rows="16" placeholder="This code will be added just after the opening body tag of your page"></textarea>
						</td>
					</tr>
					
					<tr>
						<th scope="row">BODY (FOOTER)] Add an additional tracking code (like Google Tag Manager...)</th>
						<td>
							<textarea name="siteseo_options[]" rows="16" placeholder="Paste your tracking code here(footer)"></textarea> 
						</td>
					</tr>
				</tbody>
			</table>';
		
	}
	
	static function cookies(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		echo'<h3 class="siteseo-tabs">Cookies</h3>
		<p class="description">Manage user consent for GDPR and customize your cookie bar easily.</p>
		<p class="description">Works with Google Analytics and Matomo.</p>
		<table>
			<tbody class="form-table">
				<tr>
					<th scope="row" style="user-select:auto;">Where to load the cookie bar?</th>
					<td>
						<select>
						</select>
					</td>
				</tr>
				
				<tr>
					<th scope"user-select:auto">Analytics tracking opt-in</th>
					<td>
						<label>
							<input name="siteseo_options[]" type="checkbox" '.(!empty($temp) ? 'checked="yes"' : 'value="1"').' />
                                    '.__('Request user consent for analytics tracking (required by GDPR)', 'siteseo').'
                        </label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto:">Allow user to change its choice</th>
					<td>
						<label for="siteseo_cookies">
							<input name="siteseo_options[]" type="checkbox" '.(!empty($temp) ? 'checked="yes"' : 'value="1"').' />
							Allow user to chnage its choice about cookies'.__('Request user consent for analytics tracking (required by GDPR)', 'siteseo').'
                        </label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Consent message for user tracking</th>
					<td>
						<textarea name="siteseo_options[]" ></textarea>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options[]">Accept button for user tracking</th>
					<td>
						<input type="text" name="siteseo_options[]" value="" placeholder="Accept"> 
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options[]">Close button</th>
					<td>
						<input type="text" name="siteseo_options[]" value="" placeholder="default:X">
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options[]" >Edit cookies button</th>
					<td>
						<input type="text" name="siteseo_options[]" value="" placeholder="default:Manage cookie">
						
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options[]">User consent cookie expiration date</th>
					<td>
						<input type="number"  name="siteseo_options[]" value="" >
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options">Cookie bar position</th>
					<td>
						<select>
						</select>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="siteseo_options">Text alignment</th>
					<td>
						<select>
						</select>
					</td>
				</tr>
				
			</tbody>
		</table>'; 
		
	}
	
	static function matomo(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		$matomo_subtabs = [
			'tracking' => 'Tracking',
		];
		
		echo'<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<div class="siteseo-container">';
								$is_first = true;
								foreach($matomo_subtabs as $post_key => $post_val){
									$active_class = $is_first ? 'active' : '';
									echo '<a href="#'.$post_key.'" class="'.$active_class.'">'.$post_val.'</a>';
									$is_first = false;
								}
							echo'</div>
					</th>
					<td>
						<h3>Matomo</h3>
						<div class="siteseo_wrap_label">
						<p class="description">Use Matomo to track your users with privacy in mind. We support both On Premise and Cloud installations.</p>
						</div>
						<span class="line"></span>
						<h3>Tracking</h3>
					
					</td>
				</tr>
			</tbody>
			</table>';
		
	}
	
	static function advanced(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		$adavnced_subtabs =[
			'custom-dimensions' => 'Custom Dimensions',
			'Misc' => 'Misc',
		];

		echo'<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<div class="siteseo-container">';
								$is_first = true;
								foreach($adavnced_subtabs as $post_key => $post_val){
									$active_class = $is_first ? 'active' : '';
									echo '<a href="#'.$post_key.'" class="'.$active_class.'">'.$post_val.'</a>';
									$is_first = false;
								}
							echo'</div>
						</th>
						<td>
							
						
						</td>
					</tr>
				</tbody>
			</table>';
		
	}
	
	static function clarity(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		echo'<h3 class="siteseo-tabs">Microsoft Clarity</h3>
		<p class="description">Use Microsoft Clarity to capture session recordings, get instant heatmaps and powerful Insights for Free. Know how people interact with your site to improve user experience and conversions.</p>
		
		<div class="siteseo-notice">
			<span class="dashicons dashicons-info"></span>
			<p>'.
			 printf(wp_kses_post(__('Create your first Microsoft Clarity project <a href="%s" target="_blank">here</a>.', 'siteseo')), esc_url('https://clarity.microsoft.com/'))
			.'</p>
		</div>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select:auto;">Enable Microsoft Clarity</th>
					<td>
						<input type="checkbox" name="siteseo_options[]" value="">
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Enter your Clarity project ID</th>
					<td>
						<input type="text" placeholder="Enter your Project Id" value="" >
					</td>
				</tr>
			</tbody>
		</table>';
		
	}
	
	static function google_anlytics(){
		global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		echo'<h3 class="siteseo-tabs">Google Anlytics</h3>
		<p class="description">Link your Google Analytics to your website. The tracking code will be automatically added to your site.</p>';
		
	}
	
		
	static function save_settings(){
		
	}

}
