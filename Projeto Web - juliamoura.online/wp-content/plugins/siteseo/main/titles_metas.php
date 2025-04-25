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

class Titles_metas{
	
	static function advanced_metas(){
		global $siteseo,$robots;

		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}

		//add robots tags
		$options_cat = $siteseo->titles_settings['titles_tax_titles']['category'];
		$option_tags = $siteseo->titles_settings['titles_tax_titles']['post_tag'];
		$option_post = $siteseo->titles_settings['titles_single_titles']['post'];
		$option_page = $siteseo->titles_settings['titles_single_titles']['page'];

		$robots['noindex'] = !empty($siteseo->titles_settings['titles_noindex']);

		$robots['nofollow'] = !empty($siteseo->titles_settings['titles_nofollow']);

		$robots['noimageindex'] = !empty($siteseo->titles_settings['titles_noimageindex']);

		$robots['noarchive'] = !empty($siteseo->titles_settings['titles_noarchive']);

		$robots['nosnippet'] = !empty($siteseo->titles_settings['titles_nosnippet']);

		if(!empty($options_cat['noindex']) && is_category() && !empty($options_cat['enable'])){
			$robots['noindex'] = false;
		}

		if(!empty($options_cat['nofollow']) && is_category() && !empty($options_cat['enable'])){
			$robots['nofollow'] = false;
		}

		if(!empty($option_tags['noindex']) && is_tag() && !empty($option_tags['enable'])){
			$robots['noindex'] = false;
		}

		if(!empty($option_tags['nofollow']) && is_tag() && !empty($option_tags['enable'])){
			$robots['nofollow'] = false;
		}

		if(!empty($siteseo->titles_settings['titles_archives_author_noindex'] && is_author())){
			$robots['noindex'] = false;
		}

		if(!empty($option_tags['titles_archives_date_noindex']) && is_date()){
			$robots['noindex'] = false;
		}

		if(!empty($option_tags['titles_archives_search_title_noindex']) && is_search()){
			$robots['noindex'] = false;
		}

		if(!empty($option_post['noindex']) && is_single()){
			$robots['noindex'] = false;
		}

		if(!empty($option_post['nofollow']) && is_single()){
			$robots['nofollow'] = false;
		}

		if(!empty($option_page['noindex']) && is_page()){
			$robots['noindex'] = false;
		}

		if(!empty($option_page['nofollow']) && is_page()){
			$robots['nofollow'] = false;
		}

		return $robots;
	}
	
	static function add_nositelinkssearchbox(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}
	
		if(!empty($siteseo->titles_settings['titles_nositelinkssearchbox'])){
			echo'<meta name="google" content="nositelinkssearchbox" >';
		}
		
	}
	
	static function replace_variables($content){
		global $post, $siteseo, $wp_query;
		
		//site info
		$site_title = get_bloginfo('name');
		$site_tagline = get_bloginfo('description');
		$site_sep = $siteseo->titles_settings['titles_sep'];
		
		
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$page = get_query_var('page') ? get_query_var('page') : 1;
		
		//date info
		$current_time = current_time('timestamp');
		$archive_date = get_the_date('d');
		$archive_month = get_the_date('M');
		$archive_month_name = get_the_date('F');
		$archive_year = get_the_date('Y');
		
		//Author
		$author_id = isset($post->post_author) ? $post->post_author : get_current_user_id();
		$author_first_name = get_the_author_meta('first_name', $author_id);
		$author_last_name = get_the_author_meta('last_name', $author_id);
		$author_website = get_the_author_meta('url', $author_id);
		$author_nickname = get_the_author_meta('nickname', $author_id);
		$author_bio = get_the_author_meta('description', $author_id);
		
		// WooCommerce
		$wc_variables = array();
		if(function_exists('wc_get_product') && is_singular('product')){
			$product = wc_get_product($post->ID);
			if ($product) {
				$wc_variables = array(
					'%%wc_single_cat%%' => strip_tags(wc_get_product_category_list($post->ID)),
					'%%wc_single_tag%%' => strip_tags(wc_get_product_tag_list($post->ID)),
					'%%wc_single_short_desc%%' => $product->get_short_description(),
					'%%wc_single_price%%' => $product->get_price(),
					'%%wc_single_price_exe_tax%%' => $product->get_price_excluding_tax(),
					'%%wc_sku%%' => $product->get_sku()
				);
			}
		}
		
		$replacements = array(
			'%%sep%%' => $site_sep,
			'%%sitetitle%%' => $site_title,
			'%%post_title%%' => is_singular() ? get_the_title() : '',
			'%%post_excerpt%%' => is_singular() ? get_the_excerpt() : '',
			'%%post_content%%' => is_singular() ? wp_strip_all_tags(get_the_content()) : '',
			'%%post_thumbnail_url%%' => get_the_post_thumbnail_url($post),
			'%%post_url%%' => get_permalink(),
			'%%post_date%%' => get_the_date(),
			'%%post_modified_date%%' => get_the_modified_date(),
			'%%post_author%%' => get_the_author(),
			'%%post_category%%' => strip_tags(get_the_category_list(', ')),
			'%%post_tag%%' => strip_tags(get_the_tag_list('', ', ', '')),
			'%%_category_title%%' => single_cat_title('', false),
			'%%_category_description%%' => category_description(),
			'%%tag_title%%' => single_tag_title('', false),
			'%%tag_description%%' => tag_description(),
			'%%term_title%%' => single_term_title('', false),
			'%%term_description%%' => term_description(),
			'%%search_keywords%%' => get_search_query(),
			'%%current_pagination%%' => $paged,
			'%%page%%' => $page,
			'%%cpt_plural%%' => post_type_archive_title('', false),
			'%%archive_title%%' => get_the_archive_title(),
			'%%archive_date%%' => $archive_date,
			'%%archive_date_day%%' => $archive_date,
			'%%archive_date_month%%' => $archive_month,
			'%%archive_date_month_name%%' => $archive_month_name,
			'%%archive_date_year%%' => $archive_year,
			'%%currentday%%' => date_i18n('j', $current_time),
			'%%currentmonth%%' => date_i18n('F', $current_time),
			'%%currentmonth_short%%' => date_i18n('M', $current_time),
			'%%currentmonth_num%%' => date_i18n('n', $current_time),
			'%%currentyear%%' => date_i18n('Y', $current_time),
			'%%currentdate%%' => date_i18n(get_option('date_format'), $current_time),
			'%%currenttime%%' => date_i18n(get_option('time_format'), $current_time),
			'%%author_first_name%%' => $author_first_name,
			'%%author_last_name%%' => $author_last_name,
			'%%author_website%%' => $author_website,
			'%%author_nickname%%' => $author_nickname,
			'%%author_bio%%' => $author_bio,
		);
		
		//WooCommerces
		if(!empty($wc_variables)){
			$replacements = array_merge($replacements, $wc_variables);
		}
		

		if(preg_match_all('/%%_cf_(.*?)%%/', $content, $matches)){
			foreach ($matches[1] as $custom_field) {
				$meta_value = get_post_meta($post->ID, $custom_field, true);
				$replacements["%%_cf_${custom_field}%%"] = $meta_value;
			}
		}
		

		if(preg_match_all('/%%_ct_(.*?)%%/', $content, $matches)){
			foreach($matches[1] as $taxonomy){
				$terms = get_the_terms($post->ID, $taxonomy);
				$term_names = is_array($terms) ? wp_list_pluck($terms, 'name') : array();
				$replacements["%%_ct_${taxonomy}%%"] = implode(', ', $term_names);
			}
		}
		
		if(preg_match_all('/%%_ucf_(.*?)%%/', $content, $matches)){
			foreach($matches[1] as $user_meta){
				$meta_value = get_user_meta($author_id, $user_meta, true);
				$replacements["%%_ucf_${user_meta}%%"] = $meta_value;
			}
		}
	 
		$target_keywords = isset($siteseo->keywords_settings['tempory_set']) ? $siteseo->keywords_settings['tempory_set'] : '';
		$replacements['%%target_keyword%%'] = $target_keywords;
		
	 
		$replacements = array_map(function($value){
			if(is_array($value) || is_object($value)){
				return '';
			}
			return strip_tags($value);
		}, $replacements);
		
		return str_replace(
			array_keys($replacements),
			array_values($replacements),
			$content
		);
	}
	
	static function modify_site_title($title, $sep = ''){
        global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}
		
		$post_types = $siteseo->titles_settings['titles_single_titles']['post'];
		$page_types = $siteseo->titles_settings['titles_single_titles']['page'];
		$category = $siteseo->titles_settings['titles_tax_titles']['category'];
		$tags = $siteseo->titles_settings['titles_tax_titles']['post_tag'];

		
		// default
        if(is_front_page() && !empty($siteseo->titles_settings['titles_home_site_title'])){
            $new_title = self::replace_variables(esc_attr($siteseo->titles_settings['titles_home_site_title']));
            
			if(!empty($sep)){
                $new_title .= " $sep " . get_bloginfo('name');
            }
            
            return $new_title;
        }
		
		// page types
		if(is_page() && !empty($page_types['title'])){
			$new_title = self::replace_variables(esc_attr($page_types['title']));
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
		}
		
		//post types
		if(is_single() && !empty($post_types['title'])){
			$new_title = self::replace_variables(esc_attr($post_types['title']));
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
			
		}
		
		// category taxonomies
		if(is_category() && !empty($category['title']) && !empty($category['enable'])){
			$new_title = self::replace_variables(esc_attr($category['title']));
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
		}
	
		// tag taxonomies
		if(is_tag() && !empty($tags['title']) && !empty($tags['enable'])){

			$new_title = self::replace_variables(esc_attr($tags['title']));
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
			
		}
		//author archive
		if(is_author() && !empty($siteseo->titles_settings['titles_archives_author_title'])){
			
			$new_title = self::replace_variables(esc_attr($siteseo->titles_settings['titles_archives_author_title']));
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
			
		}
		// date archive
		if(is_date() && !empty($siteseo->titles_settings['titles_archives_date_title'])){

			$new_title = self::replace_variables(esc_attr($siteseo->titles_settings['titles_archives_date_title']));

			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}

			return $new_title;
			
		}
		
		// search archive
		if(is_search() && !empty($siteseo->titles_settings['titles_archives_search_title'])){
			
			$new_title = esc_attr($siteseo->titles_settings['titles_archives_search_title']);
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;
			
		}
		
		// 404 archive
		if(is_404() && !empty($siteseo->titles_settings['titles_archives_404_title'])){
			
			$new_title = esc_attr($siteseo->titles_settings['titles_archives_404_title']);
			
			if(!empty($sep)){
				$new_title .= " $sep " . get_bloginfo('name');
			}
			
			return $new_title;

		}
		
        return $title;
    }
	
	static function add_meta_description(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}
		
		$page_types = $siteseo->titles_settings['titles_single_titles']['page'];
		$post_types = $siteseo->titles_settings['titles_single_titles']['post'];
		$category = $siteseo->titles_settings['titles_tax_titles']['category'];
		$tags = $siteseo->titles_settings['titles_tax_titles']['post_tag'];
	
		// default
		if(is_front_page() && !empty($siteseo->titles_settings['titles_home_site_desc'])){
			echo '<meta name="description" content="' .  self::replace_variables(esc_attr($siteseo->titles_settings['titles_home_site_desc'])) . '">';
		}
		
		// page types
		if(is_page() && !empty($page_types['description'])){
			echo'<meta name="description" content="'. self::replace_variables(esc_attr($page_types['description'])).'" >';
			
		}
		// post types
		if(is_single() && !empty($post_types['description'])){
			echo'<meta name="description" content="'. self::replace_variables(esc_attr($post_types['description'])).'" >';	
		}
		
		// category archive
		if(is_category() && !empty($category['description']) && !empty($category['enable'])){
		
			echo'<meta name="description" content="'. self::replace_variables(esc_attr($category['description'])).'" >'; 
		}
		
		// tag archives
		if(is_tag() && !empty($tags['description']) && !empty($tags['enable'])){
			echo'<meta name="description" content="'. self::replace_variables(esc_attr($tags['description'])).'" >';
		}
		
		//author archive
		if(is_author() && !empty($siteseo->titles_settings['titles_archives_author_desc'])){
			echo'<meta name="description" content="'.esc_attr($siteseo->titles_settings['titles_archives_author_desc']).'" >';
		}
		
		// date archive
		if(is_date() && !empty($siteseo->titles_settings['titles_archives_date_desc'])){
			echo'<meta name="description" content="'.esc_attr($siteseo->titles_settings['titles_archives_date_desc']).'" >';
		}
		
		// search archive
		if(is_search() && !empty($siteseo->titles_settings['titles_archives_search_desc'])){
			echo'<meta name="description" content="'.esc_attr($siteseo->titles_settings['titles_archives_search_desc']).'" >';
		}
		
		//404 archives
		if(is_404() && !empty($siteseo->titles_settings['titles_archives_404_desc'])){
			echo'<meta name="description" content="'.esc_attr($siteseo->titles_settings['titles_archives_404_desc']).'" >';
		}
	}
	
	static function add_rel_link_pages(){
		global $siteseo,$paged;
		
		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}
		
		if(!empty($siteseo->titles_settings['titles_paged_rel'])){
			
			if(get_previous_posts_link()){
				
				echo'<link rel="prev"  href="'.esc_url(get_pagenum_link($paged - 1)).'" />';
			}
			if(get_next_posts_link()){
				
				echo'<link rel="next" href="'.esc_url(get_pagenum_link($paged + 1)).'" />';
			}
		}
		
	}

	static function date_time_publish(){
		global $siteseo;
		
		if(empty($siteseo->setting_enabled['toggle-titles'])){
			return;
		}
		
		$post_types = $siteseo->titles_settings['titles_single_titles']['post'];
        $page_types = $siteseo->titles_settings['titles_single_titles']['page'];
		
		if(!empty($post_types['date']) && is_single()){
			$published_time = get_the_date('c');
			$modified_time = get_the_modified_date('c');  
			echo'<meta article:published_time content="'.$published_time.'">';
			echo'<meta article:modified_time content="'.$modified_time.'">';
		}
		
		if(!empty($page_types['date']) && is_page()){
			$published_time = get_the_date('c');
			$modified_time = get_the_modified_date('c');  
			echo'<meta article:published_time content="'.$published_time.'">';
			echo'<meta article:modified_time content="'.$modified_time.'">';
			echo'<meta article:modified_time content="'.$modified_time.'">';
			
		}
		
		if(!empty($post_types['thumb_gcs']) && is_single()){
			if(get_the_post_thumbnail_url(get_the_ID())){
				echo'<meta name="thumbnail" content="'. get_the_post_thumbnail_url(get_the_ID()).'">';
			}

			if(get_the_post_thumbnail_url(get_the_ID())){
				echo'<meta name="thumbnail" content="' . get_the_post_thumbnail_url(get_the_ID()).'">';
			}
		}

	}
	
	static function disable_date_archive(){
		global $siteseo;
		
		if(empty($siteseo->titles_settings['titles_archives_date_disable'])){
			return;
		}
		
		if(empty($siteseo->titles_settings['titles_archives_author_disable'])){
			return;
		}
	}
	
	static function metaboxes_enable(){
		global $siteseo;
		
		$page_settings = $siteseo->titles_settings['titles_single_titles']['page']['enable'] ?? '';
		$post_settings = $siteseo->titles_settings['titles_single_titles']['post']['enable'] ?? '';
		
		if(!empty($post_settings)){
			// enabled
			add_action('add_meta_boxes', '\SiteSEO\Titles_metas::post_metabox');
		}
		
		if(!empty($page_settings)){
			// enabled
			add_action('add_meta_boxes', '\siteseo\Titles_metas::page_metabox');
		}
	}
	
	static function post_metabox(){
		
		add_meta_box('siteseo-post-metabox','SiteSEO','\SiteSEO\metaboxes\meta_tags::render_metabox','post','side','high');
		
	}
	
	static function page_metabox(){
	
		add_meta_box('siteseo-page-metabox','SiteSEO','\SiteSEO\metaboxes\meta_tags::render_metabox','page','side','high');
	}
}
