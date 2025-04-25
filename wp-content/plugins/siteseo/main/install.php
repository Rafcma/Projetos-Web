<?php
/*
* SITESEO
* https://siteseo.io
* (c) SiteSEO Team
*/

namespace SiteSEO;

if(!defined('ABSPATH')){
	die('Hacking Attempt !');
}

class Install{
	static function activate(){
		update_option('siteseo_version', SITESEO_VERSION);
	}

	static function deactivate(){
		delete_option('siteseo_version');
	}

	static function uninstall(){
		delete_option('siteseo_version');
		delete_option('siteseo_toggle');
		delete_option('siteseo_titles_option_name');
		delete_option('siteseo_titles_option_name');
		delete_option('siteseo_social_option_name');
		delete_option('siteseo_advanced_option_name');
		delete_option('siteseo_instant_indexing_option_name');
		delete_option('siteseo_xml_sitemap_option_name');
		delete_option('siteseo_google_analytics_option_name');
	}

}