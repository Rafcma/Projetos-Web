<?php
/*
* SITESEO
* https://siteseo.io
* (c) SiteSEO Team
*/

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

function siteseo_admin_header(){
	
   SiteSEO\settings\Dashbord::admin_header();
}

function siteseo_submit_button($value =''){
	
	echo'<div class="siteseo-submit-button"><span class="line"></span><input type="submit" id="submit" name="submit" value="' . esc_attr($value ?: 'Save changes') . '" class="submit-button"></div>';
}

function siteseo_get_docs_link(){
    // handel for pro
	return 'hello';
}

function siteseo_suggestions_variable(){
	
	$title_variables['%%sep%%'] = 'Separator';
	$title_variables['%%sitetitle%%'] = 'Tagline';
	$title_variables['%%post_title%%'] = 'Post title';
	$title_variables['%%post_excerpt%%'] = 'Post excerpt';
	$title_variables['%%post_content%%'] = 'Post content / product description';
	$title_variables['%%post_thumbnail_url%%'] = 'Post thumbnail URL';
	$title_variables['%%post_url%%'] = 'Post url';
	$title_variables['%%post_date%%'] = 'Post date';
	$title_variables['%%post_modified_date%%'] = 'Post modified date';
	$title_variables['%%post_author%%'] = 'Post author';
	$title_variables['%%post_category%%'] = 'Post category';
	$title_variables['%%post_tag%%'] = 'Post_tag';
	$title_variables['%%_category_title%%'] = 'Category title';
	$title_variables['%%_category_description%%'] = 'Category description';
	$title_variables['%%tag_title%%'] = 'Tag title';
	$title_variables['%%tag_description%%'] = 'Tag description';
	$title_variables['%%term_title%%'] = 'Term title';
	$title_variables['%%tag_description%%'] = 'Tag description';
	$title_variables['%%term_title%%'] = 'Term title';
	$title_variables['%%term_description%%'] = 'Term description';
	$title_variables['%%search_keywords%%'] = 'Search keywords';
	$title_variables['%%current_pagination%%'] = 'Current number page';
	$title_variables['%%page%%'] = 'Page number with context';
	$title_variables['%%cpt_plural%%'] = 'Plural Post Type Archive name';
	$title_variables['%%archive_title%%'] = 'Archive_title';
	$title_variables['%%archive_date%%'] = 'Archive_date';
	$title_variables['%%archive_date_day%%'] = 'Day Archive date';
	$title_variables['%%archive_date_month%%'] = 'Month Archive title';
	$title_variables['%%archive_date_month_name%%'] = 'Month name Archive title';
	$title_variables['%%archive_date_year%%'] = 'Year Archive title';
	$title_variables['%%_cf_your_custom_field_name%%'] = 'Custom fields from post, page, post type and term taxonomy';
	$title_variables['%%_ct_your_custom_taxonomy_slug%%'] = 'Custom term taxonomy from post, page or post type';
	$title_variables['%%wc_single_cat%%'] = 'Single product category';
	$title_variables['%%wc_single_tag%%'] = 'Single product tag';
	$title_variables['%%wc_single_short_desc%%'] = 'Single product short description';
	$title_variables['%%wc_single_price%%'] = 'Single product price';
	$title_variables['%%wc_single_price_exe_tax'] = 'Single product price taxes excluded';
	$title_variables['%%wc_sku%%'] = 'Single SKU Product';
	$title_variables['%%currentday%%'] = 'Current day';
	$title_variables['%%currentmonth%%'] = 'Current month';
	$title_variables['%%currentmonth_short%%'] = 'Current month in 3 letter';
	$title_variables['%%currentyear%%'] = 'Current year';
	$title_variables['%%currentdate%%'] = 'Current date';
	$title_variables['%%currenttime%%'] = 'Current time';
	$title_variables['%%author_first_name%%'] = 'Author first name';
	$title_variables['%%author_last_name%%'] = 'Author last name';
	$title_variables['%%author_website%%'] = 'Author website';
	$title_variables['%%author_nickname%%'] = 'Author nickname';
	$title_variables['%%author_bio%%'] = 'Author biography';
	$title_variables['%%_ucf_your_user_meta%%'] = 'Custom User Meta';
	$title_variables['%%currentmonth_num%%'] = 'Current month in digital format';
	$title_variables['%%target_keyword%%'] = 'Target keywords';
	
	return $title_variables;
}

function siteseo_suggestion_button(){

	$suggest_variable = siteseo_suggestions_variable();

	echo '<button class="tag-select-btn"><span id="icon" class="dashicons dashicons-arrow-down-alt2"></span></button>';
    
    if(!empty($suggest_variable)){
		echo '<div class="siteseo-suggetion">
			<div class="search-box-container">
				<input type="text" class="search-box" placeholder="Search a tag...">
			</div>
			<div class="suggestions-container">';
			foreach($suggest_variable as $key =>$value){
				echo '<div class="section">'.$value.'
					<div class="item">
						<div class="tag">'.$key.'</div>
					</div>
				</div>';
			}
		echo '</div>
		</div>';
	}
}

