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

class Social_metas{

	static function add_social_graph(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-social'])){
			return;
		}
		
		$org_type = !empty($siteseo->social_settings['social_knowledge_type']) ? esc_attr($siteseo->social_settings['social_knowledge_type']) : '';
		$org_name = !empty($siteseo->social_settings['social_knowledge_name']) ? esc_attr($siteseo->social_settings['social_knowledge_name']) : ''; 
		$org_logo = !empty($siteseo->social_settings['social_knowledge_img']) ? esc_url($siteseo->social_settings['social_knowledge_img']) : '';
		$org_number = !empty($siteseo->social_settings['social_knowledge_phone']) ? esc_attr($siteseo->social_settings['social_knowledge_phone']) : '';
		$org_contact_type = !empty($siteseo->social_settings['social_knowledge_contact_type']) ? esc_attr($siteseo->social_settings['social_knowledge_contact_type']) : '';
		$org_contact_option = !empty($siteseo->social_settings['social_knowledge_contact_option']) ? esc_attr($siteseo->social_settings['social_knowledge_contact_option']) : '';

		$fb_account = !empty($siteseo->social_settings['social_accounts_facebook']) ? esc_url($siteseo->social_settings['social_accounts_facebook']) : '';
		$twitter_account = !empty($siteseo->social_settings['social_accounts_twitter']) ? esc_url($siteseo->social_settings['social_accounts_twitter']) : '';
		$insta_account = !empty($siteseo->social_settings['social_accounts_instagram']) ? esc_url($siteseo->social_settings['social_accounts_instagram']) : '';
		$yt_account = !empty($siteseo->social_settings['social_accounts_youtube']) ? esc_url($siteseo->social_settings['social_accounts_youtube']) : '';
		$pt_account = !empty($siteseo->social_settings['social_accounts_pinterest']) ? esc_url($siteseo->social_settings['social_accounts_pinterest']) : '';

		//description
		$site_url = get_site_url();
		$site_description = get_bloginfo('name');

		//JSON-LD data
		$json_ld = [
			'@context' => 'https://schema.org',
			'@type' => $org_type ?: 'Organization',
			'name' => $org_name,
			'logo' => $org_logo,
			'url' => $site_url,
			'description' => $site_description,
		];

		//contact point
		if(!empty($org_contact_option) && !empty($org_contact_type) && !empty($org_number)){
			$json_ld['contactPoint'] = [
				'@type' => 'ContactPoint',
				'contactType' => $org_contact_type,
				'telephone' => $org_number,
				'contactOption' => $org_contact_option,
			];
		}
		
		$same_as = array_filter([$fb_account, $twitter_account, $insta_account, $yt_account, $pt_account]);
		if(!empty($same_as)){
			$json_ld['sameAs'] = $same_as;
		}

		// Output JSON-LD script
		echo '<script type="application/ld+json" class="siteseo-schema">';
		echo json_encode($json_ld, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		echo '</script>';
	}

	
	
	static function fb_graph(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-social'])){
			return;
		}
		
		$fb_page_id = $siteseo->social_settings['social_facebook_link_ownership_id'] ?? '';
		$fb_link_owership = $siteseo->social_settings['social_facebook_admin_id'] ?? '';
		$og_type = get_post_meta(get_the_ID(), '_og_type', true);
		$og_title = get_the_title();
		$og_description = get_bloginfo('description');
		$og_url =  get_home_url();
		$og_sitename = get_bloginfo('name');
		$og_img = $siteseo->social_settings['social_facebook_img'] ?? '';
		$org_height = '';
		$org_width = '';
		
		
		if(!$og_type){
			$og_type = 'website'; //default wb
		}
				
		if(!empty($siteseo->social_settings['social_twitter_card_og'])){

			if(!empty($og_url)){
				echo '<meta property="og:url" content="' . esc_html($og_url) . '" />';
			}

			if(!empty($og_sitename)){
				echo '<meta property="og:site_name" content="' . esc_html($og_sitename) . '" />';
			}

			if(function_exists('get_locale')){
				echo '<meta property="og:locale" content="' . esc_html(get_locale()) . '" />';
			}

			if(!empty($og_type)){
				echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />';
			}

			if(!empty($og_title)){
				echo '<meta property="og:title" content="' . esc_html($og_title) . '" />';
			}

			if(!empty($og_description)){
				echo '<meta property="og:description" content="' . esc_html($og_description) . '" />';
			}

			if(!empty($og_img)){
				echo '<meta property="og:image" content="' . esc_html($og_img) . '" />';
				echo '<meta property="og:secure_url" content="' . esc_html($og_img) . '" />';
			}

			if(!empty($og_img_height)){
				echo '<meta property="og:image:height" content="' . esc_attr($og_img_height) . '" />';
			}

			if(!empty($og_img_width)){
				echo '<meta property="og:image:width" content="' . esc_attr($og_img_width) . '" />';
			}

			if(!empty($fb_page_id)){
				echo '<meta property="fb:pages" content="' . esc_html($fb_page_id) . '" />';
			}

			if(!empty($fb_link_owership)){
				echo '<meta property="fb:admins" content="' . esc_html($fb_link_owership) . '" />';
			}	
		}
		
	}
	
	static function twitter_card(){
		global $siteseo;
		
		if(empty($siteseo->social_settings['social_twitter_accounts']) || empty($siteseo->setting_enabled['toggle-social'])){
			return;
		}
		
		$site_type = get_post_meta(get_the_ID(), '_og_type', true);
		$site_title = get_the_title();
		$site_description = get_bloginfo('description');
		$site_url =  get_home_url();
		$sitename = get_bloginfo('name');
		$twitter_img = $siteseo->social_settings['social_twitter_card_img'] ?? '' ;
		
		if(!$site_type){
			$site_type = 'website'; //default wb
		}
				
		if(!empty($siteseo->social_settings['social_twitter_card_og'])){
			
			echo'<meta property="twitter:locale" content="'.esc_html(get_locale()).'" />';
			
			if($site_type){
				echo'<meta property="twitter:type" content="'.esc_attr($site_type).'" />';
			}
			if(!empty($site_title)){
				echo'<meta property="twitter:title"  content="'.esc_html($site_title).'"/>.';
			}
			
			if(!empty($site_description)){
				echo'<meta property="twitter:description content="'.esc_html($site_description).'"/>';
			}
			
			if(!empty($site_url)){
				echo'<meta property="twitter:url" content="'.esc_html($site_url).'" />';
			}
			
			if(!empty($sitename)){
				echo'<meta property="twitter:sitename" content="'.esc_html($sitename).'" />';
			}
			
			if(!empty($twitter_img)){
				echo'<meta property="twitter:image" content="'.esc_html($twitter_img).'"/>';
			}
		}
		
	}


}