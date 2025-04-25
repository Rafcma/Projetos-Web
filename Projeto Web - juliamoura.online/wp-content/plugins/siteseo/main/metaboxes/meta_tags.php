<?php
/*
* SITESEO
* https://siteseo.io
* (c) SiteSEO Team
*/

namespace SiteSEO\metaboxes;

if(!defined('ABSPATH')){
	die('Hacking Attempt !');
}

class meta_tags{
	
	static function render_metabox(){

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_content_analysis'; // Default tab

		$siteseo_metabox_tabs =[
			'tab_content_analysis' => esc_html__('Content Analysis', 'siteseo'),
			'tab_title' => esc_html__('Title','siteseo'),
			'tab_social' => esc_html__('Social','siteseo'),
			'tab_advanced' => esc_html__('Advanced','siteseo'),
			'tab_redirect' => esc_html__('Redirects', 'siteseo'),
		];

        echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">
		<div id="siteseo-tabs" class="wrap">
			<div class="nav-tab-wrapper">';

        foreach($siteseo_metabox_tabs as $tab_key => $tab_caption){
			
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
        }
		
		echo'</div> 
		<div class="tab-content-wrapper">
        <div class="siteseo-tab' . ($current_tab === 'tab_content_analysis' ? ' active' : '') . '" id="tab_content_analysis">';
        self::content_analysis();
        echo'</div>     
        <div class="siteseo-tab' . ($current_tab === 'tab_title' ? ' active' : '') . '" id="tab_title">';
        self::title();
        echo'</div>     
        <div class="siteseo-tab' . ($current_tab === 'tab_social' ? ' active' : '') . '" id="tab_social">';
        self::social();
        echo'</div>     
        <div class="siteseo-tab' . ($current_tab === 'tab_advanced' ? ' active' : '') . '" id="tab_advanced">';
        self::advanced();
        echo'</div>     
        <div class="siteseo-tab' . ($current_tab === 'tab_redirect' ? ' active' : '') . '" id="tab_redirect">';
        self::redirect();
        echo'</div>     
        </div>
	</form>';
	
	} 
	
	static function redirect(){
		global $siteseo;
				
		echo'<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" style="user-select:auto;">Enable redirection</th>
						<td>
							<input type="checkbox"  value="" name="siteseo_options[]">
						</td>
					</tr>
					
					<hr/>
					
					<tr>
						<th scope="row" style="user-select:auto;">Login status</th>
						<td>
							<input type="text" value="" name="siteseo_options[]">
						</td>
					</tr>
					
					<tr>
						<th scope="row" style="user-select:auto;">Redirection Type</th>
						<td>
							<input type="text" value="" name="siteseo_options[]">
						</td>
					</tr>
					
					<tr>
						<th scope="row" style="user-select:auto;">Redirection URL</th>
						<td>
							<input type="text" value="" name="siteseo_options[]">
						</td>
					</tr>

				</tbody>
			</table>';	
	}
	
	static function advanced(){
		global $siteseo;
		
		echo'<table class="form-table">
				<tbody>
					<tr>
						<th scope="row" style="user-select:auto;">Meta Robots Settings
							<p class="description">You cannot uncheck a checkbox? This is normal, and it most likely defined</p>
						</th>
						<td>
							
						</td>
					</tr>
					
					<hr/>
					
					<tr>
						<th scope="row" style="user-select:auto;">Canonical URL</th>
						<td>
							<input type="text" value="" placeholder="" name="siteseo_options[]">
						</td>
					</tr>
					
					<hr/>
					
					<tr>
						<th scope="row" style="user-select:auto;"></th>
						<td>
							<select>
							</select>
						</td>
					</tr>
				
				</tbody>
			</table>';
	}
	
	static function social(){
		
		$image_prev = esc_url(SITESEO_ASSETS_DIR).'/img/social-placeholder.png';
		
		echo'<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select:auto;">Preview</th>
					<td>
						<img src="'.$image_prev.'" alt="Facebook image prev" style="border-radius:10px;" width="500" height="600">
					</td>
				</tr>
				<tr>
					<th scope="row" style="user-select:auto;">Facebook Title</th>
					<td>
						<input type="text" value="" name="siteseo_options[]">
					</td>
				</tr>
				
				<hr/>
				
				<tr>
					<th scope="row" style="user-select:auto;"></th>
					<td>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">Facebook description</th>
					<td>
						<input type="text" value="" name="siteseo_options[]">
					</td>
				</tr>
				
				<hr/>
				
				<tr>
					<th scope="row" style="user-select:auto;">Facebook Thumbnail</th>
					<td>
						<input type="text" placeholder="Enter the URL of image you want to be show as the Facebook image" name="siteseo_options[]" value="" >
						<p class="description">Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens).</p>
					</td>
				</tr>
			</tbody>
		</table>';
		
	}
	
	static function title(){
		global $siteseo;
		
		echo'<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select:auto;">Title</th>
					<td>
						<input type="text" value="" name="siteseo_options[]">
					</td>
				</tr>
				
				<hr/>
				
				<tr>
					<th scope="row" style="user-select:auto;">Meta Description</th>
					<td>
						<input type="text name="siteseo_options[]" value=""> 
					</td>
				</tr>
			</tbody>
		</table>';
		
	}
	
	static function content_analysis(){
		global $siteseo;
		
		echo'<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select:auto;">Focus Keywords</th>
					<td>
						<input type="text" name="siteseo_options[]" value="">
					</td>
				</tr>
			</tbody>
		</table>';
		
	}
	
}