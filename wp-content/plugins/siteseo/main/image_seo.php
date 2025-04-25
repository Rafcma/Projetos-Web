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

class Image_seo(){
	
	static function tags(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-advanced'])){
			return;
		}
		
		if($siteseo->advanced_settings['attachment']){
			
		}
		
	}

}