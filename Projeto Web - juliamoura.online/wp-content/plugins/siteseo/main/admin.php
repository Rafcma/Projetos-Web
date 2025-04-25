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

class Admin {
	static function init(){
		add_action('admin_enqueue_scripts', '\SiteSEO\Admin::enqueue_script');
		add_action('admin_menu', '\SiteSEO\Admin::add_menu');
	}
	
	static function add_menu(){
		$capability = 'manage_options';
		$siteseo_icon = SITESEO_ASSETS_DIR.'/img/logo-24.svg';
		add_menu_page(__('Dashboard', 'siteseo'), 'SiteSEO', $capability, 'siteseo', 'SiteSEO\settings\Dashbord::dashbord_tab', esc_url($siteseo_icon), 90);
		
		add_submenu_page('siteseo', __('Titles & Metas', 'siteseo'), 'Titles & Metas', $capability, 'siteseo-titles', 'SiteSEO\settings\Titles::menu');
		
		add_submenu_page('siteseo', __('Sitemap Tab', 'siteseo'), 'Sitemaps', $capability, 'siteseo-sitemaps', 'SiteSEO\settings\Sitemap::menu');
		
		add_submenu_page('siteseo', __('Social Networks', 'siteseo'), 'Social Networks', $capability, 'siteseo-social', 'SiteSEO\settings\Social::menu');
		
		add_submenu_page('siteseo', __('Analytics', 'siteseo'), 'Analytics', $capability, 'siteseo-analytics', 'SiteSEO\settings\Analytics::menu');
		
		add_submenu_page('siteseo', __('Instant Indexing', 'siteseo'), 'Instant Indexing', $capability, 'siteseo-instant-indexing', 'SiteSEO\settings\Instant::menu');
		
		add_submenu_page('siteseo', __('Image SEO & Advanced settings', 'siteseo'), 'Advanced', $capability, 'siteseo-advanced', 'SiteSEO\settings\Advanced::menu');
		
		add_submenu_page('siteseo', __('Tools', 'siteseo'), 'Tools', $capability, 'siteseo-tools', 'SiteSEO\settings\Tools::menu');
	}
	

	static function enqueue_script(){
		
		if(empty($_GET['page']) || strpos($_GET['page'], 'siteseo') === FALSE){
			return;
		}
		
		wp_enqueue_media();
		
		wp_enqueue_script('siteseo-admin-js', SITESEO_DIR_URL.'assets/js/admin.js', ['jquery'], SITESEO_VERSION, true);
		wp_enqueue_style('siteseo-admin-bar', SITESEO_DIR_URL . 'assets/css/admin-bar.css');
		wp_enqueue_style('siteseo-admin-pages', SITESEO_DIR_URL . 'assets/css/siteseo.css');

	}
}