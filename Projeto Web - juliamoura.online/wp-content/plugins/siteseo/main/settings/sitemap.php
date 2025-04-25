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

class Sitemap{

    static function menu(){
		global $siteseo;

		Dashbord::admin_header();

		$sitemap_toggle = isset($siteseo->setting_enabled['toggle-xml-sitemap']) ? $siteseo->setting_enabled['toggle-xml-sitemap'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_sitemap_general'; // Default tab

		$titles_meta_subtabs = [
			'tab_sitemap_general' => esc_html__('Home', 'siteseo'),
			'tab_sitemap_post_types' => esc_html__('Post types', 'siteseo'),
			'tab_sitemap_taxonomy ' => esc_html__('Taxonomy', 'siteseo'),
			'tab_sitmap_html' => esc_html__('HTML Sitemap', 'siteseo')
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';

		wp_nonce_field('siteseo_sitemap_settings');

		Dashbord::render_toggle('Sitemaps - SiteSEO', 'sitemap_toggle', $sitemap_toggle, $nonce);

		echo'<div id="siteseo-tabs" class="wrap">
		<div class="nav-tab-wrapper">';

		foreach($titles_meta_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo'</div>
		<div class="tab-content-wrapper">
		<div class="siteseo-tab' . ($current_tab == 'tab_sitemap_general' ? ' active' : '') . '" id="tab_sitemap_general">';
		self::general_sitemaps();
		echo'</div>  
		<div class="siteseo-tab' . ($current_tab == 'tab_sitemap_post_types' ? ' active' : '') . '" id="tab_sitemap_post_types">';
		self::post_types_sitemaps();
		echo'</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_sitemap_taxonomy' ? ' active' : '') . '" id="tab_sitemap_taxonomy">';
		self::taxonomy_sitemap();
		echo'</div>  
		<div class="siteseo-tab' . ($current_tab == 'tab_sitmap_html' ? ' active' : '') . '" id="tab_sitmap_html">';
		self::html_sitemap();
		echo'</div>
		</div>';

		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo '</form>';
	}

    static function general_sitemaps(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		$options = get_option('siteseo_xml_sitemap_option_name');
		
		$xml_sitemap = isset($options['xml_sitemap_general_enable']) ? $options['xml_sitemap_general_enable'] : '';
		$img_sitemap = isset($options['xml_sitemap_img_enable']) ? $options['xml_sitemap_img_enable'] : '';
		$author_sitemap = isset($options['xml_sitemap_author_enable']) ? $options['xml_sitemap_author_enable'] : '';
		$html_sitemap = isset($options['xml_sitemap_html_enable']) ? $options['xml_sitemap_html_enable'] : '';
		
        echo'<h3>'.esc_html('General','siteseo').'</h3>
		<p>A sitemap is a file where you provide information about the <strong>pages, images, videos... and the relationships between them. </strong>
			Search engines like Google read this file to <strong>crawl your site more efficiently.</strong>
		</p>
        <p>The XML sitemap is an <strong>exploration aid.</strong> Not having a sitemap will absolutely <strong>NOT prevent engines from indexing your content.</strong> For this, opt for meta robots.</p>
        <p>'.esc_attr('This is the URL of your index sitemaps to submit to search engines:','siteseo').'</p>
        
        <div class="siteseo-styles pre"><pre><span class="dashicons dashicons-external"></span><a href="' . esc_url(get_option('home')) . '/sitemaps.xml" target="_blank">' . esc_url(get_option('home')) . '/sitemaps.xml</a></pre></div>
		
		<div class="wrap-tags"><button class="btnSecondary">Flush Premalinks</div>
		
        <div class="siteseo-notice">
		    <span id="siteseo-dash-icon" class="dashicons dashicons-info"></span>
            <p>To view your sitemap, <strong>enable permalinks</strong> (not default one), and save settings to flush them.</P>
        </div>
        
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">Enable XML Sitemap</th>
                    <td>
                     <label><input id="siteseo_enable_sitemap" name="siteseo_options[enable_xml_sitemap]" type="checkbox" ' . (!empty($xml_sitemap) ? 'checked="yes"' : '') . ' value="1"/>' . __('Enable XML Sitemap', 'siteseo') . '</label>
                    </td>
                </tr>    

                <tr>
                    <th scope="row">Enable Image Sitemap</th>
                    <td>
                        <label><input id="siteseo_image_sitemap" name="siteseo_options[enable_img_sitemap]" type="checkbox" ' . (!empty($img_sitemap) ? 'checked="yes"' : '') . ' value="1"/>' . __('Enable Image Sitemap (standard images, image galleries, featured image, WooCommerce product images)', 'siteseo-pro') . '</label>
                        <p class="description">'.esc_html('Images in XML sitemaps are visible only from the source code.','siteseo').'</p>
                    </td>
                </tr>  

                <tr>
                    <th scope="row">Enable Author Sitemap</th>
                    <td>
                        <label><input id="siteseo_author_sitemap" name="siteseo_options[enable_author_sitemap]" type="checkbox" ' . (!empty($author_sitemap) ? 'checked="yes"' : '') . ' value="1"/>' . __('Enable Author Sitemap', 'siteseo') . '</label>
                        <p class="description">'.esc_html('Make sure to enable author archive from SEO, titles and metas, archives tab.','siteseo').'</p>
                    </td>
                </tr> 

                  <tr>
                    <th scope="row">Enable HTML Sitemap</th>
                    <td>
                        <label><input id="siteseo_html_sitemap" name="siteseo_options[enable_html_sitemap]" type="checkbox" ' . (!empty($html_sitemap) ? 'checked="yes"' : '') . ' value="1"/>' . __('Enable HTML Sitemap', 'siteseo') . '</label>
                    </td>
                </tr> 
			</tbody>
        </table>
		<input type="hidden" name="siteseo_options[general_sitemaps] value="1"/>';

    }

    static function post_types_sitemaps(){

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

        echo'<h3>Post Types</h3>
            <p>Include/Exclude Post Types.</p>
                <table class="form-table">
                    <tbody>
                        <tr scope="row">
                            <th>'.esc_html('Check to INCLUDE Post Types','siteseo').'</th>
                                <td>
                                    <label for="sitemap_post_types_post">
                                        <input id="sitemap_post_types_post" name="" type="checkbox" value="1"/>' . esc_html__('Include', 'siteseo') . 
                                    '</label>
                                </td>
                        </tr>
                    
                        <tr scope="row">
                              <th></th>
                             <td>
                                <label for="sitemap_post_types_pages">
                                        <input id="sitemap_post_types_pages" name="" type="checkbox" value="1"/>' . esc_html__('Include', 'siteseo') . 
                                '</label>
                            </td>
                        </tr>

                        <tr scope="row">
                              <th></th>
                             <td>
                                <label for="sitemap_post_types_media">
                                        <input id="sitemap_post_types_media" name="" type="checkbox" value="1"/>' . esc_html__('Include', 'siteseo') . 
                                '</label>
                            </td>
                        </tr>
						
                    <tbody>
                </table><input type="hidden" name="siteseo_options[post_types_sitemaps]" value="1"/>';
    }

	
    static function taxonomy_sitemap(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }
		
		$get_taxonomies = get_taxonomies();
		$check_taxomies = apply_filters('siteseo_sitemaps_tax', $get_taxonomies);

        echo'<h3>Taxonomies</h3>
            <p class="description">Taxonomies<p>
            <table class="form-table">
                <tr scope="row">
                    <th>Check to INCLUDE Taxonomies</th>
                    <td>';
                        foreach($check_taxomies as $taxonomies_key =>$taxonomies_value){
							echo'<label>'. $taxonomies_key .'</label><br/>
							<input type="checkbox" name="siteseo_options[]" value="" />
							<br/>';
							
                        }
                    echo'</td>
                </tr>
            
            </table><input type="hidden" name="siteseo_options[taxonomies_sitemap]" value="1">';

    }

    static function html_sitemap(){

        if(!empty($_POST['submit'])){
		
            self::save_settings();
        }
		
		$options = get_option('siteseo_xml_sitemap_option_name');
		
		$include_pages = isset($options['xml_sitemap_html_mapping']) ? $options['xml_sitemap_html_mapping'] : '';
		$exclude_page = isset($options['xml_sitemap_html_exclude']) ? $options['xml_sitemap_html_exclude'] : '';
		$oder = isset($options['xml_sitemap_html_order']) ? $options['xml_sitemap_html_order'] : '';
		$order_by = isset($options['xml_sitemap_html_orderby']) ? $options['xml_sitemap_html_orderby'] : '';
		$disable_date = isset($options['xml_sitemap_html_date']) ? $options['xml_sitemap_html_date'] : '';
		$remove_archive = isset($options['xml_sitemap_html_archive_links']) ? $options['xml_sitemap_html_archive_links'] : '';
		

        echo'<h3>'.esc_html('HTML Sitemap','siteseo').'</h3>
        <p>'.esc_html('Create an HTML Sitemap for your visitors and boost your SEO.','siteseo').'</p>
        <p>'.esc_html('Limited to 1,000 posts per post type. You can change the order and sorting criteria below.','siteseo').'</p>

        <div class="siteseo-notice"><span class="dashicons dashicons-info"></span>
        <div>
            <h3>' . esc_html__('How to use the HTML Sitemap?', 'siteseo') . '</h3>
            <h4>' . esc_html__('Block Editor', 'siteseo') . '</h4>
            <p>' . wp_kses_post(__('Add the HTML sitemap block using the <strong>Block Editor</strong>.', 'siteseo')) . '</p>
            <h4>' . esc_html__('Shortcode', 'siteseo') . '</h4>
            <p>' . esc_html__('You can also use this shortcode in your content (post, page, post type...):', 'siteseo') . '</p>
            <div class="siteseo-styles pre"><pre>[siteseo_html_sitemap]</div></pre>
            <p>' . esc_html__('To include specific custom post types, use the CPT attribute:', 'siteseo') . '</p>
            <div class="siteseo-styles pre"><pre>[siteseo_html_sitemap cpt="post,product"]</div></pre>
            <h4>' . esc_html__('Other', 'siteseo') . '</h4>
            <p>' . esc_html__('Dynamically display the sitemap by entering an ID to the first field below.', 'siteseo') . '</p>
			</div>
		</div>
        
        <table class="form-table">
             <tr scope="row">
                <th>Post, Page, or Custom Post Type IDs to display:</th>
                <td>
                    <input type="text" value="'.esc_html($include_pages).'" name="siteseo_options[page_numbers]" placeholder="eg:2, 28, 68">
                </td>
            </tr>

            <tr scope="row">
                <th>Exclude Posts, Pages, Custom Post Types or Terms IDs:</th>
                <td>
                    <input type="text" value="'.esc_html($exclude_page).'" name="siteseo_options[exclude_page]" placeholder="eg: 13 ,8 ,28">
                </td>
            </tr>

            <tr scope="row">
                <th>Order:</th>
                <td>
                    <select name="siteseo_options[order]">
                        <option value="Default (date)" '.selected($oder, 'Default (date)', false).'>'.esc_html('DESC (descending order from highest to lowest values (3, 2, 1; c, b, a))','siteseo').'</option>
                         <option value="Default (date)" '.selected($oder, 'Default (date)', false).'>'.esc_html('ASC (ascending order from lowest to highest values (1, 2, 3; a, b, c))','siteseo').'</option>
                    </select>
                </td>
            </tr>

            <tr scope="row">
                <th>Order By:</th>
                <td>
                    <select name="siteseo_options[order_by]">
                        <option value="Default (date)" '.selected($order_by, 'Default (date)', false).'>'.esc_html('Deafult (date)','siteseo').'</option>
                        <option value="Post Title" '.selected($order_by, 'Post Title', false).'>'.esc_html('Post Title','siteseo').'</option>
                        <option value="Modified date" '.selected($order_by, 'Modified date', false).'>'.esc_html('Modified date','siteseo').'</option>
                        <option value="Post ID" '.selected($order_by, 'Post ID', false).'>'.esc_html('POST ID','siteseo').'</option>
                        <option value="Menu Order" '.selected($order_by, 'Menu Order', false).'>'.esc_html('Menu Order','siteseo').'</option>
                    </select>
                </td>
            </tr>
            <tr scope="row">
                <th>Disable Date:</th>
                <td>
                    <label for="sitemap_html_date">
                        <input id="sitemap_html_date" name="siteseo_options[disable_date]" type="checkbox" ' . (!empty($disable_date) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Disable date after each post, page, post type?', 'siteseo') . 
                    '</label>
                </td>
            </tr>

            <tr scope="row">
                <th>Remove Archive Links:</th>
                <td>
                    <label for="sitemap_remove_link">
                        <input id="sitemap_remove_link" name="siteseo_options[remove_links]" type="checkbox" ' . (!empty($remove_archive) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove links from archive pages (eg: Products)', 'siteseo') . 
                    '</label>
                </td>
            </tr>
        </table><input type="hidden" name="siteseo_options[html_sitemap]" value="1" />';
    }

    static function save_settings(){
		global $siteseo;
		
		check_admin_referer('siteseo_sitemap_settings');
		
		if(!current_user_can('manage_options') || !is_admin()){
			return;
		}
		
		$options = [];
		
		if(empty($_POST['siteseo_options'])){
			return;
		}
				
		if(isset($_POST['siteseo_options']['general_sitemaps'])){
			
			$options['xml_sitemap_general_enable'] = isset($_POST['siteseo_options']['enable_xml_sitemap']);
			$options['xml_sitemap_img_enable'] = isset($_POST['siteseo_options']['enable_img_sitemap']);
			$options['xml_sitemap_author_enable'] = isset($_POST['siteseo_options']['enable_author_sitemap']);
			$options['xml_sitemap_html_enable'] = isset($_POST['siteseo_options']['enable_html_sitemap']);
			
		}
		
		if(isset($_POST['siteseo_options']['html_sitemap'])){
			
			$options['xml_sitemap_html_mapping'] = isset($_POST['siteseo_options']['page_numbers']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['page_numbers'])) : '';
			$options['xml_sitemap_html_exclude'] = isset($_POST['siteseo_options']['exclude_page']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['exclude_page'])) : '';
			$options['xml_sitemap_html_order'] = isset($_POST['siteseo_options']['order'])? sanitize_text_field(wp_unslash($_POST['siteseo_options']['order'])) : '';
			$options['xml_sitemap_html_orderby'] = isset($_POST['siteseo_options']['order_by']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['order_by'])) : '';
			$options['xml_sitemap_html_date'] = isset($_POST['siteseo_options']['disable_date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['disable_date'])) : '';
			$options['xml_sitemap_html_archive_links'] = isset($_POST['siteseo_options']['remove_links']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['remove_links'])) : '';
		}
		
		// posts 
		if(isset($_POST['siteseo_options']['xml_sitemap_post_types_list'])){

			foreach($_POST['siteseo_options']['xml_sitemap_post_types_list'] as $posttypes_key => $posttypes_value){
				if(isset($posttypes_value['include'])){
					$options['xml_sitemap_post_types_list'][$posttypes_key]['include'] = sanitize_text_field(wp_unslash($posttypes_value['include'])); 
				}
			}
		}

		// Taxonomies
		if(isset($_POST['siteseo_options']['xml_sitemap_taxonomies_list'])){
			foreach($_POST['siteseo_options']['xml_sitemap_taxonomies_list'] as $taxonomy_key => $taxonomy_value){
					if(isset($taxonomy_value['include'])){
						$options['xml_sitemap_taxonomies_list'][$taxonomy_key]['include'] = sanitize_text_field(wp_unslash($taxonomy_value['include']));
				}
			}
		}
		
		update_option('siteseo_xml_sitemap_option_name',$options);

    }
}