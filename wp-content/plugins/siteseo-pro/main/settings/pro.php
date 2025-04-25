<?php
/*
* SITESEO
* https://siteseo.io
* (c) SITSEO Team
*/

namespace SiteSEOPro\Settings;

// Are we being accessed directly ?
if(!defined('ABSPATH')){
	die('Hacking Attempt !');
}

use \SiteSEOPro\Settings\PageSpeed;

class Pro{

	// Display page
	static function home(){
		global $siteseo;

		if(function_exists('siteseo_admin_header')){
			siteseo_admin_header();
		}

		$current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_siteseopro_woocommerce'; // Default tab

		$siteseopro_settings_tabs = [
			'tab_siteseopro_woocommerce' => esc_html__('WooCommerce', 'siteseo'),
			'tab_siteseopro_easydigital_downloads' => esc_html__('Easy Digital Downloads', 'siteseo'),
			'tab_siteseopro_pagespeed_insights' => esc_html__('PageSpeed Insights', 'siteseo'),
			'tab_siteseopro_dublin_core' => esc_html__('Dublin Core', 'siteseo'),
			'tab_siteseopro_local_business' => esc_html__('Local Business', 'siteseo'),
			'tab_siteseopro_structured_data' => esc_html__('Structured Data Types', 'siteseo')
		];

		echo '<form method="post" style="margin-right:20px;" class="siteseo-option" name="siteseo-flush">';

		$sitesepro_license = get_option('siteseo_license');
		// TODO:: Will make it visible later.
		// if(empty($sitesepro_license['license'])){
			// echo'<div class="siteseopro_license_notices">';
				// $docs = siteseo_get_docs_links();

				// echo'<div class="siteseo-notice is-success">
				// <p><strong>' . __('Welcome to SiteSEO PRO!', 'siteseo-pro') . '</strong></p>
				// <p>' . __('Please activate your license to receive automatic updates and get premium support.', 'siteseo-pro') . '</p>
				// <p><a class="button button-primary" href="' . esc_url(admin_url('admin.php?page=siteseo-license')) . '">' . __('Activate License', 'siteseo-pro') . '</a></p>
				// </div>
				
			// </div>';
		// }

		echo'<h2 style="padding-left:3.5%;">SiteSEO - PRO</h2>';
		wp_nonce_field('sitseo_pro_settings');

		echo '<div id="siteseo-tabs" class="wrap">
		<div class="nav-tab-wrapper">';

		foreach ($siteseopro_settings_tabs as $tab_key => $tab_caption) {
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" href="?page=siteseo-pro-page#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}

		echo '</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseopro_woocommerce' ? ' active' : '') . '" id="tab_siteseopro_woocommerce">';
		self::woocommerce_tab();
		echo '</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseopro_easydigital_downloads' ? ' active' : '') . '" id="tab_siteseopro_easydigital_downloads">';
		self::easy_digital_downloads_tab();
		echo '</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseopro_pagespeed_insights' ? ' active' : '') . '" id="tab_siteseopro_pagespeed_insights">';
		self::pagespeed_insights_tab();
		echo '</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseopro_dublin_core' ? ' active' : '') . '" id="tab_siteseopro_dublin_core">';
		self::dublin_core_tab();
		echo '</div>
		<div class="siteseo-tab' . ($current_tab == 'tab_siteseopro_local_business' ? ' active' : '') . '" id="tab_siteseopro_local_business">';
		self::local_business_tab();
		echo '</div>
		<div class="siteseo-tab' .($current_tab == 'tab_siteseopro_structured_data' ? ' active' : ''). '" id="tab_siteseopro_structured_data">';
		self::structured_data();
		echo'</div>
		</div>';

		siteseo_submit_button(__('Save changes', 'siteseo'));
		echo '</form>';

	}

	static function woocommerce_tab(){
		global $siteseo;

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		$options = $siteseo->pro;

		// Check if settings are enable
		$cart_page = !empty($options['woocommerce_cart_page_no_index']);
		$checkout_page = !empty($options['woocommerce_checkout_page_no_index']);
		$account_page = !empty($options['woocommerce_customer_account_page_no_index']);
		$woo_og_price = !empty($options['woocommerce_product_og_price']);
		$woo_og_currency = !empty($options['woocommerce_product_og_currency']);
		$woo_meta_generator = !empty($options['woocommerce_meta_generator']);
		$schema_output = !empty($options['woocommerce_schema_output']);
		$schema_breadcrumbs = !empty($options['woocommerce_schema_breadcrumbs_output']);
		$toggle_state_woocommerce = !empty($options['toggle_state_woocommerce']) ? $options['toggle_state_woocommerce'] : '';

		echo '<h2>WooCommerce</h2>
		<div class="siteseo-toggle-container">
	    	<div class="siteseo-toggle-switch ' . ($toggle_state_woocommerce ? 'active' : '') . '" id="siteseo-toggleSwitch-woocommerce"></div>
		<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
	    	<p class="siteseo-toggle-label" id="siteseo-toggle-label-woocommerce">' . ($toggle_state_woocommerce ? 'Click to disable this feature' : 'Click to enable this feature') . '</p>
	    	<input type="hidden" name="siteseo_pro_options[toggle_state_woocommerce]" id="toggle_state_woocommerce" value="' . esc_attr($toggle_state_woocommerce) . '">
	    	</div>
		<p>'.esc_html__('Enhance WooCommerce SEO by enabling this feature, which automatically adds Product Identifier Type and Product Identifier Value fields to the WooCommerce product metabox (e.g., barcode). These fields are essential for enriching the Product schema, helping search engines better understand and display your products', 'siteseo-pro').'</p>';
	  
		if(!is_plugin_active('woocommerce/woocommerce.php')){
			echo'<div class="siteseo-notice is-warning"><p>'.__('You need to enable <strong>WooCommerce</strong> to apply these settings.', 'siteseo-pro').'</p></div>';
		}

		echo'<table class="form-table"><tbody>
			<tr><th scope="row">Cart page</th>
			<td><label for="siteseo_woocommerce_cart_page_no_index">
				<input id="siteseo_woocommerce_cart_page_no_index" name="siteseo_pro_options[woocommerce_cart_page_no_index]" type="checkbox"' . 
			  (!empty($cart_page) ? 'checked="yes"' : '') . 
			  ' value="1"/>' . 
			  esc_html__('noindex', 'siteseo-pro') . 
			'</label>
			<p class="description">' . esc_html__('If your theme or plugin displays the cart across your entire WordPress site, don\'t enable this option.', 'siteseo-pro') . '</p></td></tr>
		  
			<tr><th scope="row">Checkout page</th>
			<td><label for="siteseo_woocommerce_checkout_page_no_index">
				<input id="siteseo_woocommerce_checkout_page_no_index" name="siteseo_pro_options[woocommerce_checkout_page_no_index]" type="checkbox"' . (!empty($checkout_page) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('noindex', 'siteseo-pro') . 
			'</label></td></tr>
		  
			<tr><th scope="row">Customer account pages</th>
			<td><label for="siteseo_woocommerce_customer_account_page_no_index">
				<input id="siteseo_woocommerce_customer_account_page_no_index" name="siteseo_pro_options[woocommerce_customer_account_page_no_index]" type="checkbox"'.(!empty($account_page) ? 'checked="yes"' : '').' value="1"/>'.esc_html__('noindex', 'siteseo-pro') . 
			'</label></td></tr>
	   
			<tr><th scope="row">OG Price</th>
			<td><label for="siteseo_woocommerce_product_og_price">
				<input id="siteseo_woocommerce_product_og_price" name="siteseo_pro_options[woocommerce_product_og_price]" type="checkbox"'.(!empty($woo_og_price) ? 'checked="yes"' : '').' value="1"/>' .esc_html__('Add product:price:amount meta for product', 'siteseo-pro') . 
			'</label>

			<div class="siteseo-styles pre"><pre>' . esc_html('<meta property="product:price:amount" content="99" />') . '</pre></div></td></tr>
	 
			<tr><th scope="row">OG Currency</th>
			<td><label for="siteseo_woocommerce_product_og_currency">
				<input id="siteseo_woocommerce_product_og_currency" name="siteseo_pro_options[woocommerce_product_og_currency]" type="checkbox"'.(!empty($woo_og_currency) ? 'checked="yes"' : '').' value="1"/>'.esc_html__('Add product:price:currency meta for product', 'siteseo-pro').
			'</label>
			<div class="siteseo-styles pre"><pre>' . esc_html('<meta property="product:price:currency" content="EUR" />') .'</pre></div></td></tr>

			<tr><th scope="row">Remove WooCommerce generator tag in your head</th>
			<td><label for="siteseo_woocommerce_meta_generator">
				<input id="siteseo_woocommerce_meta_generator" name="siteseo_pro_options[woocommerce_meta_generator]" type="checkbox"'.(!empty($woo_meta_generator) ? 'checked="yes"' : '').' value="1"/>'.esc_html__('Remove WooCommerce meta generator', 'siteseo-pro').
			'</label>
			<div class="siteseo-styles pre"><pre>' . esc_html('<meta name="generator" content="WooCommerce 7.5" />') . '</pre></div></td>

			<tr><th scope="row">Remove WooCommerce Schemas</th>
			<td><label for="siteseo_woocommerce_schema_output">
				<input id="siteseo_woocommerce_schema_output" name="siteseo_pro_options[woocommerce_schema_output]" type="checkbox"'.(!empty($schema_output) ? 'checked="yes"' : '').' value="1"/>'.esc_html__('Remove default JSON-LD structured data (WooCommerce 3+)', 'siteseo-pro') . 
			'</label>
			<p class="description">' . 
				sprintf(__('The default product schema added by WooCommerce generates errors in Google Search Console. Disable it and create your own <a href="%s">automatic product schema</a>.', 'siteseo-pro'), esc_url(admin_url('edit.php?post_type=siteseo_schemas'))) . 
			'</p></td></tr>

			<tr><th scope="row">Remove WooCommerce breadcrumbs schemas only</th>
			<td><label for="siteseo_woocommerce_schema_breadcrumbs_output">
				<input id="siteseo_woocommerce_schema_breadcrumbs_output" name="siteseo_pro_options[woocommerce_schema_breadcrumbs_output]" type="checkbox"'.(!empty($schema_breadcrumbs) ? 'checked="yes"' : '').' value="1"/>' . esc_html__('Remove default breadcrumbs JSON-LD structured data (WooCommerce 3+)', 'siteseo-pro').
			'</label><p class="description">' . esc_html__('If "Remove default JSON-LD structured data (WooCommerce 3+)" option is already checked, the breadcrumbs schema is already removed from your source code.', 'siteseo-pro') . 
			'</p></td></tr>
		</tbody></table>
		<input type="hidden" name="woocommerce_settings" value="1"/>';

	}

	static function easy_digital_downloads_tab() {

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		$options = get_option('siteseo_pro_options');

		// check settings enable
		$option_og_price = isset($options['edd_product_og_price']) ? $options['edd_product_og_price'] : '';
		$option_og_currency = isset($options['edd_product_og_currency']) ? $options['edd_product_og_currency'] : '';
		$option_meta_generator = isset($options['edd_meta_generator']) ? $options['edd_meta_generator'] : '';
		$toggle_state_easy_digital = isset($options['toggle_state_easy_digital']) ? $options['toggle_state_easy_digital'] : '';

		echo '<h2>Easy Digital Downloads</h2>
		<div class="siteseo-toggle-container">
		<div class="siteseo-toggle-switch ' . ($toggle_state_easy_digital ? 'active' : '') . '" id="siteseo-toggleSwitch-edd"></div>
		<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
		<p class="siteseo-toggle-label" id="siteseo-toggle-label-edd">' . (!empty($toggle_state_easy_digital) ? esc_html__('Click to disable this feature', 'siteseo-pro') : esc_html__('Click to enable this feature', 'siteseo-pro')) . '</p>
		<input type="hidden" name="siteseo_pro_options[toggle_state_easy_digital]" id="toggle_state_easy_digital" value="' . esc_attr($toggle_state_easy_digital) . '">
		</div>
		<p>'.esc_html__('Improve Easy Digital Downloads SEO', 'siteseo-pro').'</p>';

		if(!is_plugin_active('easy-digital-downloads/easy-digital-downloads.php')){
			echo '<div class="siteseo-notice is-warning"><p>';
			_e('You need to enable <strong>Easy Digital Downloads</strong> to apply these settings.', 'siteseo-pro');
			echo '</p></div>';
		}

		echo'<table class="form-table">
			<tbody>
				<tr><th scope="row">OG price</th>
					<td><label for="siteseo_edd_product_og_price">
						<input id="siteseo_edd_product_og_price" name="siteseo_pro_options[edd_product_og_price]" type="checkbox" ' . (empty(!$option_og_price) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Add product:price:amount meta for product', 'siteseo-pro') .
						 '</label>' .
						'<div class="siteseo-styles pre"><pre>' . esc_html('<meta property="product:price:amount" content="99" />') . '</pre></div>
					</td>
				</tr>' .
				'<tr><th scope="row">OG Currency</th>' .
					'<td><label for="siteseo_edd_product_og_currency">' .
						'<input id="siteseo_edd_product_og_currency" name="siteseo_pro_options[edd_product_og_currency]" type="checkbox"'. (!empty($option_og_currency) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Add product:price:currency meta for product', 'siteseo-pro') . '</label>' .
						'<div class="siteseo-styles pre"><pre>' . esc_html('<meta property="product:price:currency" content="EUR" />') . '</pre></div>
					</td>
				</tr>' .
				'<tr>
					<th scope="row">'.esc_html__('Remove Easy Digital Downloads generator tag in your head', 'siteseo-pro').'</th>' .
					'<td><label for="siteseo_edd_meta_generator">' .
						'<input id="siteseo_edd_meta_generator" name="siteseo_pro_options[edd_meta_generator]" type="checkbox" ' . (!empty($option_meta_generator) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Remove EDD meta generator', 'siteseo-pro') .
						'</label>' .
					 '<div class="siteseo-styles pre"><pre>' . esc_html('<meta name="generator" content="Easy Digital Downloads v3.0" />') . '</pre></div>
					</td>
				</tr>
			</tbody>
		</table>

		<input type="hidden" name="digital_download_settings" value="1"/>';

	}

	static function pagespeed_insights_tab(){

		global $siteseo;

		if(!empty($_POST['submit'])){
			self::save_settings();	
		}

		// check settings enable
		$check_api_key = !empty($siteseo->pro['ps_api_key']) ? $siteseo->pro['ps_api_key'] : '';
		$docs = function_exists('siteseo_get_docs_links') ? siteseo_get_docs_links() : '';

		echo '<h2>PageSpeed Insights</h2>
			<p>'.esc_html__('Check your site performance with Google PageSpeed Insights.', 'siteseo-pro').'</p>
			<p>'.esc_html__('Learn how your site has performed, based on data from your actual users around the world.', 'siteseo-pro').'</p>
		 <table class="form-table">
			<tbody>' .
				'<tr><th scope="row">'.esc_html__('Enter your own Google Page Speed API key', 'siteseo-pro').'</th>';

		echo sprintf(
			'<td><input id="siteseo_ps_api_key" type="text" name="siteseo_pro_options[ps_api_key]" aria-label="%s" placeholder="%s" value="%s">',
			esc_html__('Google Page Speed Insights API key', 'siteseo-pro'),
			esc_html__('Enter your Page Speed Insights API key', 'siteseo-pro'),
			esc_html($check_api_key)
		);

		echo '<p class="siteseo-help description">
			<span class="dashicons dashicons-external"></span>
			<a href="' . esc_url($docs['page_speed']['api']) . '" target="_blank">' . esc_html__('Learn how to create a free Google Page Speed API key', 'siteseo-pro') . '</a>
		  </p>
		  <p class="siteseo-help description">
			<span class="dashicons dashicons-external"></span>
			<a href="' . esc_url($docs['page_speed']['google']) . '" target="_blank">' . esc_html__('A Page Speed Insights key is required to avoid quota errors.', 'siteseo-pro') . '</a>
		  </p></tr>';
		  
		echo'</th></tbody></table>';

		echo '<br/><div class="siteseo-pagespeed-input-wrapper">
			<input id="siteseo_ps_url" type="text" name="siteseo_pro_options[siteseo_ps_url]" placeholder="' . esc_attr__('Enter a URL to analyse with Page Speed Insights', 'siteseo-pro') . '" value="' . esc_html(get_home_url()) . '">
			<button type="button" id="siteseopro-pagespeed-btn" class="siteseo-request-page-speed btn btnPrimary">' .
			esc_html__('Analyse performance', 'siteseo-pro') . '</button><div style="position: absolute;left:85%;margin-top:1%;" class="spinner"></div>
			<button type="button" id="siteseopro-clear-Page-speed-insights" class="btn btnTertiary">' .
			esc_html__('Remove last analysis', 'siteseo-pro') . '</button>
		  </div>';

		echo'<div id="siteseopro-pagespeed-results">';
		$page_speed = get_option('siteseo_pro_page_speed', []); 

		if(!empty($page_speed['mobile']) && !empty($page_speed['desktop'])){
			PageSpeed::analysis();
		}

		echo'</div>
		<input type="hidden" name="pagespeed_settings" value="1"/>';
	}

	static function dublin_core_tab(){
		global $siteseo;

		if(!empty($_POST['submit'])){
			self::save_settings();
		}

		$dublin_core = !empty($siteseo->pro['dublin_core_enable']);
		$toggle_state_dublin_core = !empty($siteseo->pro['toggle_state_dublin_core']) ? $siteseo->pro['toggle_state_dublin_core'] : '';

		echo '<h2>Dublin Core</h2><div class="siteseo-toggle-container">
		<div class="siteseo-toggle-switch ' . ($toggle_state_dublin_core ? 'active'  : '') . '" id="siteseo-toggleSwitch-dublin"></div>
		<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
		<p class="siteseo-toggle-label" id="siteseo-toggle-label-dublin">' . (!empty($toggle_state_dublin_core) ? 'Click to disable this feature' : 'Click to enable this feature') . '</p>
		<input type="hidden" name="siteseo_pro_options[toggle_state_dublin_core]" id="toggle_state_dublin_core" value="' . esc_attr($toggle_state_dublin_core) . '">
		</div>' .
		 '<br/>' . esc_html__('Dublin Core is a set of meta tags to describe your content','siteseo-pro') .
		 '<br/>' . esc_html__('These tags are automatically generated. Recognized by states / governments, they are used by directories, Bing, Baidu and Yandex.','siteseo-pro') .
		 '<table class="form-table">
			<tbody>
				<tr>' .
					'<th scope="row" style="user-select: auto;">'.esc_html__('Enable Dublin Core', 'siteseo-pro').'</th>'.
					'<td><label for="siteseo_dublin_core_enable">'.
						'<input id="siteseo_dublin_core_enable" name="siteseo_pro_options[dublin_core_enable]" type="checkbox" ' .
							(!empty($dublin_core) ? 'checked="yes"' : '') . ' value="1"/>' . esc_html__('Enable Dublin Core meta tags (dc.title, dc.description, dc.source, dc.language, dc.relation, dc.subject)', 'siteseo-pro') .
						'</label>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="dublin_code_settings" value="1"/>';
	}
	
	static function local_business_tab(){
		global $siteseo;
		
		if(!empty($_POST['submit'])){
			self::save_settings();
		}
		
		// Time slots
		$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
	
		$hours = range('00', '23');
		$mins = ['00', '15', '30', '45', '59'];
		
		if(isset($_POST['siteseo_pro_options']) && is_array($_POST['siteseo_pro_options'])){
			$siteseo_pro_options = map_deep(wp_unslash($_POST['siteseo_pro_options']), 'sanitize_text_field');
		}
		
		$business_type['LocalBusiness'] = 'Local Business (default)';
		$business_type['AnimalShelter'] = 'Animal Shelter';
		$business_type['ChildCare'] = 'Child Care';
		$business_type['DryCleaningOrLaundry'] = 'Dry Cleaning Or Laundry';
		$business_type['EmergencyService'] = 'Emergency Service';
		$business_type['FireStation'] = '|-FireStation';
		$business_type['Hospital'] = '|-Hospital';
		$business_type['PoliceStation'] = '|-Police Station';
		$business_type['EmploymentAgency'] = 'Employment Agency';
		$business_type['Entertainment Business'] = 'Entertainment Business';
		$business_type['AdultEntertainment'] = '|-AdultEntertainment';
		$business_type['AmusementPark'] = '|-Amusement Park';
		$business_type['ArtGallery'] = '|-Art Gallery';
		$business_type['Casino'] = '|-Casino';
		$business_type['ComedyClub'] = '|-Comedy Club';
		$business_type['MovieTheater'] = '|-Movie Theater';
		$business_type['NightClub'] = '|-Night Club';
		$business_type['FinancialService'] = '|-Financial Service';
		$business_type['AccountingService'] = '|-Accounting Service';
		$business_type['AutomatedTeller'] = '|-Automated Teller';
		$business_type['BankOrCreditUnion'] = '|-Bank Or CreditUnion';
		$business_type['InsuranceAgency'] = '|-Insurance Agency';
		$business_type['FoodEstablishment'] = 'Food Establishment';
		$business_type['Bakery'] = '|-Bakery';
		$business_type['BarOrPub'] = '|-Bar Or Pub';
		$business_type['Brewery'] = 'Brewery';
		$business_type['CafeOrCoffeeShop'] = '|-Cafe Or CoffeeShop';
		$business_type['FastFoodRestaurant'] = '|-Fast Food Restaurant';
		$business_type['IceCreamShop'] = '|-Ice Cream Shop';
		$business_type['Restaurant'] = '|-Restaurant';
		$business_type['Winery'] = '|-Winery';
		$business_type['GovernmentOffice'] = 'Government Office';
		$business_type['PostOffice'] = '|-PostOffice';
		$business_type['HealthAndBeautyBusiness'] = 'Health And Beauty Business';
		$business_type['BeautySalon'] = '|-Beauty Salon';
		$business_type['DaySpa'] = '|-DaySpa';
		$business_type['HairSalon'] = '|-Hair Salon';
		$business_type['HealthClub'] = '|-Health Club';
		$business_type['NailSalon'] = '|-Nail Salon';
		$business_type['TattooParlor'] = '|-Tattoo Parlor';
		$business_type['HomeAndConstructionBusiness'] = '|-Home And Construction Business';
		$business_type['Electrician'] = '|-Electrician';
		$business_type['HVACBusiness'] = '|-HVAC Business';
		$business_type['HousePainter'] = '|-House Painter';
		$business_type['Locksmith'] = '|-Locksmith';
		$business_type['MovingCompany'] = '|-MovingCompany';
		$business_type['Plumber'] = '|-Plumber';
		$business_type['RoofingContractor'] = '|-Roofing Contractor';
		$business_type['InternetCafe'] = '|-Internet Cafe';
		$business_type['MedicalBusiness'] = '|-Medical Business';
		$business_type['CommunityHealth'] = '|-Community Health';
		$business_type['Dentist'] = '|-Dentist';
		$business_type['Dermatology'] = '|-Dermatology';
		$business_type['DietNutrition'] = '|-Diet Nutrition';
		$business_type['Emergency'] = '|-Emergency';
		$business_type['Gynecologic'] = '|-Gynecologic';
		$business_type['MedicalClinic'] = '|-MedicalClinic';
		$business_type['Midwifery'] = '|-Midwifery';
		$business_type['Nursing'] = '|-Nursing';
		$business_type['Obstetric'] = '|-Obstetric';
		$business_type['Oncologic'] = '|-Oncologic';
		$business_type['Optician'] = '|-Optician';
		$business_type['Otolaryngologic'] = '|-Otolaryngologic';
		$business_type['Pediatric'] = '|-Pediatric';
		$business_type['Pharmacy'] = '|-Pharmacy';
		$business_type['Physiotherapy'] = '|-Physiotherapy';
		$business_type['PlasticSurgery'] = '|-PlasticSurgery';
		$business_type['Podiatric'] = '|-Podiatric';	
		$business_type['PrimaryCare'] = '|-PrimaryCare';
		$business_type['Psychiatric'] = '|-Psychiatric';
		$business_type['PublicHealth'] = '|-PublicHealth';
		$business_type['VeterinaryCare'] = '|-VeterinaryCare';
		$business_type['LegalService'] = '|-LegalService';	
		$business_type['Attorney'] = '|-Attorney';	
		$business_type['Notary'] = '|-Notary';
		$business_type['Library'] = 'Library';	
		$business_type['LodgingBusiness'] = 'LodgingBusiness';
		$business_type['BedAndBreakfast'] = '|-Bed And Breakfast';
		$business_type['Campground'] = '|-Campground';
		$business_type['Hostel'] = '|-Hostel';
		$business_type['Hotel'] = '|-Hotel';
		$business_type['Motel'] = '|-Motel';
		$business_type['Resort'] ='|-Resort';
		$business_type['ProfessionalService'] ='Professional Service';
		$business_type['RadioStation'] ='Radio Station';
		$business_type['RealEstateAgent'] ='Real Estate Agent';
		$business_type['RecyclingCenter'] ='Recycling Center';
		$business_type['SelfStorage'] ='Real Self Storage';
		$business_type['ShoppingCenter'] ='ShoppingCenter';
		$business_type['SportsActivityLocation'] ='Sports Activity Location';	
		$business_type['BowlingAlley'] ='|-Bowling Alley';
		$business_type['ExerciseGym'] = '|-Exercise Gym';
		$business_type['GolfCourse'] = '|-Golf Course';
		$business_type['HealthClub'] = '|-HealthClub';
		$business_type['PublicSwimmingPool'] = '|-Public Swimming Pool';
		$business_type['SkiResort'] = '|-Ski Resort';
		$business_type['SportsClub'] = '|-Sports Club';
		$business_type['StadiumOrArena'] = '|-Stadium Or Arena';
		$business_type['TennisComplex'] = '|-Tennis Complex';
		$business_type['Store'] = '|-Store';
		$business_type['AutoPartsStore'] = '|-Auto Parts Store';
		$business_type['BikeStore'] = '|-Bike Store';
		$business_type['BookStore'] = '|-Book Store';
		$business_type['ClothingStore'] = '|-Clothing Store';
		$business_type['ComputerStore'] = '|-Computer Store';
		$business_type['ConvenienceStore'] = '|-Convenience Store';
		$business_type['DepartmentStore'] = '|-Department Store';
		$business_type['ElectronicsStore'] = '|-Electronics Store';
		$business_type['Florist'] = '|-Florist';
		$business_type['FurnitureStore'] = '|-Furniture Store';
		$business_type['GardenStore'] = '|-Garden Store';
		$business_type['GroceryStore'] = '|-Grocery Store';
		$business_type['HardwareStore'] = '|-Hardware Store';
		$business_type['HobbyShop'] = '|-Hobby Shop';
		$business_type['HomeGoodsStore'] = '|-Home Goods Store';
		$business_type['JewelryStore'] = '|-Jewelry Store';
		$business_type['LiquorStore'] = '|-Liquor Store';
		$business_type['MensClothingStore'] = '|-Mens Clothing Store';
		$business_type['MobilePhoneStore'] = '|-Mobile Phone Store';
		$business_type['MovieRentalStore'] = '|-Movie Rental Store';
		$business_type['MusicStore'] = '|-Music Store';
		$business_type['OfficeEquipmentStore'] = '|-Office Equipment Store';
		$business_type['OutletStore'] = '|-Outlet Store';
		$business_type['PawnShop'] = '|-Pawn Shop';
		$business_type['PetStore'] = '|-PetStore';
		$business_type['ShoeStore'] = '|-Shoe Store';
		$business_type['SportingGoodsStore'] = '|-Sporting Goods Store';
		$business_type['TireShop'] = '|-Tire Shop';
		$business_type['ToyStore'] = '|-Toy Store';
		$business_type['WholesaleStore'] = '|-Whole sale Store';
		$business_type['TelevisionStation'] = '|-Whole sale Store';
		$business_type['TouristInformationCenter'] = 'Tourist Information Center';
		$business_type['TravelAgency'] = 'Travel Agency';
		$business_type['AutomotiveBusiness'] = 'Automotive Business';
		$business_type['AutoBodyShop'] = '|-Auto Body Shop';
		$business_type['AutoDealer'] = '|-Auto Dealer';
		$business_type['AutoPartsStore'] = '|-Auto Parts Store';
		$business_type['AutoRental'] = '|-Auto Rental';
		$business_type['AutoRepair'] = '|-Auto Repair';
		$business_type['AutoWash'] = '|-AutoWash';
		$business_type['GasStation'] = '|-Gas Station';
		$business_type['MotorcycleDealer'] = '|-Motorcycle Dealer';
		$business_type['MotorcycleRepair'] = '|-MotorcycleRepair';
		
		// Get saved settings
		$options = $siteseo->pro;
		
		$display_schema = isset($options['local_business_display_schema']) ? esc_attr($options['local_business_display_schema']) : '';
		$set_street_address = isset($options['street_address']) ? esc_attr($options['street_address']): '';
		$set_city = isset($options['city']) ? esc_attr($options['city']): ''; 
		$set_state = isset($options['state']) ? esc_attr($options['state']): ''; 
		$set_postal_code = isset($options['postal_code']) ? esc_attr($options['postal_code']) : '' ; 
		$set_country = isset($options['country']) ? esc_attr($options['country']): '';
		$set_latitude = isset($options['latitude'])? esc_attr($options['latitude']): ''; 
		$set_longitude = isset($options['longitude']) ? esc_attr($options['longitude']) : '';
		$set_place_id = isset($options['place_id']) ? esc_attr($options['place_id']) : '';
		$set_url = isset($options['url']) ? esc_attr($options['url']) : '';
		$set_telephone = isset($options['telephone']) ? esc_attr($options['telephone']): '';
		$set_price_range = isset($options['price_range']) ? esc_attr($options['price_range']) : '';
		$set_cuisine_served = isset($options['cuisine_served'])? esc_attr($options['cuisine_served']) : '';
		$set_accepts_reser = isset($options['accepts_reser']) ? esc_attr($options['accepts_reser']) : '';
		$set_business_type = isset($options['business_type']) ? esc_attr($options['business_type']) : 'LocalBusiness';
		$toggle_state_local_buz = isset($options['toggle_state_local_buz']) ? esc_html($options['toggle_state_local_buz']): '';
		
		
		$docs = siteseo_get_docs_links();
		// Display the settings form
		echo '<h2>'.esc_html__('Local Business', 'siteseo-pro').'</h2>
		<div class="siteseo-toggle-container">
		<div class="siteseo-toggle-switch ' . (!empty($toggle_state_local_buz) ? 'active' : '') . '" id="siteseo-toggleSwitch-local"></div>
		<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
		<p class="siteseo-toggle-label" id="siteseo-toggle-label-local">' . (!empty($toggle_state_local_buz) ? esc_html__('Click to disable this feature', 'siteseo-pro') : esc_html__('Click to enable this feature', 'siteseo-pro')) . '</p>
		<input type="hidden" name="siteseo_pro_options[toggle_state_local_buz]" id="toggle_state_local_buz" value="' . esc_attr($toggle_state_local_buz) . '"></div>';
	

		echo'<p>'.esc_html__('Local Business data type for Google', 'siteseo-pro').'</p>
		
		<form method="post" action="">';
		wp_nonce_field('sitseo_pro_settings', 'sitseo_pro_settings_nonce');
	
		echo '<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select: auto;">Business type</th>
				<td>
					<select id="siteseo_pro_rich_snippets_lb_type" name="siteseo_pro_options[business_type]">';
					
					foreach ($business_type as $type_value => $type_i18n) {
						$selected = ($type_value == $set_business_type) ? 'selected' : '';
						echo '<option value="'.esc_attr($type_value).'" '.esc_attr($selected).'>'.esc_html($type_i18n).'</option>';
					}
		
					echo '</select>
						</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Street Address</th>
					<td><input type="text" name="siteseo_pro_options[street_address]" placeholder="eg: Place Bellevue" value="'.esc_attr($set_street_address).'">
						<p class="description">' . sprintf('<span class="field-required">%s</span> %s.', esc_html__('Required', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
		
				<tr><th scope="row" style="user-select: auto;">City</th>
					<td><input type="text" name="siteseo_pro_options[city]" placeholder="Biarritz" value="'.esc_attr($set_city).'">
						<p class="description">' . sprintf('<span class="field-required">%s</span> %s.', esc_html__('Required', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">State</th>
					<td><input type="text" name="siteseo_pro_options[state]" placeholder="eg: Nouvelle Aquitaine" value="'.esc_attr($set_state).'">
						<p class="description">' . sprintf('<span class="field-required">%s</span> %s.', esc_html__('Required', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Postal code</th>
					<td><input type="text" name="siteseo_pro_options[postal_code]" placeholder="eg: 64200" value="'.esc_attr($set_postal_code).'">
						<p class="description">' . sprintf('<span class="field-required">%s</span> %s.', esc_html__('Required', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Country</th>
					<td><input type="text" name="siteseo_pro_options[country]" placeholder="eg: France" value="'.esc_attr($set_country).'">
						<p class="description">' . sprintf('<span class="field-required">%s</span> %s.', esc_html__('Required', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Latitude</th>
					<td><input type="text" name="siteseo_pro_options[latitude]" placeholder="eg: 43.4831389" value="'.esc_attr($set_latitude).'">
						 <p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Longitude</th>
					<td><input type="text" name="siteseo_pro_options[longitude]" placeholder="eg: -1.5630987" value="'.esc_attr($set_longitude).'">
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Place ID</th>
					<td><input type="text" name="siteseo_pro_options[place_id]" placeholder="eg: Biarrit" value="'.esc_attr($set_place_id).'">
						<p class="description">' . __('<a href="https://developers.google.com/places/web-service/place-id" target="_blank">Click here to find your Google Maps Place ID</a><span class="siteseo-help dashicons dashicons-external"></span> for your Local Business. <br>This ID will be used to display the Google Maps link from the LB widget.', 'siteseo-pro') . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">URL</th>
					<td><input type="text" name="siteseo_pro_options[url]" placeholder="default:'.get_home_url().'" value="'.esc_attr($set_url).'">
						<p class="description"> '. esc_html__('Default: homepage. Google recommends to include your business details (address, phone, website...) for your visitors too.', 'siteseo-pro') .' </p>
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">Telephone</th>
					<td><input type="text" name="siteseo_pro_options[telephone]" placeholder="eg: +33559240138" value="'.esc_attr($set_telephone).'">
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>            
				
				<tr><th scope="row" style="user-select: auto;">Price range</th>
					<td><input type="text" name="siteseo_pro_options[price_range]" placeholder="eg: $$, €€€, or ££££..." value="'.esc_attr($set_price_range).'">
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto;">'.esc_html__('Cuisine served', 'siteseo-pro').'</th>
					<td><input type="text" name="siteseo_pro_options[cuisine_served]" placeholder="French, Mediterranean, or American" value="'.esc_attr($set_cuisine_served).'">
						<p class="description"> '. esc_html__('Only to be filled if the business type is: "FoodEstablishment", "Bakery", "BarOrPub", "Brewery", "CafeOrCoffeeShop", "FastFoodRestaurant", "IceCreamShop", "Restaurant" or "Winery".', 'siteseo-pro').' </p>
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>            
				
				<tr><th scope="row" style="user-select: auto;">'.esc_html__('Accepts Reservations', 'siteseo-pro').'</th>
					<td><input type="text" name="siteseo_pro_options[accepts_reser]" placeholder="eg.True" value="'.esc_attr($set_accepts_reser).'">
						<p class="description"> '. esc_html__('Indicates whether a FoodEstablishment accepts reservations. Values can be Boolean (True or False), an URL at which reservations can be made or (for backwards compatibility) the strings Yes or No.', 'siteseo-pro').' </p>
						<p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p>
					</td>
				</tr>
			
				<tr><th scope="row" style="user-select: auto;">Opening hours</th>    
					<td><div class="siteseo-notice">
						<p>' . __('<strong>Morning and Afternoon are just time slots</strong>', 'siteseo-pro') . '</p>
						<p>' . esc_html__('Eg: if you\'re opened from 10:00 AM to 9:00 PM, check Morning and enter 10:00 / 21:00.', 'siteseo-pro') . '</p>
						<p>' . esc_html__('If you are open non-stop, check Morning and enter 0:00 / 23:59.', 'siteseo-pro') . '</p>
						</div>
					
					<ul style="list-style-type: none;">';
					
					foreach($days as $key => $day) {

						$is_closed = !empty($options['opening_hours'][$key]['closed']) ? $options['opening_hours'][$key]['closed'] : 0;
						$open_morning = !empty($options['opening_hours'][$key]['open_morning']) ? $options['opening_hours'][$key]['open_morning'] : 0;
						$open_afternoon = !empty($options['opening_hours'][$key]['open_afternoon']) ? $options['opening_hours'][$key]['open_afternoon'] : 0;
						
						// Get saved start and end times
						$open_morning_start_hour = !empty($options['opening_hours'][$key]['open_morning_start_hour']) ? $options['opening_hours'][$key]['open_morning_start_hour'] : '';
						$open_morning_start_min = !empty($options['opening_hours'][$key]['open_morning_start_min']) ? $options['opening_hours'][$key]['open_morning_start_min'] : '';
	
						$open_morning_end_hour = !empty($options['opening_hours'][$key]['open_morning_end_hour']) ? esc_attr($options['opening_hours'][$key]['open_morning_end_hour']) : '';
						$open_morning_end_min = !empty($options['opening_hours'][$key]['open_morning_end_min']) ? esc_attr($options['opening_hours'][$key]['open_morning_end_min']) : '';
						
						$open_afternoon_start_hour = !empty($options['opening_hours'][$key]['open_afternoon_start_hour']) ? esc_attr($options['opening_hours'][$key]['open_afternoon_start_hour']) : '';
						$open_afternoon_start_min = !empty($options['opening_hours'][$key]['open_afternoon_start_min']) ? esc_attr($options['opening_hours'][$key]['open_afternoon_start_min']) : '';
						$open_afternoon_end_hour = !empty($options['opening_hours'][$key]['open_afternoon_end_hour']) ? esc_attr($options['opening_hours'][$key]['open_afternoon_end_hour']) : '';
						$open_afternoon_end_min = !empty($options['opening_hours'][$key]['open_afternoon_end_min']) ? esc_attr($options['opening_hours'][$key]['open_afternoon_end_min']) : '';
	
						echo'<li><h3>' . esc_html($day) . '</h3></li><hr><br>
						<input type="checkbox"'.(!empty($is_closed) ? 'checked="yes"' : '').' name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][closed]" value="1"> Closed all the day?</input><br /><br />
						<input type="checkbox"'.(!empty($open_morning) ? 'checked="yes"' : '').' name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_morning]" value="1">Open in the morning?</input><br /><br />
						<div style="display:flex;">
						<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_morning_start_hour]">';

						foreach($hours as $hour){
							$selected = ($hour == $open_morning_start_hour) ? 'selected' : '';
							echo '<option value="' . esc_attr($hour) . '" ' . esc_attr($selected) . '>' . esc_html($hour) . '</option>';
						}
						
						echo '</select>:<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_morning_start_min]">';

						foreach($mins as $min){
							$selected = ($min == $open_morning_start_min) ? 'selected' : '';
							echo '<option value="' . esc_attr($min) . '" ' . esc_attr($selected) . '>' . esc_html($min) . '</option>';
						}
						
						echo '</select>-<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_morning_end_hour]">';
						
						foreach($hours as $hour){
							$selected = ($hour == $open_morning_end_hour) ? 'selected' : '';
							echo '<option value="'. esc_attr($hour) .'" '.esc_attr($selected).'>'.esc_html($hour).'</option>';
						}
						
						echo '</select>:<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_morning_end_min]">';
						
						foreach($mins as $min){
							$selected = ($min == $open_morning_end_min) ? 'selected' : '';
							echo '<option value="' . esc_attr($min) . '" ' . esc_attr($selected) . '>' . esc_html($min) . '</option>';
						}
		
						echo '</select><br><br></div>';
				
						echo'<br><input type="checkbox"'.(!empty($open_afternoon) ? 'checked="yes"' : '').' name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_afternoon]" value="1">Open in the afternoon?</input><br /><br />
						 <div style="display:flex;"><select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_afternoon_start_hour]">';
							
							// Get saved start and end times	
							foreach($hours as $hour){
								$selected = ($hour == $open_afternoon_start_hour) ? 'selected' : '';
								echo '<option value="' . esc_attr($hour) . '" ' . esc_attr($selected) . '>' . esc_html($hour) . '</option>';
							}
							
							echo'</select>:<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_afternoon_start_min]">';
								foreach($mins as $min){
									$selected = ($min == $open_afternoon_start_min) ? 'selected' : '';
									echo '<option value="' . esc_attr($min) . '" ' . esc_attr($selected) . '>' . esc_html($min) . '</option>';
								}
	
							echo'</select>-<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_html($key).'][open_afternoon_end_hour]">';
								foreach($hours as $hour){
								   $selected = ($hour == $open_afternoon_end_hour) ? 'selected' : '';
									echo '<option value="' . esc_attr($hour) . '" ' . esc_attr($selected) . '>' . esc_html($hour) . '</option>';
								}
								
							echo'</select>:<select style="width:auto;" name="siteseo_pro_options[opening_hours]['.esc_attr($key).'][open_afternoon_end_min]">';
								foreach($mins as $min){
									$selected = ($min == $open_afternoon_end_min) ? 'selected' : '';
									echo '<option value="' . esc_attr($min) . '" ' . esc_attr($selected) . '>' . esc_attr($min) . '</option>';
								}

							echo'</select></div>';

						}
						echo '<br><br><p class="description">' . sprintf('<span class="field-recommended">%s</span> %s.', esc_html__('Recommended', 'siteseo-pro'), esc_html__('property by Google', 'siteseo-pro')) . '</p></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="local_business_settings" value="1"/>';
	}


	static function structured_data(){
		global $siteseo;

		if(!empty($_POST['sumbit'])){
			self::save_settings();
		}

		$options = $siteseo->pro;

		//load settings
		$toggle_state_stru_data = isset($options['toggle_state_stru_data']) ? $options['toggle_state_stru_data'] : '';
		$option_set_enable_structured_data = isset($options['enbaled_structured_data']) ? $options['enbaled_structured_data'] : '';
		$option_logo_url = isset($options['structured_data_image_url']) ? $options['structured_data_image_url'] : '';
		$option_desciption = isset($options['org_desciption']) ? $options['org_desciption'] : '';
		$option_email_id = isset($options['org_email']) ? $options['org_email'] : '';
		$option_phone = isset($options['org_phone_no']) ? $options['org_phone_no'] : '';
		$option_legal_name = isset($options['org_legal']) ? $options['org_legal'] : '';
		$option_establish_date = isset($options['establish_date']) ? $options['establish_date'] : '';
		$option_number_emp = isset($options['number_emp']) ? $options['number_emp'] : '';
		$option_vat_id = isset($options['vat_id']) ? $options['vat_id'] : '';
		$option_tax_id = isset($options['tax_id']) ? $options['tax_id'] : ''; 
		$option_iso_id = isset($options['iso_code']) ? $options['iso_code'] : '';
		$option_let_code = isset($options['let_code']) ? $options['let_code'] : '';
		$option_duns_number = isset($options['duns_number']) ? $options['duns_number'] : '';
		$option_naics_code = isset($options['naics_code']) ? $options['naics_code'] : '';
		
		echo'<h2>Structured Data Types (schema.org)</h2>
		<div class="siteseo-toggle-container">
		<div class="siteseo-toggle-switch ' . (!empty($toggle_state_stru_data) ? 'active' : '') . '" id="siteseo-toggleSwitch-structured-data"></div>
		<span id="siteseo-arrow-icon" class="dashicons dashicons-arrow-left-alt siteseo-arrow-icon"></span>
		<p class="siteseo-toggle-label" id="siteseo-toggle-label-stru-data">' . (!empty($toggle_state_stru_data) ? 'Click to disable this feature' : 'Click to enable this feature') . '</p>
		<input type="hidden" name="siteseo_pro_options[toggle_state_stru_data]" id="toggle_state_stru_data" value="' . esc_attr($toggle_state_stru_data) . '"></div>
		<p>Add Structured Data Types support, mark your content, and get better Google Search Results.</p>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row" style="user-select: auto;">Enable Structured Data</th>
					<td>
						<label for="siteseo_structured_data">
						<input id="siteseo_structured_data" name="siteseo_pro_options[enbaled_structured_data]" type="checkbox" ' . (empty(empty($option_set_enable_structured_data)) ? 'checked="yes"' : '') .' value="1"/>' .
						esc_html__('Enable Structured Data Types metabox for your posts, pages and custom post types', 'siteseo-pro') .'</label>
					</td>
				</tr>
				<tr>
					<th scope="row" style="user-select: auto">Upload your publisher logo</th>
					<td>
						<input id="structured_data_image_url" autocomplete="off" type="text" value="'. esc_url($option_logo_url) .'" name="siteseo_pro_options[structured_data_image_url]" aria-label="'. esc_html__('Upload your publisher logo', 'siteseo-pro').'" placeholder="'. esc_html__('Select your logo', 'siteseo-pro') .'" />	
						<input id="siteseopro_structured_data_upload_img" class="btn btnSecondary" type="button" value="'. esc_html__('Upload an Image', 'siteseo-pro') .'" />
					</td>
				</tr>

				<tr><th scope="row" style="user-select:auto"></th>
					<td>
						<p><img style="width:auto;height:auto;max-width:560px;margin-top:15px;display:inline-block;" src="'.esc_url($option_logo_url) .'" /></p>
					</td>
				</tr>
				
				<tr><th scope="row" style="user-select: auto"></th>
					<td>
						<div class="siteseo-notice">
							<h3> '. esc_html__('Make sure your image follow these Google guidelines', 'siteseo-pro').' </h3>
							<ul>
								<li> '. esc_html__('A logo that is representative of the organization.', 'siteseo-pro').' </li>
								<li> '. esc_html__('Files must be BMP, GIF, JPEG, PNG, WebP or SVG.', 'siteseo-pro') .' </li>
								<li> '. esc_html__('The image must be 112x112px, at minimum.', 'siteseo-pro') .' </li>
								<li> '. esc_html__('The image URL must be crawlable and indexable.', 'siteseo-pro') .' </li>
								<li> '. esc_html__('Make sure the image looks how you intend it to look on a purely white background (for example, if the logo is mostly white or gray, it may not look how you want it to look when displayed on a white background).', 'siteseo-pro') .' </li>
							</ul>
							<p>
								<span class="siteseo-help dashicons dashicons-external"></span>
								<a class="siteseo-help" href="https://developers.google.com/search/docs/appearance/structured-data/logo#structured-data-type-definitions" target="_blank"> '. esc_html__('Learn more', 'siteseo-pro') .' </a>
							</p>
						</div>
					</td>
				</tr>
				
				<tr>
					<th scope="row" style="user-select:auto;">'.esc_html__('Organization Legal Name', 'siteseo-pro').'</th>
					<td>
						<input type="text" name="siteseo_pro_options[org_legal]" placeholder="'.esc_attr__('Enter Organizations legal name', 'siteseo-pro').'" value="'.esc_attr($option_legal_name).'">
						<p class="description">' . esc_html__('The official registered name of your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select: auto;">'.esc_html__('Organization Description', 'siteseo-pro').'</th>
					<td>
						<input type="text" name="siteseo_pro_options[org_desciption]" placeholder="'.esc_attr__('Enter Organizations description', 'siteseo-pro').'" value="'.esc_attr($option_desciption).'">
						<p class="description">' . esc_html__('A brief description of your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">'.esc_html__('Organization Email Address', 'siteseo-pro').' </th>
					<td>
						<input type="text" name="siteseo_pro_options[org_email]" placeholder="'.esc_attr__('Enter Organizations email Id', 'siteseo-pro').'" value="'.esc_attr($option_email_id).'">
						<p class="description">' . esc_html__('The primary email address for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">'.esc_html__('Organization Phone Number', 'siteseo-pro').'</th>
					<td>
						<input type="text" name="siteseo_pro_options[org_phone_no]" placeholder="'.esc_attr__('Enter Organizations contact number', 'siteseo-pro').'" value="'.esc_attr($option_phone).'">
						<p class="description">' . esc_html__('The main contact number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">'.esc_html__('Organization Establish Date', 'siteseo-pro').'</th>
					<td>
						<input type="date" id="org_establish_date" style="width: 560px; height: 40px;"  name="siteseo_pro_options[establish_date]" placeholder="Select Organization establish date" value="'.esc_attr($option_establish_date).'">
						<p class="description">' . esc_html__('The date when your organization was established.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">Number Of Employees</th>
					<td>
						<input type="text" name="siteseo_pro_options[number_emp]" placeholder="Enter Organizations legal name" value="'.esc_attr($option_number_emp).'">
						<p class="description">' . esc_html__('The total number of employees in your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;"><h2>Organization Identifiers</h2><br/></th>
					<td>
						<p class="description">' . esc_html__('We would like to know more about your organization’s identifiers. This information will assist Google in providing accurate and relevant details about your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">VAT ID</th>
					<td>
						<input type="text" name="siteseo_pro_options[vat_id]" placeholder="Enter Organization VAT ID" value="'.esc_attr($option_vat_id).'">
						<p class="description">' . esc_html__('The VAT identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">Tax ID</th>
					<td>
						<input type="text" name="siteseo_pro_options[tax_id]" placeholder="Enter Organization Tax ID" value="'.esc_attr($option_tax_id).'">
						<p class="description">' . esc_html__('The tax identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">ISO 6523</th>
					<td>
						<input type="text" name="siteseo_pro_options[iso_code]" placeholder="Enter Organization ISO 6523" value="'.esc_attr($option_iso_id).'">
						<p class="description">' . esc_html__('The ISO 6523	identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">LEI Code</th>
					<td>
						<input type="text" name="siteseo_pro_options[let_code]" placeholder="'.esc_html__('Enter Organization LEI Code', 'siteseo-pro').'" value="'.esc_attr($option_let_code).'">
						<p class="description">' . esc_html__('The LET identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">DUNS</th>
					<td>
						<input type="text" name="siteseo_pro_options[duns_number]" placeholder="'.esc_html__('Enter Organization DUNS code', 'siteseo-pro').'" value="'.esc_attr($option_duns_number).'">
						<p class="description">' . esc_html__('The DUNS identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>

				<tr>
					<th scope="row" style="user-select:auto;">NAICS</th>
					<td>
						<input type="text" name="siteseo_pro_options[naics_code]" placeholder="'.esc_html__('Enter Organization NAICS', 'siteseo-pro').'" value="'.esc_attr($option_naics_code).'">
						<p class="description">' . esc_html__('The NAICS identification number for your organization.', 'siteseo-pro') . '</p>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="structured_data_settings" value="1"/>';
	}

	// save settings fun
	static function save_settings(){
		global $siteseo;
		
		check_admin_referer('sitseo_pro_settings');

		if(!current_user_can('manage_options') || !is_admin()){
			return;
		}

		$options = $siteseo->pro;

		if(empty($_POST['siteseo_pro_options'])){
			return;
		}

		// WooCommerce tab
		if(isset($_POST['woocommerce_settings'])){
			
			if(isset($_POST['siteseo_pro_options']['toggle_state_woocommerce'])){
				$options['toggle_state_woocommerce'] = sanitize_text_field($_POST['siteseo_pro_options']['toggle_state_woocommerce']);
			}
			
			$options['woocommerce_cart_page_no_index'] = isset($_POST['siteseo_pro_options']['woocommerce_cart_page_no_index']);
			$options['woocommerce_checkout_page_no_index'] = isset($_POST['siteseo_pro_options']['woocommerce_checkout_page_no_index']);
			$options['woocommerce_customer_account_page_no_index'] = isset($_POST['siteseo_pro_options']['woocommerce_customer_account_page_no_index']);
			$options['woocommerce_product_og_price'] = isset($_POST['siteseo_pro_options']['woocommerce_product_og_price']);
			$options['woocommerce_product_og_currency'] = isset($_POST['siteseo_pro_options']['woocommerce_product_og_currency']);
			$options['woocommerce_meta_generator'] = isset($_POST['siteseo_pro_options']['woocommerce_meta_generator']);
			$options['woocommerce_schema_output'] = isset($_POST['siteseo_pro_options']['woocommerce_schema_output']);
			$options['woocommerce_schema_breadcrumbs_output'] = isset($_POST['siteseo_pro_options']['woocommerce_schema_breadcrumbs_output']);
		}

		// Dublin Core settings
		if(isset($_POST['dublin_code_settings'])){
			
			if(isset($_POST['siteseo_pro_options']['toggle_state_dublin_core'])){
				$options['toggle_state_dublin_core'] = sanitize_text_field($_POST['siteseo_pro_options']['toggle_state_dublin_core']);
			}
			
			$options['dublin_core_enable'] = isset($_POST['siteseo_pro_options']['dublin_core_enable']);
		}

		// Easy Digital Downloads
		if(isset($_POST['digital_download_settings'])){

			if(isset($_POST['siteseo_pro_options']['toggle_state_easy_digital'])){
				$options['toggle_state_easy_digital'] = sanitize_text_field($_POST['siteseo_pro_options']['toggle_state_easy_digital']);
			}
						
			$options['edd_product_og_price'] = isset($_POST['siteseo_pro_options']['edd_product_og_price']);

			$options['edd_product_og_currency'] = isset($_POST['siteseo_pro_options']['edd_product_og_currency']);

			$options['edd_meta_generator'] = isset($_POST['siteseo_pro_options']['edd_meta_generator']);
		}

		// PageSpeed Settings
		if(isset($_POST['pagespeed_settings'])){
			$options['ps_api_key'] = !empty($_POST['siteseo_pro_options']['ps_api_key']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['ps_api_key'])) : '';
		}
		
		//local Business settings
		if(isset($_POST['local_business_settings'])){

			// toogle btn
			$options['toggle_state_local_buz'] = isset($_POST['siteseo_pro_options']['toggle_state_local_buz']);
			
			$options['local_business_display_schema'] = !empty($_POST['siteseo_pro_options']['local_business_display_schema']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']
				['local_business_display_schema'])) : '';

			$options['street_address'] = !empty($_POST['siteseo_pro_options']['street_address']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['street_address'])) : '';

			$options['city'] = !empty($_POST['siteseo_pro_options']['city']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['city'])) : '';

			$options['state'] = !empty($_POST['siteseo_pro_options']['state']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['state'])) : '';

			$options['postal_code'] = !empty($_POST['siteseo_pro_options']['postal_code']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['postal_code'])) : '';

			$options['country'] = !empty($_POST['siteseo_pro_options']['country']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['country'])) : '';

			$options['latitude'] = !empty($_POST['siteseo_pro_options']['latitude']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['latitude'])) : '';

			$options['longitude'] = !empty($_POST['siteseo_pro_options']['longitude']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['longitude'])) : '';

			$options['place_id'] = !empty($_POST['siteseo_pro_options']['place_id']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['place_id'])) : '';

			$options['url'] = !empty($_POST['siteseo_pro_options']['url']) ? sanitize_url(wp_unslash($_POST['siteseo_pro_options']['url'])) : '';

			$options['telephone'] = !empty($_POST['siteseo_pro_options']['telephone']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['telephone'])) : '';

			$options['price_range'] = !empty($_POST['siteseo_pro_options']['price_range']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['price_range'])) : '';

			$options['cuisine_served'] = !empty($_POST['siteseo_pro_options']['cuisine_served']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['cuisine_served'])) : '';

			$options['accepts_reser'] = !empty($_POST['siteseo_pro_options']['accepts_reser']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['accepts_reser'])) : '';
			
			// business type
			if(!empty($_POST['siteseo_pro_options']['business_type'])){
				$options['business_type'] = sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['business_type']));
			}

			// opening hours
			if(!empty($_POST['siteseo_pro_options']['opening_hours'])){
				$opening_hours = [];
				foreach($_POST['siteseo_pro_options']['opening_hours'] as $day => $hours){
					$opening_hours[$day] = [
						'closed' => !empty($hours['closed']),
						'open_morning' => !empty($hours['open_morning']),
						'open_morning_start_hour' => sanitize_text_field($hours['open_morning_start_hour']),
						'open_morning_start_min' => sanitize_text_field($hours['open_morning_start_min']),
						'open_morning_end_hour' => sanitize_text_field($hours['open_morning_end_hour']),
						'open_morning_end_min' => sanitize_text_field($hours['open_morning_end_min']),
						'open_afternoon' => !empty($hours['open_afternoon']) ? true : false,
						'open_afternoon_start_hour' => sanitize_text_field($hours['open_afternoon_start_hour']),
						'open_afternoon_start_min' => sanitize_text_field($hours['open_afternoon_start_min']),
						'open_afternoon_end_hour' => sanitize_text_field($hours['open_afternoon_end_hour']),
						'open_afternoon_end_min' => sanitize_text_field($hours['open_afternoon_end_min'])
					];
				}
				$options['opening_hours'] = $opening_hours;
			}
		}

		// Strutured data settings
		if(isset($_POST['structured_data_settings'])){
			
			$options['enbaled_structured_data'] = !empty($_POST['siteseo_pro_options']['enbaled_structured_data']);
			
			$options['toggle_state_stru_data'] = !empty($_POST['siteseo_pro_options']['toggle_state_stru_data']);

			$options['structured_data_image_url'] = !empty($_POST['siteseo_pro_options']['structured_data_image_url']) ? sanitize_url(wp_unslash($_POST['siteseo_pro_options']['structured_data_image_url'])) : '';

			$options['org_desciption'] = !empty($_POST['siteseo_pro_options']['org_desciption']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['org_desciption'])) : '';

			$options['org_email'] = !empty($_POST['siteseo_pro_options']['org_email']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['org_email'])) : '';

			$options['org_phone_no'] = !empty($_POST['siteseo_pro_options']['org_phone_no']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['org_phone_no'])) : '';

			$options['org_legal'] = !empty($_POST['siteseo_pro_options']['org_legal']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['org_legal'])) : '';

			$options['establish_date'] = !empty($_POST['siteseo_pro_options']['establish_date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['establish_date'])) : '';
		
			$options['number_emp'] = !empty($_POST['siteseo_pro_options']['number_emp']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['number_emp'])) : '';
			
			$options['vat_id'] = !empty($_POST['siteseo_pro_options']['vat_id']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['vat_id'])) : '';

			$options['tax_id'] = !empty($_POST['siteseo_pro_options']['tax_id']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['tax_id'])) : '';

			$options['iso_code'] = !empty($_POST['siteseo_pro_options']['iso_code']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['iso_code'])) : '';

			$options['let_code'] = !empty($_POST['siteseo_pro_options']['let_code']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['let_code'])) : '';

			$options['duns_number'] = !empty($_POST['siteseo_pro_options']['duns_number']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['duns_number'])) : '';

			$options['naics_code'] = !empty($_POST['siteseo_pro_options']['naics_code']) ? sanitize_text_field(wp_unslash($_POST['siteseo_pro_options']['naics_code'])) : '';
		}

		$siteseo->pro = $options; // Updates the global variable
		update_option('siteseo_pro_options', $options);
	}
}
