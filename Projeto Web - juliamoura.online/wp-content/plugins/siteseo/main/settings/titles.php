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


class Titles{
	
	static function menu(){
		global $siteseo;

		Dashbord::admin_header();

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_siteseo_home'; // Default tab
		
		$titles_meta_subtabs = [
			'tab_siteseo_home' => esc_html__('Home', 'siteseo'),
			'tab_siteseo_post_types' => esc_html__('Post types', 'siteseo'),
			'tab_siteseo_archives' => esc_html__('Archives', 'siteseo'),
			'tab_siteseo_taxonomies' => esc_html__('Taxonomies', 'siteseo'),
			'tab_siteseo_advanced' => esc_html('Advanced','siteseo')
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';

		wp_nonce_field('sitseo_title_settings');

		$titles_meta_toggle = isset($siteseo->setting_enabled['toggle-titles']) ? $siteseo->setting_enabled['toggle-titles'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');
		
		Dashbord::render_toggle('Titles & Metas - SiteSEO', 'titles_meta_toggle', $titles_meta_toggle, $nonce);

		echo'<div id="siteseo-tabs" class="wrap">
		<div class="nav-tab-wrapper">';

		foreach($titles_meta_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo'</div>     
		<div class="tab-content-wrapper">
		<div class="siteseo-tab '. ($current_tab == 'tab_siteseo_home' ? ' active' : '') .'" id="tab_siteseo_home">';
		self::home();
		echo'</div>
		<div class="siteseo-tab '.($current_tab == 'tab_siteseo_post_types' ? ' active' : '')  .'" id="tab_siteseo_post_types">';
		self::post_types();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_siteseo_archives' ? ' active' : '') .'" id="tab_siteseo_archives">';
		self::archives();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_siteseo_taxonomies' ? ' active' : '') .'" id="tab_siteseo_taxonomies">';
		self::taxonomies();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_siteseo_advanced' ? ' active' : '') .'" id="tab_siteseo_advanced">';
		self::advanced(); 
		echo'</div>
		</div>';
		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo'</form>';

	}

    static function home(){
		global $siteseo;

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		$options = get_option('siteseo_titles_option_name');
		//$options = $siteseo->titles_settings;

		$option_separator = isset($options['titles_sep']) ? $options['titles_sep'] : '';
		$option_site_title = isset($options['titles_home_site_title']) ? $options['titles_home_site_title'] : '';
		$option_site_title_alt = isset($options['titles_home_site_title_alt']) ? $options['titles_home_site_title_alt'] : '';
		$option_site_desc = isset($options['titles_home_site_desc']) ? $options['titles_home_site_desc'] : '';

		echo'<h3 class="siteseo-tabs">HOME</h3>
		<div class="siteseo-notice">
			<span id="siteseo-dash-icon" class="dashicons dashicons-info"></span>&nbsp;
			<p>'.esc_html__('Title and meta description are used by search engines to generate the snippet of your site in search results page.', 'siteseo').'</p>
		</div>

		<p>'. esc_html('Customize your title & meta description for homepage','siteseo').'</p>

		<span class="dashicons dashicons-external"></span>
		<a href="'.esc_attr('https://siteseo.io/docs/meta/google-uses-the-wrong-meta-title-meta-description-in-search-results/?utm_source=plugin&utm_medium=wp-admin-help-tab&utm_campaign=siteseo').'" target="_blank">'.esc_html__('Wrong meta title / description in SERP?', 'siteseo').'</a>

		<table class="form-table">
			<tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Separator</th>
                    <td>
                        <input type="text" name="siteseo_options[separator]" placeholder="Enter your separator, eg:-" value="'.esc_attr($option_separator).'">
                        <p class="description">'.esc_attr('Use this separator with %%sep%% in your title and meta description','siteseo').'</p>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">Site title</th>
                    <td>
                        <input type="text" name="siteseo_options[site_title]" value="'.esc_attr($option_site_title).'" placeholder="My awesome website">
                        <div class="wrap-tags">
                            <button class="tag-title-btn" id="btn-site-title"><span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE</button>
                            <button class="tag-title-btn" id="btn-separator"><span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR</button>
                            <button class="tag-title-btn" id="btn-tagline"><span id="icon" class="dashicons dashicons-insert"></span>TAGLINE</button>';
							siteseo_suggestion_button();
                        echo'</div>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">Alternative site title</th>
                    <td>
                        <input type="text" value="'.esc_attr($option_site_title_alt).'"  name="siteseo_options[alt_site_title]" placeholder="Alternative site title">
                        <p class="description">'.esc_html('The alternate name of the website (for example, if thers a commonly recognized acronym or shorter name for your site), if applicable. Make sure the name meets the', 'siteseo').'</p>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">Meta description</th>
                    <td>
                        <textarea type="text" name="siteseo_options[media_desc]" placeholder="This is cool website about Wookiess">'.esc_html($option_site_desc).'</textarea>
                        <div class="wrap-tags">
                            <button class="tag-title-btn" id="btn-tagline-meta"><span id="icon" class="dashicons dashicons-insert"></span>TAGLINE</button>';
                            siteseo_suggestion_button();  
                       echo'</div>
                    </td>
                </tr>
            </tbody>
        </table>
		<input type="hidden" name="siteseo_options[home_tab]" value="1"/>';

    }

    static function advanced(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

        //$options = $siteseo->titles_settings;
        $options = get_option('siteseo_titles_option_name');

        $option_noindex = isset($options['titles_noindex']) ? $options['titles_noindex'] : '';
        $option_nofollow = isset($options['titles_nofollow']) ? $options['titles_nofollow'] : '';
        $option_noimage = isset($options['titles_noimageindex']) ? $options['titles_noimageindex'] : '';
        $option_noarchive = isset($options['titles_noarchive']) ? $options['titles_noarchive'] : '';
        $option_nosnippet = isset($options['titles_nosnippet']) ? $options['titles_nosnippet'] : '';
        $option_nositelinkssearchbox = isset($options['titles_nositelinkssearchbox']) ? $options['titles_nositelinkssearchbox'] : '';
        $option_page_rel = isset($options['titles_paged_rel']) ? $options['titles_paged_rel'] : '';
        $option_paged_noindex = isset($options['titles_paged_noindex']) ? $options['titles_paged_noindex'] : '';
        $option_attachments_noindex = isset($options['titles_attachments_noindex']) ? $options['titles_attachments_noindex'] : '';


        echo'<h3 class="siteseo-tabs">Advanced</h3>
        <p>Customize your metas for all pages.</p>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">noindex</th>
                    <td>
                        <label>
				        <input id="siteseo_noindex" name="siteseo_options[noindex]" type="checkbox"' . (!empty($option_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('noindex', 'siteseo') . 
			            '</label>
                        <p class="description">'. esc_attr('Do not display all pages of the site in Google search results and do not display "Cached" links in search results.','siteseo').'</p>
                        <p class="description">Check also the<strong>"Search engine visibility"</strong> setting from the <a href="%s">WordPress Reading page</a></p>
                    </td>
                </tr>

				<td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">nofollow</th>
                    <td>
                        <label>
                            <input id="siteseo_nofollow" name="siteseo_options[no_follow]" type="checkbox"' . (!empty($option_nofollow) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('nofollow', 'siteseo') . 
                        '</label>
                        <p class="description">'. esc_html('Do not follow links for all pages.','siteseo').'</p>
                    </td>
                <tr>

				<td colspan="2"><span class="dashed-line"></span></td>
                
                <tr>
                    <th scope="row" style="user-select:auto;">noimageindex</th>
                    <td>
                        <label>
                            <input id="siteseo_nofollow" name="siteseo_options[no_image]" type="checkbox"' . (!empty($option_noimage) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('noimageindex', 'siteseo') . 
                        '</label>
                        <p class="description">'. esc_html('Do not follow links for all pages.','siteseo').'</p>
                    </td>
                <tr>

				<td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">noarchive</th>
                    <td>
                        <label>
                            <input id="siteseo_noarchive" name="siteseo_options[no_archive]" type="checkbox"' . (!empty($option_noarchive) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('noarchive', 'siteseo') . 
                        '</label>
                        <p class="description">'.esc_html('Do not display a "Cached" link in the Google search results.','siteseo').'</p>
                    </td>
                </tr>

				<td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">nosnippet</th>
                    <td>
                        <label>
                            <input id="siteseo_nosnippet" name="siteseo_options[no_snippet]" type="checkbox"' . (!empty($option_nosnippet) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('nosnippet', 'siteseo') . 
                        '</label>
                        <p class="description">'. esc_html('Do not display a description in the Google search results for all pages.','siteseo').'</p>
                    </td>
                </tr>

				<td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">nositelinkssearchbox</th>
                    <td>
                        <label>
                            <input id="siteseo_nositelinkssearchbox" name="siteseo_options[no_site_links_searchbox]" type="checkbox"' . (!empty($option_nositelinkssearchbox) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('nositelinkssearchbox', 'siteseo') . 
                        '</label>
                        <p class="description">'. esc_html('Prevents Google to display a sitelinks searchbox in search results. Enable this option will remove the "Website" schema from your source code.','siteseo') .'</p>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                 <tr>
                    <th scope="row" style="user-select:auto;">Indicate paginated content to Google</th>
                    <td>
                        <label>
                            <input id="siteseo_nositelinkssearchbox" name="siteseo_options[page_rel]" type="checkbox"' . (!empty($option_page_rel) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Add rel next/prev link in head of paginated archive pages', 'siteseo') . 
                        '</label>
                        <p class="description">'.esc_html('eg: https://example.com/category/my-category/page/2/.','siteseo').'</p>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                 <tr>
                    <th scope="row" style="user-select:auto;">noindex on paged archives</th>
                    <td>
                        <label>
                            <input id="siteseo_nositelinkssearchbox" name="siteseo_options[titles_paged_noindex]" type="checkbox" '. (!empty($option_paged_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Add a "noindex" meta robots for all paginated archive pages', 'siteseo') . 
                        '</label>
                        <p class="description">'.esc_html('eg: https://example.com/category/my-category/page/2/.','siteseo') .'</p>
                    </td>
                </tr>

                <td colspan="2"><span class="dashed-line"></span></td>

                <tr>
                    <th scope="row" style="user-select:auto;">noindex on attachment pages</th>
                    <td>
                        <label>
                            <input id="siteseo_nositelinkssearchbox" name="siteseo_options[attachments_noindex]" type="checkbox"' . (!empty($option_attachments_noindex) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__(' Add a "noindex" meta robots for all attachment pages', 'siteseo') . 
                        '</label>
                        <p class="description">'. esc_html('eg: https://example.com/my-media-attachment-page.','siteseo').'</p>
                    </td>
                </tr>
            </tbody>
        </table><input type="hidden" name="siteseo_options[advanced_tab]" value="1"/>';
    }

    static function archives(){
        global $siteseo;
    
        if(!empty($_POST['submit'])){
            self::save_settings();
        }
    
        // $options = $siteseo->titles_settings;
        $options = get_option('siteseo_titles_option_name');
    
        // Load settings
        $option_author_title   = $options['titles_archives_author_title'] ?? '';
        $option_author_desc    = $options['titles_archives_author_desc'] ?? '';
        $option_author_noindex = $options['titles_archives_author_noindex'] ?? '';
        $option_author_disabled = $options['titles_archives_author_disable'] ?? '';
        $option_date_title     = $options['titles_archives_date_title'] ?? '';
        $option_date_desc      = $options['titles_archives_date_desc'] ?? '';
        $option_date_noindex   = $options['titles_archives_date_noindex'] ?? '';
        $option_date_disabled  = $options['titles_archives_date_disable'] ?? '';
        $option_search_title   = $options['titles_archives_search_title'] ?? '';
        $option_search_desc    = $options['titles_archives_search_desc'] ?? '';
        $option_search_noindex = $options['titles_archives_search_title_noindex'] ?? '';
        $option_404_title      = $options['titles_archives_404_title'] ?? '';
        $option_404_desc       = $options['titles_archives_404_desc'] ?? '';
    
        $archives_fields = [
            'author-archives' => 'Author archives',
            'date-archives'   => 'Date archives',
            'search-archives' => 'Search archives',
            '404-archives'    => '404 archives'
        ];
    
        echo'<table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <div class="siteseo-container">';
                            $is_first = true;
                            foreach($archives_fields as $post_key => $post_val){
                                $active_class = $is_first ? 'active' : '';
                                echo'<a href="-' . $post_key . '" class="' . $active_class . '">' . $post_val . '</a>';
                                $is_first = false;
                            }
                    echo'</div>
                    </th>
                    <td>
                        <div id="author-archives">
                            <h3>Archives</h3>
                            <div class="siteseo_wrap_label">
                                <p class="description">Customize your metas for all archives.</p>
                            </div>
                            <span class="line"></span>
                            <h3>Author archives</h3>
                            <div class="siteseo_wrap_label"><p>Title template</p></div>
                            <input type="text" name="siteseo_options[author_title]" value="' . esc_attr($option_author_title) . '">
    
                            <div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-author-acrhive-title">
                                    <span id="icon" class="dashicons dashicons-insert"></span>POST AUTHOR
                                </button>
                                <button class="tag-title-btn" id="btn-author-acrhive-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-author-acrhive-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
								siteseo_suggestion_button();
                            echo'</div>
    
                            <div class="siteseo_wrap_label"><p>Meta description template</p></div>
                            <textarea name="siteseo_options[author_desc]">' . esc_html($option_author_desc) . '</textarea><br>
                            <div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[author_noindex]" type="checkbox" ' . (!empty($option_author_noindex) ? 'checked="yes"' : '') . ' value="1"/>
                                    ' . __('Do not display author archives in search engine results <strong>(noindex)</strong>', 'siteseo') . '
                                </label>
    
                                <label>
                                    <input name="siteseo_options[author_disable]" type="checkbox" ' . (!empty($option_author_disabled) ? 'checked="yes"' : '') . ' value="1"/>
                                    ' . __('Disable author archives', 'siteseo') . '
                                </label>
                            </div>
                            <span class="line"><span>
                        </div>
    
                        <div id="date-archives">
                            <h3>Date archives</h3>
                            <div class="siteseo_wrap_label"><p>Title template</p></div>
                            <input type="text" name="siteseo_options[date_title]" value="' . esc_attr($option_date_title) . '">
    
                            <div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-date-archive">
                                    <span id="icon" class="dashicons dashicons-insert"></span>DATE ARCHIVES
                                </button>
                                <button class="tag-title-btn" id="btn-date-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-date-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
								siteseo_suggestion_button();
                            echo'</div>
    
                            <div class="siteseo_wrap_label"><p>Meta description template</p></div>
                            <textarea name="siteseo_options[date_desc]">' . esc_attr($option_date_desc) . '</textarea><br>
                            <div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[date_noindex]" type="checkbox" ' . (!empty($option_date_noindex) ? 'checked="yes"' : '') . ' value="1"/>
                                    ' . __('Do not display date archives in search engine results <strong>(noindex)</strong>', 'siteseo') . '
                                </label>
    
                                <label>
                                    <input name="siteseo_options[date_disable]" type="checkbox" ' . (!empty($option_date_disabled) ? 'checked="yes"' : '') . ' value="1"/>
                                    ' . __('Disable date archives', 'siteseo') . '
                                </label>
                            </div>
                            <span class="line"><span>
                        </div>
						
						<div id="search-archives">
							<h3>Search archives</h3>
							<div class="siteseo_wrap_label"><p>Title template</p></div>
							<input type="text" name="siteseo_options[search_title]" value="' . esc_attr($option_search_title) . '">
							
							<div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-search-keyword">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEARCH KEYWORDS
                                </button>
                                <button class="tag-title-btn"  id="btn-search-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-search-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
                                siteseo_suggestion_button();
                            echo'</div>
							
							<div class="siteseo_wrap_label"><p>Meta description template</p></div>
                            <textarea name="siteseo_options[search_desc]">' . esc_attr($option_search_desc) . '</textarea><br>
							<div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[search_noindex]" type="checkbox" ' . (!empty($option_search_noindex) ? 'checked="yes"' : '') . ' value="1"/>
                                    ' . __('Do not display date archives in search engine results <strong>(noindex)</strong>', 'siteseo') . '
                                </label>
							</div>
							<span class="line"><span>
						</div>
						
						<div id="search-archives">
							<h3>404 archives</h3>
							<div class="siteseo_wrap_label"><p>Title template</p></div>
							<input type="text" name="siteseo_options[title_404]" value="' . esc_attr($option_404_title) . '">
							
							<div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-404-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-404-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
                               siteseo_suggestion_button();
                            echo'</div>
							
							<div class="siteseo_wrap_label"><p>Meta description template</p></div>
                            <textarea name="siteseo_options[desc_404]">' . esc_attr($option_404_desc) . '</textarea><br>
						</div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="siteseo_options[archives_tab]" value="1"/>';
    }
    

    static function post_types(){
        global $siteseo;
    
        if(!empty($_POST['submit'])){
			
            self::save_settings();
        }
    
        //$options = $siteseo->titles_settings;
        $options = get_option('siteseo_titles_option_name');
    
        // Load array
        $post_types = $options['titles_single_titles']['post'] ?? '';
        $page_types = $options['titles_single_titles']['page'] ?? '';
    
        // Load settings
		$option_post_enable = isset($post_types['enable']) ? $post_types['enable'] : 1;
        $option_post_title = isset($post_types['title']) ? $post_types['title'] : '';
        $option_post_desc = isset($post_types['description']) ? $post_types['description'] : '';
        $option_noindex = isset($post_types['noindex']) ? $post_types['noindex'] : '';
        $option_nofollow = isset($post_types['nofollow']) ? $post_types['nofollow'] : '';
        $option_date = isset($post_types['date']) ? $post_types['date'] : '';
        $option_thumb_gcs = isset($post_types['thumb_gcs']) ? $post_types['thumb_gcs'] : '';
		$option_page_enable = isset($page_types['enable']) ? $page_types['enable'] : 1;
        $option_page_title = isset($page_types['title']) ? $page_types['title'] : '';
        $option_page_desc = isset($page_types['description']) ? $page_types['description'] : '';
        $option_page_noindex = isset($page_types['noindex']) ? $page_types['noindex'] : '';
        $option_page_nofollow = isset($page_types['nofollow']) ? $page_types['nofollow'] : '';
        $option_page_date = isset($page_types['date']) ? $page_types['date'] : '';
        $option_page_thumb = isset($page_types['thumb_gcs']) ? $page_types['thumb_gcs'] : '';
		
		
        $post_types_fields = array(
            'post-types' => 'Posts',
            'pages-types' => 'Pages',
        );
    
        echo'<table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <div class="siteseo-container">';
                                $is_first = true;
                                foreach($post_types_fields as $post_key => $post_val){
                                    $active_class = $is_first ? 'active' : '';
                                    echo '<a href="#'.$post_key.'" class="'.$active_class.'">'.$post_val.'</a>';
                                    $is_first = false;
                                }
                        echo'</div>
                        </th>
                        <td>
                            <div>
                                <h3>Post Types</h3>
                               <div class="siteseo_wrap_label"><p class="description">Customize your titles & metas for Single Custom Post Types.</p></div>
                            </div>';
							
							if(empty($option_post_title)){
								
								echo'<div class="siteseo_wrap_label">
										<div class="siteseo-notice is-warning">
										<span id="dashicons-warning" class="dashicons dashicons-info"></span>&nbsp;
										<div>
											<p>'.wp_kses_post(__('Some <strong>Custom Post</strong> Types have no <strong>meta title</strong> set! We strongly encourage you to add one by filling in the fields below.', 'siteseo')).'</p>
											<ol>
												<li> '.esc_html__('post_tag', 'siteseo').'</li>
											</ol>
										</div>
									</div>
								</div>';
							}
							
							if(empty($option_page_desc)){

								echo'<div class="siteseo_wrap_label">
										<div class="siteseo-notice is-warning">
										<span id="dashicons-warning" class="dashicons dashicons-info"></span>&nbsp;
										<div>
											<p>'.wp_kses_post(__('Some <strong>Custom Post</strong> Types have no <strong>meta description</strong> set! We strongly encourage you to add one by filling in the fields below..', 'siteseo')).'</p>
											<ol>
												<li> '.esc_html__('post_tag', 'siteseo').'</li>
											</ol>
										</div>
									</div>
								</div>';	
							}
							
                            echo'<div id="post-types">
								
								<div class="siteseo-toggle-container">
									<h3>Posts [post]</h3>&nbsp;&nbsp;
									<div class="siteseo-toggle-switch ' . ($option_post_enable ? 'active' : '') . '" id="siteseo-toggleSwitch-posts"></div>
									<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
									<p class="toggle_state_posts" id="toggle_state_posts">' . ($option_post_enable ? ' Click to hide any SEO metaboxes / columns for this post type' : ' Click to show any SEO metaboxes / columns for this post type') . '</p>
									<input type="hidden" name="siteseo_options[enable_post]" id="enable_post_toggle" value="' . $option_post_enable . '">
								</div>
								
                                <div class="siteseo_wrap_label"><p>Title template</p></div>
                                <input type="text" name="siteseo_options[post_title]" value="'.esc_attr($option_post_title).'">
                                <div class="wrap-tags">
                                    <button class="tag-title-btn" id="btn-post-title">
                                        <span id="icon" class="dashicons dashicons-insert"></span>POST TITLE
                                    </button>
                                    <button class="tag-title-btn" id="btn-post-separator">
                                        <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                    </button>
                                    <button class="tag-title-btn" id="btn-post-site-title">
                                        <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                    </button>';
                                    siteseo_suggestion_button();
                                echo'</div>
                                <div class="siteseo_wrap_label"><p class="description">Meta description template</p></div>
                                <textarea name="siteseo_options[post_desc]">'.esc_attr($option_post_desc).'</textarea>
                                <div class="wrap-tags">
                                    <button class="tag-title-btn" id="btn-posts-meta">
                                        <span id="icon" class="dashicons dashicons-insert"></span>POST EXCERPT
                                    </button>';
									siteseo_suggestion_button();
								echo'</div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[post_noindex]" type="checkbox" '.(!empty($option_noindex) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Do not display this single post type in search engine results<strong>(noindex)</strong>', 'siteseo').'
                                    </label>
                                </div>';
								
								if(!empty($option_noindex)){
									echo'<div class="siteseo_wrap_label">
										<div class="siteseo-notice is-error">
											<p>'.wp_kses_post(__('This custom post type is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. ', 'siteseo')).'
											</p>
										</div>
									</div>';
								}
								
								echo'<div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[post_nofollow]" type="checkbox" '.(!empty($option_nofollow) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Do not follow links for this single post type <strong>nofollow</strong>', 'siteseo').'
                                    </label>
                                </div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[post_date]" type="checkbox" '.(!empty($option_date) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Display date in Google search results by adding article:published_time and article:modified_time meta?', 'siteseo').'
                                    </label>
                                    <p class="description">'. esc_html('Unchecking this does not prevent Google from displaying the post date in search results.','siteseo').'</p>
                                </div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[thumb_gcs]" type="checkbox" '.(!empty($option_thumb_gcs) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Display post thumbnail in Google Custom Search results?', 'siteseo').'
                                    </label>
                                    <p class="description">'.esc_html('This option does not apply to traditional search results.','siteseo').'</p>
                                </div>
                            </div>
                            <div id="pages-types" class="siteseo-titels-pags-tab">

								<div class="siteseo-toggle-container">
									 <h3>Pages [pages]</h3>&nbsp;&nbsp;
									<div class="siteseo-toggle-switch ' . ($option_page_enable ? 'active' : '') . '" id="siteseo-toggleSwitch-pages"></div>
									<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
									<p class="toogle_state_pages" id="toggle_state_pages">' . ($option_page_enable ? ' Click to hide any SEO metaboxes / columns for this post type' : ' Click to show any SEO metaboxes / columns for this post type') . '</p>
									<input type="hidden" name="siteseo_options[page_enable]" id="enable_toggle_page" value="' . $option_page_enable . '">
								</div>
								
                                <div class="siteseo_wrap_label"><p class="description">'.esc_html('Title template','siteseo').'</p></div>
                                <input type="text" name="siteseo_options[page_title]" value="'.esc_attr($option_page_title).'">
                                <div class="wrap-tags">
                                    <button class="tag-title-btn" id="btn-page-title">
                                        <span id="icon" class="dashicons dashicons-insert"></span>PAGE TITLE
                                    </button>
                                    <button class="tag-title-btn" id="btn-page-separator">
                                        <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                    </button>
                                    <button class="tag-title-btn" id="btn-page-sitetitle">
                                        <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                    </button>';
									siteseo_suggestion_button();
                                echo'</div>
                                <div class="siteseo_wrap_label"><p class="description">'. esc_html('Meta description template','siteseo').'</p></div>
                                <textarea name="siteseo_options[page_desc]">'.esc_attr($option_page_desc).'</textarea>
                                <div class="wrap-tags">
                                    <button class="tag-title-btn" id="btn-page-meta">
                                        <span id="icon" class="dashicons dashicons-insert"></span>PAGE EXCERPT
                                    </button>';
                                    siteseo_suggestion_button();
                               echo'</div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[page_noindex]" type="checkbox" '.(!empty($option_page_noindex) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Do not display this single page type in search engine results<strong>(noindex)</strong>', 'siteseo').'
                                    </label>
                                </div>';
								
								if(!empty($option_page_noindex)){
									echo'<div class="siteseo_wrap_label">
										<div class="siteseo-notice is-error">
											<p>'.wp_kses_post(__('This custom post type is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. ', 'siteseo')).'
											</p>
										</div>
									</div>';
								}
								
                            echo'<div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[page_nofollow]" type="checkbox" '.(!empty($option_page_nofollow) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Do not follow links for this single page type <strong>nofollow</strong>', 'siteseo').'
                                    </label>
                                </div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[page_date]" type="checkbox" '.(!empty($option_page_date) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Display date in Google search results by adding article:published_time and article:modified_time meta?', 'siteseo').'
                                    </label>
                                    <p class="description">'.esc_html('Unchecking this does not prevent Google from displaying the page date in search results.','siteseo').'</p>
                                </div>
                                <div class="siteseo_wrap_label">
                                    <label>
                                        <input name="siteseo_options[page_gcs]" type="checkbox" '.(!empty($option_page_thumb) ? 'checked="yes"' : 'value="1"').' />
                                        '.__('Display page thumbnail in Google Custom Search results?', 'siteseo').'
                                    </label>
                                    <p class="description">'.esc_html('This option does not apply to traditional search results.','siteseo').'</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
			<input type="hidden" name="siteseo_options[post_types_tab]" value="1" />';
    }
    
    static function taxonomies(){
        global $siteseo;
    
        if(!empty($_POST['submit'])){
            self::save_settings();
        }
    
        // $options = $siteseo->titles_setting;
        $options = get_option('siteseo_titles_option_name');
    
        // Load array
        $options_cat = $options['titles_tax_titles']['category'] ?? '';
        $option_tags = $options['titles_tax_titles']['post_tag'] ?? '';
    
        // Load settings
		$option_enable_category = isset($options_cat['enable']) ? $options_cat['enable'] : '';
        $option_cat_title = isset($options_cat['title']) ? $options_cat['title'] : '';
        $option_cat_desc = isset($options_cat['description']) ? $options_cat['description'] : '';
        $option_cat_noindex = isset($options_cat['noindex']) ? $options_cat['noindex'] : '';
        $option_cat_nofollow = isset($options_cat['nofollow']) ? $options_cat['nofollow'] : '';
		//tags
		$option_enable_tag = isset($option_tags['enable']) ? $option_tags['enable'] : '';
        $option_tag_title = isset($option_tags['title']) ? $option_tags['title'] : '';
        $option_tag_desc = isset($option_tags['description']) ? $option_tags['description'] : '';
        $option_tag_noindex = isset($option_tags['noindex']) ? $option_tags['noindex'] : '';
        $option_tag_nofollow = isset($option_tags['nofollow']) ? $option_tags['nofollow'] : '';
   
		$taxonomies_fields = [
			'categories-types' => 'Categories',
			'tags-types' => 'Tags',
		];
		
        echo'<table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <div class="siteseo-container">';
    
        $is_first = true;
        foreach($taxonomies_fields as $fields_key => $fields_val){
            $active_class = $is_first ? 'active' : '';
            echo'<a href="#'.$fields_key.'" class="'.$active_class.'">'.$fields_val.'</a>';
            $is_first = false;
        }
    
            echo'</th>
                    <td>
                        <h3>Taxonomies</h3>
						<div class="siteseo_wrap_label">
                        <p class="description">Customize your metas for all taxonomies archives.</p>
						</div>';
						if(empty($option_cat_title)){
								echo'<div class="siteseo_wrap_label">
									<div class="siteseo-notice is-warning">
									<span id="dashicons-warning" class="dashicons dashicons-info"></span>&nbsp;
									<div>
										<p>'.wp_kses_post(__('Some <strong> Custom Taxonomies </strong> have no meta title set! We strongly encourage you to add one by filling in the fields below.', 'siteseo')).'</p>
										<ol>
											<li> '.esc_html__('post_tag', 'siteseo').'</li>
										</ol>
									</div>
								</div>
							</div>';
						}
						
						if(empty($option_tag_desc)){
							echo'<div class="siteseo-notice is-warning">
								<span id="dashicons-warning" class="dashicons dashicons-info"></span>&nbsp;
								<div>
									<p>'.wp_kses_post(__('Some <strong>Custom Taxonomies</strong> have no <strong> meta description</strong> set! We strongly encourage you to add one by filling in the fields below..', 'siteseo')).'</p>
									<ol>
										<li> '.esc_html__('post_tag', 'siteseo').'</li>
									</ol>
								</div>
							</div>';
						}
						
                        echo'<div id="categories-types">
                            <h3>Categories [category]</h3>
							<div class="siteseo-toggle-container">
								<div class="siteseo-toggle-switch ' . ($option_enable_category ? 'active' : '') . '" id="siteseo-toggleSwitch-category"></div>
								<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
								<p class="toggle_state_category" id="toggle_state_category">' . ($option_enable_category ? 'Disable' : 'Enable') . '</p>
								<input type="hidden" name="siteseo_options[enable_category]" id="enable_category" value="' . $option_enable_category . '">
							</div>
							
                            <div class="siteseo_wrap_label">
                                <p class="description">Title template</p>
                            </div>
                            <input type="text" value="'.esc_attr($option_cat_title).'" name="siteseo_options[category_title]">
                            <div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-cate-title">
                                    <span id="icon" class="dashicons dashicons-insert"></span>CATEGORY TITLE
                                </button>
                                <button class="tag-title-btn" id="btn-cate-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-cate-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
									siteseo_suggestion_button(); 
                            echo'</div>
                            <div class="siteseo_wrap_label">
                                <p class="description">Meta description template</p>
                            </div>
                            <textarea name="siteseo_options[category_desc]">'.esc_attr($option_cat_desc).'</textarea>
                            <div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-cate-meta">
                                    <span id="icon" class="dashicons dashicons-insert"></span>DESCRIPTION
                                </button>';
                                siteseo_suggestion_button();
                            echo'</div>
                            <div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[category_noindex]" type="checkbox" '.(!empty($option_cat_noindex) ? 'checked="yes"' : 'value="1"').' />
                                    '.__('Do not display this taxonomy archive in search engine results<strong>(noindex)</strong>', 'siteseo').'
                                </label>
                            </div>';
							
							if(!empty($option_cat_noindex)){
								echo'<div class="siteseo_wrap_label">
									<div class="siteseo-notice is-error">
										<p>'.wp_kses_post(__('This custom taxonomy is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you check this out.', 'siteseo')).'
										</p>
									</div>
								</div>';
							}
							
                            echo'<div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[category_nofollow]" type="checkbox" '.(!empty($option_cat_nofollow) ? 'checked="yes"' : 'value="1"').' />
                                    '.__('Do not follow links for this taxonomy archive<strong>(nofollow)</strong>', 'siteseo').'
                                </label>
                            </div>
                        </div>
                        <div id="tags-types">
                            <h3>Tags [post tag]</h3>
							
							<div class="siteseo-toggle-container">
								<div class="siteseo-toggle-switch ' . ($option_enable_tag ? 'active' : '') . '" id="siteseo-toggleSwitch-post-tags"></div>
								<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
								<p class="toggle_state_posts_tags" id="toggle_state_posts_tags">' . ($option_enable_tag ? 'Disable' : 'Enable') . '</p>
								<input type="hidden" name="siteseo_options[post_tag_enable]" id="enable_posts_tag_toggle" value="' . $option_enable_tag. '">
							</div>
							
                            <div class="siteseo_wrap_label">
                                <p class="description">Title template</p>
                            </div>
                            <input type="text" value="'.esc_attr($option_tag_title).'" name="siteseo_options[tags_title]">
                            <div class="wrap-tags">
                                <button class="tag-title-btn" id="btn-tags-title">
                                    <span id="icon" class="dashicons dashicons-insert"></span>TAG TITLE
                                </button>
                                <button class="tag-title-btn" id="btn-tags-separator">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SEPARATOR
                                </button>
                                <button class="tag-title-btn" id="btn-tags-sitetitle">
                                    <span id="icon" class="dashicons dashicons-insert"></span>SITE TITLE
                                </button>';
                                siteseo_suggestion_button();
                            echo'</div>
                            <div class="siteseo_wrap_label">
                                <p class="description">Meta description template</p>
                            </div>
                            <textarea name="siteseo_options[tags_description]">'.esc_attr($option_tag_desc).'</textarea>
                            <div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[tags_noindex]" type="checkbox" '.(!empty($option_tag_noindex) ? 'checked="yes"' : 'value="1"').' />
                                    '.__('Do not display this taxonomy archive in search engine results<strong>(noindex)</strong>', 'siteseo').'
                                </label>
                            </div>';
							
							if(!empty($option_tag_noindex)){
								echo'<div class="siteseo_wrap_label">
									<div class="siteseo-notice is-error">
										<p>'.wp_kses_post(__('This custom taxonomy is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you check this out.', 'siteseo')).'
										</p>
									</div>
								</div>';
							}
							
							echo'<div class="siteseo_wrap_label">
								 <div class="siteseo-notice is-warning">
									<p>We do not recommend indexing <strong>tags</strong> which are, in the vast majority of cases, a source of duplicate content</p>
								</div>
							</div>
                            <div class="siteseo_wrap_label">
                                <label>
                                    <input name="siteseo_options[tags_nofollow]" type="checkbox" '.(!empty($option_tag_nofollow) ? 'checked="yes"' : 'value="1"').' />
                                    '.__('Do not follow links for this taxonomy archive<strong>(nofollow)</strong>', 'siteseo').'
                                </label>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="siteseo_options[taxonomies_tab]" value="1" />';
    }
    

    static function save_settings(){
        global $siteseo;
		
		check_admin_referer('sitseo_title_settings');

		if(!current_user_can('manage_options') || !is_admin()){
			return;
		}

		$options = [];
        
		if(empty($_POST['siteseo_options'])){
			return;
		}

        if(isset($_POST['siteseo_options']['home_tab'])){
            
            $options['titles_sep'] = isset($_POST['siteseo_options']['separator']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['separator'])) : '';
            
			$options['titles_home_site_title'] = isset($_POST['siteseo_options']['site_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['site_title'])) : '';
            
			$options['titles_home_site_title_alt'] = isset($_POST['siteseo_options']['alt_site_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['alt_site_title'])) : '';
            
			$options['titles_home_site_desc'] = isset($_POST['siteseo_options']['media_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['media_desc'])) : '';
        }

        if(isset($_POST['siteseo_options']['advanced_tab'])){

            $options['titles_noindex'] = isset($_POST['siteseo_options']['noindex']);
			$options['titles_nofollow'] = isset($_POST['siteseo_options']['no_follow']);
			$options['titles_noimageindex'] = isset($_POST['siteseo_options']['no_image']);
			$options['titles_noarchive'] = isset($_POST['siteseo_options']['no_archive']);
            $options['titles_nosnippet'] = isset($_POST['siteseo_options']['no_snippet']);
            $options['titles_nositelinkssearchbox'] = isset($_POST['siteseo_options']['no_site_links_searchbox']);
            $options['titles_paged_rel'] = isset($_POST['siteseo_options']['page_rel']);
            $options['titles_paged_noindex'] = isset($_POST['siteseo_options']['titles_paged_noindex']);
            $options['titles_attachments_noindex'] = isset($_POST['siteseo_options']['attachments_noindex']);

        }

        if(isset($_POST['siteseo_options']['post_types_tab'])){
			
			$options['titles_single_titles']['post']['enable'] = isset($_POST['siteseo_options']['enable_post']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['enable_post'])) : '';
            
			$options['titles_single_titles']['post']['title'] = isset($_POST['siteseo_options']['post_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['post_title'])) : '';
            
			$options['titles_single_titles']['post']['description'] = isset($_POST['siteseo_options']['post_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['post_desc'])) : '';
            
			$options['titles_single_titles']['post']['noindex'] = isset($_POST['siteseo_options']['post_noindex']);
            
			$options['titles_single_titles']['post']['nofollow'] = isset($_POST['siteseo_options']['post_nofollow']);
            
			$options['titles_single_titles']['post']['date'] = isset($_POST['siteseo_options']['post_date']);
            
			$options['titles_single_titles']['post']['thumb_gcs'] = isset($_POST['siteseo_options']['thumb_gcs']);
			
			$options['titles_single_titles']['page']['enable'] = isset($_POST['siteseo_options']['page_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['page_enable'])) : '';
            
			$options['titles_single_titles']['page']['title'] = isset($_POST['siteseo_options']['page_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['page_title'])) : '';
            
			$options['titles_single_titles']['page']['description'] = isset($_POST['siteseo_options']['page_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['page_desc'])) : '';
            
			$options['titles_single_titles']['page']['noindex'] = isset($_POST['siteseo_options']['page_noindex']);
            
			$options['titles_single_titles']['page']['nofollow'] = isset($_POST['siteseo_options']['page_nofollow']);
            
			$options['titles_single_titles']['page']['date'] = isset($_POST['siteseo_options']['page_date']);
            
			$options['titles_single_titles']['page']['thumb_gcs'] = isset($_POST['siteseo_options']['page_gcs']);

        }

        if(isset($_POST['siteseo_options']['archives_tab'])){

            $options['titles_archives_author_title'] = isset($_POST['siteseo_options']['author_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['author_title'])) : '';
            
			$options['titles_archives_author_desc'] = isset($_POST['siteseo_options']['author_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['author_desc'])) : '';
            
			$options['titles_archives_author_noindex'] = isset($_POST['siteseo_options']['author_noindex']);
            
			$options['titles_archives_author_disable'] = isset($_POST['siteseo_options']['author_disable']);
            
			$options['titles_archives_date_title'] = isset($_POST['siteseo_options']['date_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['date_title'])) : '';
            
			$options['titles_archives_date_desc'] = isset($_POST['siteseo_options']['date_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['date_desc'])) : '';
            
			$options['titles_archives_date_noindex'] = isset($_POST['siteseo_options']['date_noindex']);
            
			$options['titles_archives_date_disable'] = isset($_POST['siteseo_options']['date_disable']);
            
			$options['titles_archives_search_title'] = isset($_POST['siteseo_options']['search_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['search_title'])) : '';
            
			$options['titles_archives_search_desc'] = isset($_POST['siteseo_options']['search_desc']) ? sanitize_text_field(wp_unslash(
			$_POST['siteseo_options']['search_desc'])) : '';
            
			$options['titles_archives_search_title_noindex'] = isset($_POST['siteseo_options']['search_noindex']);
            
			$options['titles_archives_404_title'] = isset($_POST['siteseo_options']['title_404']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['title_404'])) : '';
            
			$options['titles_archives_404_desc'] = isset($_POST['siteseo_options']['desc_404'])  ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['desc_404'])) : '';

        }

        if(isset($_POST['siteseo_options']['taxonomies_tab'])){
        
			$options['titles_tax_titles']['category']['enable'] = isset($_POST['siteseo_options']['enable_category']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['enable_category'])) : '';
			
			$options['titles_tax_titles']['category']['title'] = isset($_POST['siteseo_options']['category_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['category_title'])) : '';
			
			$options['titles_tax_titles']['category']['description'] = isset($_POST['siteseo_options']['category_desc']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['category_desc'])) : '';
			
			$options['titles_tax_titles']['category']['noindex'] = isset($_POST['siteseo_options']['category_noindex']);
			
			$options['titles_tax_titles']['category']['nofollow'] = isset($_POST['siteseo_options']['category_nofollow']);
			
			$options['titles_tax_titles']['post_tag']['enable'] = isset($_POST['siteseo_options']['post_tag_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['post_tag_enable'])) : '';
			
			$options['titles_tax_titles']['post_tag']['title'] = isset($_POST['siteseo_options']['tags_title']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['tags_title'])) : '';
			
			$options['titles_tax_titles']['post_tag']['description'] = isset($_POST['siteseo_options']['tags_description']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['tags_description'])) : '';
			
			$options['titles_tax_titles']['post_tag']['noindex'] = isset($_POST['siteseo_options']['tags_noindex']);
			$options['titles_tax_titles']['post_tag']['nofollow'] = isset($_POST['siteseo_options']['tags_nofollow']);
        
        }

        // $options = $siteseo->titles_settings;
        update_option('siteseo_titles_option_name', $options);
    }
    

 }