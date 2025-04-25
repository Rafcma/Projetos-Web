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

class Advanced{

	static function menu(){
		global $siteseo;

		Dashbord::admin_header();

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_image_seo'; // Default tab

		$titles_meta_subtabs = [
			'tab_image_seo' => esc_html__('Image SEO', 'siteseo'),
			'tab_advanced' => esc_html__('Advanced', 'siteseo'),
			'tab_appearance' => esc_html__('Appearance', 'siteseo'),
			'tab_security' => esc_html__('Security', 'siteseo'),
			'tab_breadcrumbs' => esc_html('Breadcrumbs','siteseo'),
			'tab_toc' => esc_html('Table of content', 'siteseo'),
			'tab_robots_txt' => esc_html('robots.txt','siteseo'),
			'tab_htaccess' => esc_html('htaccess','siteseo')
		];

		echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';

		wp_nonce_field('siteseo_advance_settings');
		
		$advanced_toggle = isset($siteseo->setting_enabled['toggle-advanced']) ? $siteseo->setting_enabled['toggle-advanced'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');

		Dashbord::render_toggle('Image SEO & Advanced settings - SiteSEO', 'advanced_toggle', $advanced_toggle, $nonce);

		echo'<div id="siteseo-tabs" class="wrap">
			<div class="nav-tab-wrapper">';

		foreach($titles_meta_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo'</div>     
		<div class="tab-content-wrapper">
		<div class="siteseo-tab '. ($current_tab == 'tab_image_seo' ? ' active' : '') .'" id="tab_image_seo">';
		self::image_seo();
		echo'</div>
		<div class="siteseo-tab '.($current_tab == 'tab_advanced' ? ' active' : '')  .'" id="tab_advanced">';
		self::advanced();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_appearance' ? ' active' : '') .'" id="tab_appearance">';
		self::appearance();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_security' ? ' active' : '') .'" id="tab_security">';
		self::security();
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_breadcrumbs' ? ' active' : '') .'" id="tab_breadcrumbs">';
		self::breadcrumbs(); 
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_toc' ? ' active' : '') .'" id="tab_toc">';
		self::toc_tab(); 
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_robots_txt' ? ' active' : '') .'" id="tab_robots_txt">';
		self::robots_tab(); 
		echo'</div>
		<div class="siteseo-tab '. ($current_tab == 'tab_htaccess' ? ' active' : '') .'" id="tab_htaccess">';
		self::htaccess(); 
		echo'</div>
		</div>';

		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo'</form>';

	}

	static function image_seo(){
		global $siteseo;

		if(!empty($_POST['submit'])){

            self::save_settings();
        }

		//$options = $siteseo->$advanced_settings;
		$options = get_option('siteseo_advanced_option_name');

		$option_attachment = isset($options['advanced_attachments']) ? $options['advanced_attachments'] : '';
		$option_attachment_file = isset($options['adavnced_attachments_file']) ? $options['adavnced_attachments_file'] : '';
		$option_clean_filename = isset($options['advanced_clean_filename']) ? $options['advanced_clean_filename'] : '';
		$option_img_title = isset($options['advanced_image_auto_title_editor']) ? $options['advanced_image_auto_title_editor'] : '';
		$option_img_alt = isset($options['advanced_image_auto_alt_editor']) ? $options['advanced_image_auto_alt_editor'] : '';
		$option_target_key = isset($options['advanced_image_auto_alt_target_kw']) ? $options['advanced_image_auto_alt_target_kw'] : '';
		$option_cap_img = isset($options['advanced_image_auto_caption_editor']) ? $options['advanced_image_auto_caption_editor'] : '';
		$option_desc_img = isset($options['advanced_image_auto_desc_editor']) ? $options['advanced_image_auto_desc_editor'] : '';

		echo'<h3 class="siteseo-tabs">Image SEO</h3>
        <p>Images can generate a lot of traffic to your site. Make sure to always add alternative texts, optimize their file size, filename etc..</p>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Redirect attachment pages to post parent</th>
                    <td>
                        <label>
				        <input name="siteseo_options[attachment]" type="checkbox"' . (!empty($option_attachment) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Redirect attachment pages to post parent (or homepage if none)', 'siteseo') . 
			            '</label>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Redirect attachment pages to their file URL</th>
                    <td>
                        <label>
                            <input name="siteseo_options[attachment_file]" type="checkbox"' . (!empty($option_attachment_file) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Redirect attachment pages to their file URL (https://www.example.com/my-image-file.jpg)', 'siteseo') . 
                        '</label>
                        <p class="description">If this option is checked, it will take precedence over the redirection of attachments to the post parent.</p>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Cleaning media filename</th>
                    <td>
                        <label>
                            <input name="siteseo_options[clean_filename]" type="checkbox"' . (!empty($option_clean_filename) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('When upload a media, remove accents, spaces, capital letters... and force UTF-8 encoding', 'siteseo') . 
                        '</label>
                        <p class="description">e.g. "ExãMple 1 cópy!.jpg" => "example-1-copy.jpg"</p>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Automatically set the image Title</th>
                    <td>
                        <label>
                            <input name="siteseo_options[auto_img_title]" type="checkbox"' . (!empty($option_img_title) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('when uploading an image file, automatically set the title based on the filename', 'siteseo') . 
                        '</label>
                        <p class="description">we use product title for WooCommerce products.</p>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Automatically set the image Alt txt</th>
                    <td>
                        <label><input name="siteseo_options[auto_img_alt]" type="checkbox"' . (!empty($option_img_alt) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('When uploading an image file, automatically set the alternative text based on the filename', 'siteseo') . 
                        '</label>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Automatically set the image Alt text from target keywords	</th>
                    <td>
                        <label>
                            <input name="siteseo_options[auto_target_keyword]" type="checkbox"' . (!empty($option_target_key) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Use the target keywords if not alternative text set for the image', 'siteseo') . 
                        '</label>
                        <p class="description">This setting will be applied to images without any alt text only on frontend. This setting is retroactive. If you turn it off, alt texts that were previously empty will be empty again.</p>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Automatically set the image Caption</th>
                    <td>
                        <label>
                            <input name="siteseo_options[caption_image]" type="checkbox"' . (!empty($option_cap_img) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('When uploading an image file, automatically set the caption based on the filename', 'siteseo') . 
                        '</label>
                    </td>
                <tr>

				<tr>
                    <th scope="row" style="user-select:auto;">Automatically set the image Description</th>
                    <td>
                        <label>
                            <input name="siteseo_options[description_img]" type="checkbox"' . (!empty($option_desc_img) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('When uploading an image file, automatically set the description based on the filename', 'siteseo') . 
                        '</label>
                    </td>
                <tr>

			</tbody>
		</table><input type="hidden" name="siteseo_options[image_seo]" value="1"/>';
	}
	
	static function advanced(){
		global $siteseo;

		if(!empty($_POST['submit'])){
			save_settings();
		}
		
		//$options = $siteseo->advanced_settings;
		$options = get_option('siteseo_advanced_option_name');
		
		$option_taxonomy_desc = isset($options['advanced_tax_desc_editor']) ? $options['advanced_tax_desc_editor'] : '';
		$option_category_url = isset($options['advanced_category_url']) ? $options['advanced_category_url'] : '';
		$option_noreferrer_link = isset($options['advanced_noreferrer']) ? $options['advanced_noreferrer'] : '';
		$option_wp_generator = isset($options['advanced_wp_generator']) ? $options['advanced_wp_generator'] : '';
		$option_hentry_post = isset($options['advanced_hentry']) ? $options['advanced_hentry'] : '';
		$option_author_url = isset($options['advanced_comments_author_url']) ? $options['advanced_comments_author_url'] : '';
		$option_site_fileds = isset($options['advanced_comments_website']) ? $options['advanced_comments_website'] : '';
		$option_rel_attributes = isset($options['advanced_comments_form_link']) ? $options['advanced_comments_form_link'] : '';
		$option_shortlink = isset($options['advanced_wp_shortlink']) ? $options['advanced_wp_shortlink'] : '';
		$option_wlw_meta_tag = isset($options['advanced_wp_wlw']) ? $options['advanced_wp_wlw'] : '';
		$option_rsd_meta_tag = isset($options['advanced_wp_rsd']) ? $options['advanced_wp_rsd'] : '';
		$option_google_meta_value = isset($options['advanced_google']) ? $options['advanced_google'] : '';
		$option_bring_meta_value = isset($options['advanced_bing']) ? $options['advanced_bing'] : '';
		$option_pinterest_meta_value = isset($options['advanced_pinterest']) ? $options['advanced_pinterest'] : '';
		$option_yandex_meta_value = isset($options['advanced_yandex']) ? $options['advanced_yandex'] : ''; 
		
		echo'<h3 class="siteseo-tabs">Advanced</h3>
		<p class="description">Advanced SEO options for advanced users.</p>
		<table class="form-table"/>
			<tbody>
				<tr>
					<th scope="row" style="user-select:auto;">Add WP Editor to taxonomy description textarea</th>
					<td>
						<label>
				        <input name="siteseo_options[taxonomy_desc]" type="checkbox"' . (!empty($option_taxonomy_desc) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Add TINYMCE editor to term description', 'siteseo') . 
			            '</label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove /category/ in URL</th>
					<td>
						<label>
				        <input name="siteseo_options[category_url]" type="checkbox"' . (!empty($option_category_url) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove /category/ in your permalinks', 'siteseo') . 
			            '</label>
						<p class="description">e.g. "https://example.com/category/my-post-category/" => "https://example.com/my-post-category/"</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row">Remove product category base from permalinks</th>
					<td>
						<div class="siteseo_wrap_label">
							<div class="siteseo-notice is-warning">
							<span id="dashicons-warning" class="dashicons dashicons-info"></span>&nbsp;
							<div>
								<p>'.wp_kses_post(__('You need to enable <strong>WooCommerce</strong> to apply these settings.', 'siteseo')).'</p>
							</div>
							</div>
						</div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove noreferrer link attribute in post content</th>
					<td>
						<label>
				        <input name="siteseo_options[noreferrer_link]" type="checkbox"' . (!empty($option_noreferrer_link) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove noreferrer link attribute in source code', 'siteseo') . 
			            '</label>
						<p class="description">Useful for affiliate links (eg: Amazon).</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove WordPress meta generator tag</th>
					<td>
						<label>
							<input name="siteseo_options[wp_generator_meta]" type="checkbox"' . (!empty($option_wp_generator) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove WordPress meta generator in source code', 'siteseo') . 
						'</label>
						
						<div class="siteseo-styles pre"><pre>' . esc_html('<meta name="generator" content="WordPress 6.0.3" />') . '</pre></div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove hentry post class</th>
					<td>
						<label>
							<input name="siteseo_options[hentry_post]" type="checkbox"' . (!empty($option_hentry_post) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove hentry post class to prevent Google from seeing this as structured data (schema)', 'siteseo') . 
						'</label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove author URL</th>
					<td>
						<label>
							<input name="siteseo_options[comments_author_url]" type="checkbox"' . (!empty($option_author_url) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__(' Remove comment author URL in comments if the website is filled from profile page', 'siteseo') . 
						'</label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove website field from comment form</th>
					<td>
						<label for="">
							<input name="siteseo_options[website_filed]" type="checkbox"' . (!empty($option_site_fileds) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove website field from comment form to reduce spam', 'siteseo') . 
						'</label>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Add "nofollow noopener noreferrer" rel attributes to the comments form link</th>
					<td>
						<label>
							<input name="siteseo_options[comment_form_link]" type="checkbox"' . (!empty($option_rel_attributes) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Prevent search engines to follow / index the link to the comments form', 'siteseo') . 
						'</label>
						
						<div class="siteseo-styles pre"><pre>' . esc_html('https://www.example.com/my-blog-post/#respond') . '</pre></div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove WordPress shortlink meta tag</th>
					<td>
						<label>
							<input name="siteseo_options[shortlink]" type="checkbox"' . (!empty($option_shortlink) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove WordPress shortlink meta tag in source', 'siteseo') . 
						'</label>
						
						<div class="siteseo-styles pre"><pre>' . esc_html('<link rel="shortlink" href="https://www.example.com/"/>') . '</pre></div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove Windows Live Writer meta tag</th>
					<td>
						<label>
							<input name="siteseo_options[wlw_meta]" type="checkbox"' . (!empty($option_wlw_meta_tag) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove Windows Live Writer meta tag in source code', 'siteseo') . 
						'</label>
						
						<div class="siteseo-styles pre"><pre>' . esc_html('<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://www.example.com/wp-includes/wlwmanifest.xml" />') . '</pre></div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Remove RSD meta tag</th>
					<td>
						<label>
							<input name="siteseo_options[rsd_meta]" type="checkbox"' . (!empty($option_rsd_meta_tag) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove Really Simple Discovery meta tag in source code', 'siteseo') . 
						'</label>
						<p class="description">WordPress Site Health feature will return a HTTPS warning if you enable this option. This is a false positive of course.</p>
						<div class="siteseo-styles pre"><pre>' . esc_html('<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://www.example.com/wp-includes/wlwmanifest.xml" />') . '</pre></div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Google site verification</th>
					<td>
						<input name="siteseo_options[google_meta_value]" type="text" placeholder="Enter Google meta value site verification" value="'.$option_google_meta_value.'"/>
						<p class="description">If your site is already verified in <strong>Google Search Console</strong>, you can leave this field empty.</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Bing site verification</th>
					<td>
						<input name="siteseo_options[bring_meta_value]" type="text" placeholder="Enter Google meta value site verification" value="'.$option_bring_meta_value.'"/>
						<p class="description">If your site is already verified in <strong>Bing Webmaster tools</strong>, you can leave this field empty.</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Pinterest site verification</th>
					<td>
						<input name="siteseo_options[pinterest_meta_value]" type="text" placeholder="Enter Pinterest meta value site verification" value="'.$option_pinterest_meta_value.'"/>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Yandex site verification</th>
					<td>
						<input name="siteseo_options[yandex_meta_value]" type="text" placeholder="Enter Yandex meta value site verification" value="'.$option_yandex_meta_value.'"/>
					</td>
				</tr>
			</tbody>
		</table><input type="hidden" name="siteseo_options[advanced_tab]" value="1"/>';
		
	}
	
	static function appearance(){
		
		global $siteseo;
		
		if(!empty($_POST['submit'])){
			self::save_settings();
		}
		
		$appearance_fields =[
			'metaboxes'=>'Metaboxes',
			'Admin-bar'=>'Admin bar',
			'SEO-Dashbord' => 'SEO Dashbord',
			'Columns' => 'Columns',
			'Misc' =>'Misc',
		];
		
		echo'<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
						 <div class="siteseo-container">';
							$is_first = true;
							foreach($appearance_fields as $post_key => $post_val){
								$active_class = $is_first ? 'active' : '';
								echo '<a href="#'.$post_key.'" class="'.$active_class.'">'.$post_val.'</a>';
								$is_first = false;
							}
						echo'</div>
						</th>
						<td>
							<div>
								<h3>Appearance</h3>
								<div class="siteseo_wrap_label">
									<p class="description">Customize the plugin to fit your needs.</p>
								</div>
								<span class="line"></span>
								<h3>Metaboxes</h3>
								<p>'.esc_html('Edit your SEO metadata directly from your favorite page builder.','siteseo').'</p>
								<table class="form-table">
									<tbody>
										<tr>
											<th scope="row">Universal Metabox (Gutenberg)</th>
											<td><label><input type="checkbox" name="siteseo_options[]"  value=""/>
												' . __('Enable the universal SEO metabox for the Block Editor (Gutenberg)', 'siteseo') . '
												<label>
											</td>
										</tr>
										
										<tr>
											<th scope="row">Disable Universal Metabox</th>
											<td></label><input type="checkbox" name="siteseo_options" value=""/>
												'.__('Disable the universal SEO metabox','siteseo').'
											</td>
										</tr>
										
										<tr>
											<th scope="row">Move SEO metaboxs position</th>
											<td><select>
												
												</select>
											</td>
										</tr>
										
										<tr>
											<th scope="row">Remove Content Analysis Metabox</th>
											<td>
												<label><input type="checkbox" value="" name="siteseo_options[]">
												'.__(' Remove Content Analysis Metabox','siteseo').'
												</label>
												<p class="description">By checking this option, we will no longer track the significant keywords.</p>
											</td>
										</tr>
										
										
										<tr>
											<th scope=row">Hide Genesis SEO Metabox</th>
											<td>
												<label><input type="checkbox" value="" name="siteseo_options[]">
													'.__(' Remove Genesis SEO Metabox','siteseo').'
												</label>
											</td>
										</tr>
										
										<tr>
											<th scope="row">Hide advice in Structured Data Types metabox</th>
											<td>
												<label><input type="checkbox" value="" name="siteseo_options[]">
												'.__(' Remove the advice if None schema selected','siteseo').'
												</label>
											</td>
										</tr>
									</tbody>
									</table>
									<span class="line"></span>
									<h3>Admin bar</h3>
									<p class="description">The admin bar appears on the top of your pages when logged in to your WP admin.</p>
									<table>
									<tbody>
										<tr>
											<th scope="row">SEO in admin bar</th>
											<td>
												<label><input type="checkbox" value="" name="siteseo_options[]">
												'.__('Remove SEO from Admin Bar in backend and frontend','siteseo').'
												</label>
											</td>
										</tr>
										
										<tr>
											<th scope="row">Noindex in admin bar</th>
											<td>
												<label><input type="checkbox" value="" name="siteseo_options[]">
												'.__('Remove noindex item from Admin Bar in backend and frontend','siteseo').'
												</label>
											</td>
										</tr>
										
									</tbody>
									</table>
									<span class="line"></span>
									<h3>SEO Dashboard</h3>
									<table>
										<tbody>
											<tr>
												<th scope="row">Show Title tag column in post types</th>
												<td><label><input type="checkbox" value="" name="siteseo_options[]">
												'.__('Show Title tag column in post types','siteseo').'</label>
												</td>
											</tr>
											
											<tr>
												<th scope="row">Show Meta description column in post types</th>
												<td>
											</tr>
										</tbody>
									</table>
							</div>
						</td>
				</tr>
			</tbody>
		</table>';

	}
	
	
	static function security(){
		
		global $siteseo;
		
		if(!empty($_POST['submit'])){
			self::save_settings();
		}
		
		echo'<h3 class="siteseo-tabs">Security</h3>';

	}

	static function breadcrumbs(){
		
		if(!empty($_POST['submit'])){
			siteseo_save_advanced_settings();
		}
	
		$options = get_option('siteseo_advanced_option_name', []);
		$enabled = !empty($options) && isset($options['breadcrumbs_enable']);
		$separators = ['-', '|', '/', '←', '→', '↠', '⇒', '►', '—', '•', '»', '›', '–'];
		$separator = !empty($options['breadcrumbs_seperator']) ? $options['breadcrumbs_seperator'] : '';
		$custom_separator = !empty($options['breadcrumbs_custom_seperator']) ? $options['breadcrumbs_custom_seperator'] : '';
		$hide_home = isset($options['breadcrumbs_home']) ? $options['breadcrumbs_home'] : false;
		$home_label = !empty($options['breadcrumb_home_label']) ? $options['breadcrumb_home_label'] : __('Home', 'siteseo');
		$prefix = !empty($options['breadcrumb_prefix']) ? $options['breadcrumb_prefix'] : '';

		echo'<div class="siteseo-section-header">
		<h2>' . esc_html__('Breadcrumbs', 'siteseo') . '</h2>
		</div>
		<p>' . esc_html__('Breadcrumbs work as a navigation tool for users, helping them know their current location and providing quick links to their previous browsing path, which improves the user experience.', 'siteseo') . '</p>

		<table class="form-table">
			<tr>
				<th scope="row">' . esc_html__('Enable Breadcrumbs', 'siteseo') . '</th>
				<td>
					<label>
						<input type="checkbox" value="1" id="siteseo_breadcrumbs_enable" name="siteseo_advanced_option_name[breadcrumbs_enable]" ' . checked($enabled, true, false) . '/>
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Breadcrumbs Display Methods', 'siteseo') . '</th>
				<td>
					<div class="siteseo-inner-tabs-wrap">
						<input type="radio" id="siteseo-breadcrumbs-gutenberg" name="siteseo-inner-tabs" checked>
						<input type="radio" id="siteseo-breadcrumbs-shortcode" name="siteseo-inner-tabs">
						<input type="radio" id="siteseo-breadcrumbs-php" name="siteseo-inner-tabs">
						
						<ul class="siteseo-inner-tabs">
							<li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-gutenberg"><span class="dashicons dashicons-block-default"></span>' . esc_html__('Gutenberg Blocks', 'siteseo') . '</label></li>
							<li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-shortcode"><span class="dashicons dashicons-shortcode"></span>' . esc_html__('Shortcode', 'siteseo') . '</label></li>
							<li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-php"><span class="dashicons dashicons-editor-code"></span>' . esc_html__('PHP Code', 'siteseo') . '</label></li>
						</ul>
						
						<div class="siteseo-inner-tab-content">
							<h4>' . esc_html__('Gutenberg Block', 'siteseo') . '</h4>
							<p>' . esc_html__('Generate Block can be accessed by going to edit post using the Gutenberg Editor, the default editor of WordPress. There search for Breadcrumbs block.', 'siteseo') . '</p>
						</div>
						
						<div class="siteseo-inner-tab-content">
							<h4>' . esc_html__('Shortcode', 'siteseo') . '</h4>
							<p>' . esc_html__('WordPress shortcodes are shortcuts ([shortcode]) that insert features without coding. You can use these shortcodes with Classic Editor, Gutenberg, or any other editor. Copy the shortcode below and use it in the editor.', 'siteseo') . '</p>
							<pre>[siteseo_breadcrumbs]</pre>
						</div>
						
						<div class="siteseo-inner-tab-content">
							<h4>' . esc_html__('PHP Code', 'siteseo') . '</h4>
							<p>' . esc_html__('You can add the breadcrumbs by directly adding PHP code. Make sure you are aware of what you are doing. Use the code below anywhere in your theme.', 'siteseo') . '</p>
							<pre>' . esc_html("<?php if(function_exists('siteseo_render_breadcrumbs')){ echo siteseo_render_breadcrumbs(); } ?>") . '</pre>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Separator', 'siteseo') . '</th>
				<td>
					<div class="siteseo_breadcrumbs_seperator_callback">
					   <div class="siteseo-seperator-btns">';
		foreach($separators as $sep){
			$checked = ($separator == $sep) ? 'checked' : '';
			echo'<label>
				<input type="radio" name="siteseo_advanced_option_name[breadcrumbs_seperator]" value="' . esc_attr($sep) . '" ' . esc_attr($checked) . '/>
				' . esc_html($sep) . '</label>';
		}
		echo'</div>
						<input type="text" style="width:200px" name="siteseo_advanced_option_name[breadcrumbs_custom_seperator]" placeholder="' . esc_html__('Custom Separator', 'siteseo') . '" value="' . esc_attr($custom_separator) . '"/>
					</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Home Settings', 'siteseo') . '</th>
				<td>
					<div>
						<label style="margin:10px 0;">
							<input type="checkbox" name="siteseo_advanced_option_name[breadcrumbs_home]" ' . checked($hide_home, true, false) . '/>
							' . esc_html__('Hide Home', 'siteseo') . '
						</label>
						<label>
							<input type="text" name="siteseo_advanced_option_name[breadcrumb_home_label]" placeholder="' . esc_attr__('Homepage label', 'siteseo') . '" value="' . esc_attr($home_label) . '"/>
							<p class="description">' . esc_html__('Home label', 'siteseo') . '</p>
						</label>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Prefix', 'siteseo') . '</th>
				<td>
					<div>
						<label>
							<input type="text" id="siteseo_breadcrumbs_prefix" name="siteseo_advanced_option_name[breadcrumb_prefix]" placeholder="' . esc_attr__('Breadcrumb Prefix', 'siteseo') . '" value="' . esc_attr($prefix) . '"/>
						</label>
					</div>
				</td>
			</tr>
		</table>';

	}
	
	
	static function robots_tab(){

		global $siteseo;

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		echo'<p>'. esc_html__('Manage your robots.txt file here. Adjust settings according to your SEO requirements.', 'siteseo') . '</p>';
		echo'<table class="form-table">';

		if(!file_exists(ABSPATH . 'robots.txt')){
			echo'<tr><td colspan="2"><button class="btn btnSecondary" id="siteseo-create-robots">'.esc_html__('Create robots.txt', 'siteseo').'</button><span class="spinner"></span></td></tr>';
		} else{
			echo'<tr><th class="row">'.esc_html__('Preview', 'siteseo').'</th><td colspan="2"><a href="'.esc_url(get_home_url()).'/robots.txt" class="btn btnSecondary" target="_blank">'.esc_html__('View Robots.txt', 'siteseo').'</a></td></tr>';
		}

		if(!file_exists(ABSPATH . 'robots.txt')){
			echo'</table>';
			return;
		}

		$robots_txt = file_exists(ABSPATH . 'robots.txt') ? file_get_contents(ABSPATH . 'robots.txt') : '';

			echo'<tr>
				<th class="row">'.esc_html__('robots.txt File', 'siteseo').'</th>
					<td colspan="2">
						<textarea id="siteseo_robots_file_content" placeholder="'.esc_attr__('Enter your robots.txt rules here', 'siteseo').'" rows="15" cols="50">' . esc_textarea($robots_txt) . '</textarea>
					</td>
				</tr>
			<tr>
			<th></th>
				<td colspan="2">
					<button  class="btn btnSecondary" id="siteseo-update-robots">'.esc_html__('Update robots.txt', 'siteseo').'</button>
					<span class="spinner"></span>
				</td>
		</tr>	
		</table>';
	}
	
	static function htaccess(){

		global $siteseo;

		$home_path = get_home_path();
		$htaccess_file = $home_path . '.htaccess';

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		echo'<h3 class="siteseo-tabs">htaccess</h3>
		<p class="description">'.esc_html('Edit your .htaccess file to configure advanced settings for your site','siteseo').'</p>';


		if(!file_exists($htaccess_file) || !is_writable($htaccess_file)){
			echo '<table class="siteseo-notice-table">
					<tr>
						<td class="siteseo-notice is-error"><p>'.esc_html__('The .htaccess file does not exist or You do not have permission to edit the .htaccess file', 'siteseo').'</p>
						</td>
					</tr>
				</table>';
			return;
		}

		echo'<table class="siteseo-notice-table" style="width: 82%;padding-left:42%">
			<tr>
				<th class="row"></th>
					<td colspan="2" class="siteseo-notice is-error">
						'.esc_html__('Be careful editing this file. If any incorrect edits are made, your site could go down. You can restore the htaccess file by replacing it with the backup copy created by SiteSEO with name .htaccess_backup.siteseo', 'siteseo').'
						<label style="margin-top:10px; display: block;">
							<input type="checkbox" value="1" id="siteseo_htaccess_enable"/><strong>I understand the risk and I want to edit this file.</strong>
						</label>
					</td>
			</tr>
		</table>';

		$htaccess_code = file_get_contents($htaccess_file);

		echo'<table class="form-table" style="width: 100%;">
			<tr>
				<th class="row">Edit your htaccess file</th>
				<td>
					<textarea id="siteseo_htaccess_file" name="siteseo_advanced_option_names[htaccess_code]" rows="22" style="width: 100%;">' . esc_textarea($htaccess_code) . '</textarea>
				</td>
			</tr>
			<tr>
				<th class="row">
					<td style="padding-top: 10px;">
						<div style="display: flex; align-items: center;">
							<button id="siteseo_htaccess_btn" class="btn btnSecondary">'.esc_html__('Update htaccess.txt', 'siteseo').'</button>
							<span class="spinner" style="margin-left: 10px;"></span>
						</div>
					</td>
				</th>
			</tr>
		</table>';

	}
	
	static function toc_tab(){
		global $siteseo;
		
		if(!empty($_POST['submit'])){
			self::save_settings();
		}
		
		$options = get_option('siteseo_advanced_option_name');
		
		$option_enable_toc = isset($options['toc_enable']) ? $options['toc_enable'] : '';
		$option_toc_label = isset($options['toc_label']) ? $options['toc_label'] : '';
		$option_toc_heading_type = isset($options['toc_heading_type']) ? $options['toc_heading_type'] : '';
		$option_toc_excluded_headings = isset($options['toc_excluded_headings']) ? $options['toc_excluded_headings'] : '';
		
		echo'<h3 class="siteseo-tabs">Table of Contents</h3>
		<p class="description">A table of content works as an index section for your post or page. It helps search engines understand your page structure and users find specific sections quickly, which might help SEO, as it helps search engines better understand the structure of your content and also improves user experience.</p>';
		
		echo'<div class="siteseo_wrap_label">
			<p class="description">To use Table of Content on your pages, you can use this shortcode</p></div>
			
			<table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Enable TOC</th>
                    <td>
				        <input name="siteseo_options[enable_toc]" type="checkbox"' . (!empty($option_enable_toc) ? 'checked="yes"' : '') . ' value="1"/></label>
                    </td>
                </tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">TOC Label</th>
					<td>
						<input name="siteseo_options[toc_label]" placeholder="Table of content" type="text" value="'.$option_toc_label.'">
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Exclude Headings</th>
					<td>
						<label>
                            <input name="siteseo_options[exclude_h1]" type="checkbox"' . (!empty($temp) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('H1', 'siteseo') . 
                        '</label>
						&nbsp;
						<label>
							<input name="siteseo_options[exclude_h2]" type="checkbox" '. (!empty($temp) ? 'checked="yes"' : '') .' value="1" />'. esc_html__('H2','siteseo').
						'</label>
						
						&nbsp;
						
						<label>
							<input name="siteseo_options[exclude_h3]" type="checkbox" '. (!empty($temp) ? 'checked="yes"' : '') .' value="1" />'. esc_html__('H3','siteseo').
						'</label>
						
						&nbsp;
						<label>
							<input name="siteseo_options[exclude_h4]" type="checkbox" '. (!empty($temp) ? 'checked="yes"' : '') .' value="1" />'. esc_html__('H4','siteseo').
						'</label>
						
						&nbsp;
						<label>
							<input name="siteseo_options[exclude_h5]" type="checkbox" '. (!empty($temp) ? 'checked="yes"' : '') .' value="1" />'. esc_html__('H5','siteseo').
						'</label>
						
						&nbsp;
						<label>
							<input name="siteseo_options[exclude_h6]" type="checkbox" '. (!empty($temp) ? 'checked="yes"' : '') .' value="1" />'. esc_html__('H6','siteseo').
						'</label>	
					</td>
					
					<tr>
						<th scope="row" style="user-select:auto;">List Type</th>
						<td>
							<select name="siteseo_options[list_type]">
								<option value="Ordered List" '.selected($option_toc_heading_type, 'Ordered List', false).'>'.esc_html('Ordered List','siteseo').'</option>
								<option value="UnOrdered List" '.selected($option_toc_heading_type, 'UnOrdered List', false).'>'.esc_html('UnOrdered List','siteseo').'</option>
							</select>
						</td>
					</tr>
				</tr>
			</tbody>
		</table><label type="hidden" name="siteseo_options[toc_tab]" value="1"/>';
	}

	static function save_settings(){
		global $siteseo;
		
		check_admin_referer('siteseo_advance_settings');
		
		if(!current_user_can('manage_options') || !is_admin()){
			return;
		}
		
		$options = [];
		
		if(!empty($_post['siteseo_options'])){
			return;
		}
		
		if(isset($_POST['siteseo_options']['image_seo'])){

			$options['advanced_attachments'] = isset($_POST['siteseo_options']['attachment']);
			$options['adavnced_attachments_file'] = isset($_POST['siteseo_options']['attachment_file']);
			$options['advanced_clean_filename'] = isset($_POST['siteseo_options']['clean_filename']);
			$options['advanced_image_auto_title_editor'] = isset($_POST['siteseo_options']['auto_img_title']);
			$options['advanced_image_auto_alt_editor'] = isset($_POST['siteseo_options']['auto_img_alt']);
			$options['advanced_image_auto_alt_target_kw'] = isset($_POST['siteseo_options']['auto_target_keyword']);
			$options['advanced_image_auto_caption_editor'] = isset($_POST['siteseo_options']['caption_image']);
			$options['advanced_image_auto_desc_editor'] = isset($_POST['siteseo_options']['description_img']);

		}
		
		if(isset($_POST['siteseo_options']['advanced_tab'])){
			
			$options['advanced_tax_desc_editor'] = isset($_POST['siteseo_options']['taxonomy_desc']);
			$options['advanced_category_url'] = isset($_POST['siteseo_options']['category_url']);
			$options['advanced_noreferrer'] = isset($_POST['siteseo_options']['noreferrer_link']);
			$options['advanced_wp_generator'] = isset($_POST['siteseo_options']['wp_generator_meta']);
			$options['advanced_hentry'] = isset($_POST['siteseo_options']['hentry_post']);
			$options['advanced_comments_author_url'] = isset($_POST['siteseo_options']['comments_author_url']);
			$options['advanced_comments_website'] = isset($_POST['siteseo_options']['website_filed']);
			$options['advanced_comments_form_link'] = isset($_POST['siteseo_options']['comment_form_link']);
			$options['advanced_wp_shortlink'] = isset($_POST['siteseo_options']['shortlink']);
			$options['advanced_wp_rsd'] = isset($_POST['siteseo_options']['rsd_meta']);
			$options['advanced_wp_wlw'] = isset($_POST['siteseo_options']['wlw_meta']);
			$options['advanced_google'] = isset($_POST['siteseo_options']['google_meta_value']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['google_meta_value'])) : '';
			$options['advanced_bing'] = isset($_POST['siteseo_options']['bring_meta_value']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['bring_meta_value'])) : '';
			$options['advanced_pinterest'] = isset($_POST['siteseo_options']['pinterest_meta_value']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['pinterest_meta_value'])) : '';
			$options['advanced_yandex'] = isset($_POST['siteseo_options']['yandex_meta_value']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['yandex_meta_value'])) : '';
			
		}
		
		if(isset($_POST['siteseo_options']['toc_tab'])){
			
			$options['toc_enable'] = isset($options['siteseo_options']['enable_toc']);
			$options['toc_label'] = isset($options['siteseo_options']['toc_label']);
			$options['toc_heading_type'] = isset($options['siteseo_options']['list_type']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['list_type'])) : '';
		}
		
		 update_option('siteseo_advanced_option_name', $options);
	}

}