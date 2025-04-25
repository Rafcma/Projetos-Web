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

class Dashbord{

	static function dashbord_tab(){
		global $siteseo;
		
		self::admin_header();
		
		$titles_meta_toggle = isset($siteseo->setting_enabled['toggle-titles']) ? $siteseo->setting_enabled['toggle-titles'] : '';
		$sitemap_toggle = isset($siteseo->setting_enabled['toggle-xml-sitemap']) ? $siteseo->setting_enabled['toggle-xml-sitemap'] : '';
		$social_toggle = isset($siteseo->setting_enabled['toggle-social']) ? $siteseo->setting_enabled['toggle-social'] : '';
		$advanced_toggle = isset($siteseo->setting_enabled['toggle-advanced']) ? $siteseo->setting_enabled['toggle-advanced'] : '';
		$analytics_toggle = isset($siteseo->setting_enabled['toggle-google-analytics']) ? $siteseo->setting_enabled['toggle-google-analytics'] : '';
		$indexing_toggle = isset($siteseo->setting_enabled['toggle-instant-indexing']) ? $siteseo->setting_enabled['toggle-instant-indexing'] : '';
		$titels_meta_toggle = '';

		$nonce = wp_create_nonce('siteseo_toggle_nonce');
		
		$siteseo_dashbord_img = SITESEO_ASSETS_DIR.'/img/seo-get-started.jpg';
		$siteseo_loginizer_product = SITESEO_ASSETS_DIR.'/img/loginizer_product.png';

		echo'<div style="margin-top:8%;" id="siteseo-dashbord" class="siteseo-option">
			<div class="siteseo-dashbord-container">
			   <div class="siteseo-text-content">
					<h2>HOW-TO GET STARTED</h2><h1>Welcome to SiteSEO!</h1>
					<p>Launch our installation wizard to quickly and easily configure the basic SEO settings for your site. Browse our video guides to go further. Cant find the answers to your questions? Open a ticket from your customer area. A happiness engineer will be happy to help you.</p>
					<div class="siteseo-buttons">
					<a class="get-started" href="#">Get started</a>
					<a class="dismiss" href="#">Dismiss</a>
					</div>
				</div>
				<div class="siteseo-image-content"><img alt="Illustration of a megaphone with various icons representing SEO and digital marketing" height="470" src="'.esc_url($siteseo_dashbord_img).'" width="470"/>
				</div>
			</div>
		</div>
	<div class="siteseo_dashbord_tab">
		<div style="margin-top:4%;margin-right:23%;" class="siteseo-option">
			<h1>'.esc_html('SiteSEO Feature Management','siteseo').'</h1>
			<div class="siteseo-dashbord-container">
				<div class="siteseo-card">
					<span class="dashicons dashicons-edit-large"></span>
					<h3>Titles &amp; Metas</h3>
					<p>Manage all your titles and metas for post types, taxonomies more...</p>
					<div class="settings">
						<a href="admin.php?page=siteseo-titles">Settings</a>';
						self::render_toggle('Titles & Metas -SiteSEO', 'titles_meta_toggle', $titles_meta_toggle, $nonce, true);
				   echo'</div>
				</div>
	  
	  
			<div class="siteseo-card">
				<span class="dashicons dashicons-networking"></span>
				<h3>XML & HTML Sitemaps</h3>
				<p>Manage your XML - Image - Video- Taxonomies - HTML Sitemap more...</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-sitemaps">Settings</a>';
					self::render_toggle('Sitemaps - SiteSEO', 'sitemap_toggle', $sitemap_toggle, $nonce,true);
				echo'</div>
			</div>
	  
			<div class="siteseo-card">
				<span class="dashicons dashicons-share"></span>
				<h3>Social Networks</h3>
				<p>Open Graph, Twitter Card, Google Knowledge Graph and more...</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-social">Settings</a>';
					self::render_toggle('Social - SiteSEO', 'social_toggle', $social_toggle, $nonce,true);
				echo'</div>
			</div>
	  
			<div class="siteseo-card">
				<span class="dashicons dashicons-performance"></span>
				<h3>Analytics</h3>
				<p>Track everything about your visitors with Analytics/Matomo more...</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-analytics">Settings</a>';
					self::render_toggle('Analytics - SiteSEO', 'analytics_toggle', $analytics_toggle, $nonce,true);
				echo'</div>
			</div>
	  
			<div class="siteseo-card">
				<span class="dashicons dashicons-superhero"></span>
				<h3>Instant Indexing</h3>
				<p>Ping Google & Bing to quickly index your content. Updated and  remove submit URLs</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-instant-indexing">Settings</a>';
					self::render_toggle('Instant indexing - SiteSEO', 'indexing_toggle', $indexing_toggle, $nonce,true);
				echo'</div>
			</div>
			
			<div class="siteseo-card">
				<span class="dashicons dashicons-format-gallery"></span>
				<h3>Image SEO</h3>
				<p>Optimize your images for SEO. Configure advanced settings more...</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-advanced">Settings</a>';
					self::render_toggle('Advanced - SiteSEO', 'advanced_toggle', $advanced_toggle, $nonce,true);
				echo'</div>
			</div>
			
			<div class="siteseo-card">
				<span class="dashicons dashicons-upload"></span>
				<h3>Tools</h3>
				<p>Import/Export plugin settings from site to site. Reset settings more...</p>
				<div class="settings">
					<a href="admin.php?page=siteseo-tools">Settings</a>
					<div class="siteseo-toggle-container">
						<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
						<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
					</div>
				</div>
			</div>';
		
			if(!defined('SITESEO_PRO_VERSION')){
				echo'<div class="siteseo-card">
					<span class="dashicons dashicons-cart"></span>
					<h3>WooCommerces SEO</h3>
					<p>Optimize your images for SEO. Configure advanced settings more...</p>
					<div class="pro-feature">Pro Feature</div>
					<div class="settings">
						<a>Settings</a>
						<div class="siteseo-toggle-container">
							<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
							<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
							<span id="lock" class="dashicons dashicons-lock"></span>
						</div>
					</div>
				</div>
				
				<div class="siteseo-card">
					<span class="dashicons dashicons-money-alt"></span>
					<h3>Easy Digital Downloads</h3>
					<p>Optimize your images for SEO. Configure advanced settings more...</p>
					<div class="pro-feature">Pro Feature</div>
					<div class="settings">
						<a>Settings</a>
						<div class="siteseo-toggle-container">
							<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
							<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
							<span id="lock" class="dashicons dashicons-lock"></span>
						</div>
					</div>
				</div>
				
				<div class="siteseo-card">
					<span class="dashicons dashicons-code-standards"></span>
					<h3>Page Speed</h3>
					<p>Optimize your images for SEO. Configure advanced settings more...</p>
					<div class="pro-feature">Pro Feature</div>
					<div class="settings">
						<a>Settings</a>
						<div class="siteseo-toggle-container">
							<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
							<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
							<span id="lock" class="dashicons dashicons-lock"></span>
						</div>
					</div>
				</div>
				
				<div class="siteseo-card">
					<span class="dashicons dashicons-list-view"></span>
					<h3>Structured Data</h3>
					<p>Optimize your images for SEO. Configure advanced settings more...</p>
					<div class="pro-feature">Pro Feature</div>
					<div class="settings">
						<a>Settings</a>
						<div class="siteseo-toggle-container">
							<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
							<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
							<span id="lock" class="dashicons dashicons-lock"></span>
						</div>
					</div>
				</div>
				
				<div class="siteseo-card">
					<span class="dashicons dashicons-location"></span>
					<h3>Local Business</h3>
					<p>Optimize your images for SEO. Configure advanced settings more...</p>
					<div class="pro-feature">PRO Feature</div>
					<div class="settings">
						<a>Settings</a>
						<div class="siteseo-toggle-container">
							<div class="siteseo-toggle-switch ' . ($titels_meta_toggle ? 'active' : '') . '" id="siteseo-toggleSwitch-titles" data-nonce="' . $nonce . '"></div>
							<input type="hidden" name="siteseo_options[titels_meta_toggle]" id="titels_meta_toggle" value="' . $titels_meta_toggle . '">
							<span id="lock" class="dashicons dashicons-lock"></span>
						</div>
					</div>
				</div>';
				
			}
			
	echo'</div></div>';
	
	echo'<div style="margin-bottom:1070px;" class="siteseo-ad-container">
		<div class="siteseo-ad-header">
			<div class="siteseo-ad-logo">
				<img alt="Shield icon with a lock in the center" height="50" src="'.esc_url($siteseo_loginizer_product).'" width="150"/>
			</div>
		  </div><div class="siteseo-ad-description">Protect your WordPress website from
			<em>unauthorized access and malware</em>:
		   </div><div class="siteseo-ad-features"><p>BruteForce Protection</p>
			<p>reCaptcha</p>
			<p>Two Factor Authentication</p>
			<p>Black/Whitelist IP</p>
			<p>Detailed Logs</p>
			<p>Extended Lockouts</p>
			<p>2FA via Email</p>
			<p>And many more ...</p>
		   </div><a class="siteseo-ad-button" target="-blank" href="https://wordpress.org/plugins/loginizer/">Visit Loginizer</a>
		  </div> 
		
	</div>';
	
	}
	
	static function admin_header(){
		
		 echo'<div class="siteseo-navbar">
			<div class="logo">
				<img alt="siteseo logo" height="30" src="'. esc_url(SITESEO_ASSETS_DIR).'/img/logo-24.svg'.'" width="40"/>
					<div class="breadcrumb">
						<a href="#">Home</a>
						<span>/</span>
						<a class="active" href="">'.esc_html(get_admin_page_title()).'</a>
					</div>
			</div>
			<div class="links">
				<a target="_blank" href="https://siteseo.io/docs/">Docs</a>
				<a target="_blank" class="support" href="https://softaculous.deskuss.com/open.php">Support</a>
			</div>
		</div>';
	}
	
	
	static function render_toggle($title, $toggle_key, $toggle_state, $nonce, $simple = false){
		$is_active = $toggle_state ? 'active' : '';
		$state_text = $toggle_state ? 'Disable' : 'Enable';

		// for dashbord screen
		if($simple){
			echo'<div class="siteseo-toggle-container">
					<div class="siteseo-toggle-switch ' . esc_attr($is_active) . '" id="siteseo-toggleSwitch-' . esc_attr($toggle_key) . '" data-nonce="' . esc_attr($nonce) . '" data-toggle-key="' . esc_attr($toggle_key) . '" data-action="save_' . esc_attr($toggle_key) . '"></div>
					<input type="hidden" name="siteseo_options[' . esc_attr($toggle_key) . ']" id="' . esc_attr($toggle_key) . '" value="' . esc_attr($toggle_state) . '">
				</div>';
		} else{
			
			echo'<div class="siteseo-toggle-container">
					<span id="siteseo-tab-title"><strong>' . esc_html($title) . '</strong></span>
					<div class="siteseo-toggle-switch ' . esc_attr($is_active) . '" id="siteseo-toggleSwitch-' . esc_attr($toggle_key) . '" data-nonce="' . esc_attr($nonce) . '" data-toggle-key="' . esc_attr($toggle_key) . '" data-action="save_' . esc_attr($toggle_key) . '"></div>
					<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
					<p class="toggle_state_' . esc_attr($toggle_key) . '">' . esc_html($state_text) . '</p>
					<input type="hidden" name="siteseo_options[' . esc_attr($toggle_key) . ']" id="' . esc_attr($toggle_key) . '" value="' . esc_attr($toggle_state) . '">
				</div>';
		}
	}

}