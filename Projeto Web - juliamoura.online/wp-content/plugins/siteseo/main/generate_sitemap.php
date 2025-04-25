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

class Generate_sitemap{

	static function general_settings(){
		global $siteseo;
	
		if(isset($siteseo->sitemap_settings['xml_sitemap_general_enable'])){
			
		}

		if(isset($siteseo->sitemap_settings['xml_sitemap_general_enable'])){
			add_rewrite_rule('^sitemaps.xml$', 'index.php?siteseo_sitemap=1', 'top');
			add_action('template_redirect','SiteSEO\Generate_sitemap::render_sitemap');
			add_filter('query_vars', 'SiteSEO\Generate_sitemap::enable_args');
		}
		add_rewrite_rule('author.xml?$', 'index.php?siteseo_author=1', 'top');
	}
	
	static function enable_args($vars){
		 $vars[] = 'sitemap';
		return $vars;
	}
	
	static function render_sitemap(){
		/*
		if(get_query_var('sitemap')){
			// Set the correct header for XML
			header('Content-Type: application/xml; charset=utf-8');

			// Start outputting the XML structure
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

			// Query the posts you want to include in the sitemap
			$args = array(
				'post_type' => 'post', // Change to any custom post type as needed
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);
			$query = new WP_Query($args);

			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$post_url = get_permalink();

					echo '<url>';
					echo '<loc>' . esc_url($post_url) . '</loc>';
					echo '<lastmod>' . get_the_modified_date('c') . '</lastmod>';
					echo '<changefreq>weekly</changefreq>';
					echo '<priority>0.8</priority>';
					echo '</url>';
				}
			}
			
			echo '</urlset>';
			exit;
		}
		*/
	}

	static function flush_premalinks(){
		global $siteseo;
		
	} 
}