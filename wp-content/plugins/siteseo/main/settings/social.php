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

class Social{

    static function menu(){
        global $siteseo;
		
		Dashbord::admin_header();
		
		$social_toggle = isset($siteseo->setting_enabled['toggle-social']) ? $siteseo->setting_enabled['toggle-social'] : '';
		$nonce = wp_create_nonce('siteseo_toggle_nonce');

        $current_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'tab_knowledge_graph'; // Default tab

        $social_subtabs = [
            'tab_knowledge_graph' => esc_html__('Knowledge Graph', 'siteseo'),
            'tab_social_accounts' => esc_html__('Your social accounts', 'siteseo'),
            'tab_facebook' => esc_html__('Facebook (Open Graph) ', 'siteseo'),
            'tab_twitter' => esc_html__('Twitter (Twitter card)', 'siteseo')
        ];

        echo'<form method="post" id="siteseo-form" class="siteseo-option" name="siteseo-flush">';

        wp_nonce_field('siteseo_social_settings');

		Dashbord::render_toggle('Social Networks - SiteSEO', 'social_toggle', $social_toggle, $nonce);

        echo'<div id="siteseo-tabs" class="wrap">
        <div class="nav-tab-wrapper">';

        foreach($social_subtabs as $tab_key => $tab_caption){
			$active_class = ($current_tab === $tab_key) ? ' nav-tab-active' : '';
			echo'<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab' . esc_attr($active_class) . '" data-tab="' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
        }

        echo'</div>
		<div class="tab-content-wrapper">
        <div class="siteseo-tab' . ($current_tab == 'tab_knowledge_graph' ? ' active' : '') . '" id="tab_knowledge_graph">';
        self::knowledge_graph();
        echo'</div>
        <div class="siteseo-tab' . ($current_tab == 'tab_social_accounts' ? ' active' : '') . '" id="tab_social_accounts">';
        self::social_accouts();
        echo'</div>
        <div class="siteseo-tab' . ($current_tab == 'tab_twitter' ? ' active' : '') . '" id="tab_twitter">';
        self::twitter();
        echo'</div>
        <div class="siteseo-tab' . ($current_tab == 'tab_facebook' ? ' active' : '') . '" id="tab_facebook">';
        self::facebook();
        echo'</div>
		</div>'; 

        siteseo_submit_button(__('save changes', 'siteseo'));
        echo'</form>';
 
    }

    static function knowledge_graph(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

        $options = get_option('siteseo_social_option_name');

        //load data
        $option_org_type = isset($options['social_knowledge_type']) ? $options['social_knowledge_type'] : '';
        $option_org_name = isset($options['social_knowledge_name']) ? $options['social_knowledge_name'] : '';
        $option_org_logo = isset($options['social_knowledge_img']) ? $options['social_knowledge_img'] : '';
        $option_org_number = isset($options['social_knowledge_phone']) ? $options['social_knowledge_phone'] : '';
        $option_org_contact_type = isset($options['social_knowledge_contact_type']) ? $options['social_knowledge_contact_type'] : '';
        $option_org_contact_option = isset($options['social_knowledge_contact_option']) ? $options['social_knowledge_contact_option'] : '';

        echo'<h3 class="siteseo-tabs">Knowledge Graph</h3>
        <p class="description">Configure Google Knowledge Graph.</p>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Person or organization</th>
                    <td>
                        <select name="siteseo_options[org_type]">
                            <option value="None (will disable this feature)" '.selected($option_org_type, 'Customer support', false).'>'.esc_html('None','siteseo').'</option>
                            <option value="Person" '.selected($option_org_type, 'Person', false).'>'.esc_html('Person','siteseo').'</option>
                            <option value="Organization" '.selected($option_org_type, 'Organization', false).'>'.esc_html('Organization','siteseo').'</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Your name/organization</th>
                    <td>
                        <input type="text" name="siteseo_options[org_name]" value="'.esc_attr($option_org_name).'" placeholder="eg.Miremont">
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Your photo/organization logo</th>
                    <td>
                        <input id="knowledge_org_logo_url" autocomplete="off" type="text" name="siteseo_options[org_logo]" value="'.esc_url($option_org_logo).'" placeholder="select your logo">
                        <button id="knowledge_org_logo" class="btn btnSecondary">'.esc_html('Upload an image','siteseo').'</button>
						<p class="description">JPG, PNG, WebP and GIF allowed.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Organizations phone number (only for Organizations)</th>
                    <td>
                        <input type="text" name="siteseo_options[org_contact_number]" value="'.esc_attr($option_org_number).'" placeholder="eg: +33123456789 (internationlized version required)">
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Contact type (only for Organizations)</th>
                    <td>
                        <select name="siteseo_options[org_contact_type]">
                            <option value="Customer support" '.selected($option_org_contact_type, 'Customer support', false).'>'.esc_html('Customer support','siteseo').'</option>
                            <option value="Technical support" '.selected($option_org_contact_type, 'Technical support', false).'>'.esc_html('Technical support','siteseo').'</option>
                            <option value="Billing support" '.selected($option_org_contact_type, 'Billing support', false).'>'.esc_html('Billing support','siteseo').'</option>
                            <option value="Bill payment" '.selected($option_org_contact_type, 'Bill payment', false).'>'.esc_html('Bill payment','siteseo').'</option>
                            <option value="Sales payment" '.selected($option_org_contact_type, 'Sales payment', false).'>'.esc_html('Sales payment','siteseo').'</option>
                            <option value="Credit card support" '.selected($option_org_contact_type, 'Credit card support', false).'>'.esc_html('Credit card support','siteseo').'</option>
                            <option value="Emergency support" '.selected($option_org_contact_type, 'Emergency support', false).'>'.esc_html('Emergency support','siteseo').'</option>
                            <option value="Baggage tracking" '.selected($option_org_contact_type, 'Baggage tracking', false).'>'.esc_html('Baggage tracking','siteseo').'</option>
                            <option value="Roadside assistance" '.selected($option_org_contact_type, 'Roadside assistance', false).'>'.esc_html('Roadside assistance','siteseo').'</option>
                            <option value="Package tracking" '.selected($option_org_contact_type, 'Package tracking', false).'>'.esc_html('Package tracking','siteseo').'</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Contact option (only for Organizations)</th>
                    <td>
                        <select name="siteseo_options[org_contact_option]">
                            <option value="None" '.selected($option_org_contact_option, 'None', false).'>'.esc_html('None','siteseo').'</option>
                            <option value="TollFree" '.selected($option_org_contact_option, 'TollFree', false).'>'.esc_html('TollFree', 'siteseo').'</option>
                            <option value="HearingImpairedSupported" '.selected($option_org_contact_option, 'HearingImpairedSupported', false).'>'.esc_html('Hearing Impaired Supported','siteseo').'</option>
                        </select>
                    </td>
                </tr>        
            </tbody>
        </table><input type="hidden" name="siteseo_options[knowledge_graph_tab]" value="1" >';
    }

    static function social_accouts(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

		//$options = $siteseo->social_settings;
        $options = get_option('siteseo_social_option_name');

        //load settings
        $options_facebook_acct = isset($options['social_accounts_facebook']) ? $options['social_accounts_facebook'] : '';
        $options_twitter_acct = isset($options['social_accounts_twitter']) ? $options['social_accounts_twitter'] : '';
        $options_instagram_acct = isset($options['social_accounts_instagram']) ? $options['social_accounts_instagram'] : '';
        $options_youtube_acct = isset($options['social_accounts_youtube']) ? $options['social_accounts_youtube'] : '';
        $options_pinterest_acct = isset($options['social_accounts_pinterest']) ? $options['social_accounts_pinterest'] : '';

        echo'<h3 class="siteseo-tabs">Your social accouts</h3>
         <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Facebook</th>
                    <td>
                        <input type="text" name="siteseo_options[facebook]" placeholder="eg: https://facebook.com/my-page-url" value="'.esc_url($options_facebook_acct).'">
                    </td>   
                </tr>

                 <tr>
                    <th scope="row" style="user-select:auto;">X Username</th>
                    <td>
                        <input type="text" name="siteseo_options[twitter]" placeholder="eg : @my_twitter_account" value="'.esc_url($options_twitter_acct).'">
                    </td>   
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Pinterest URL</th>
                    <td>
                        <input type="text" name="siteseo_options[pinterest]" placeholder="eg : https://pinterest.com/my-page-url/" value="'.esc_url($options_pinterest_acct).'">
                    </td>   
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Instagram URL</th>
                    <td>
                        <input type="text" name="siteseo_options[instagram]" placeholder="eg : https://www.instagram.com/my-page-url/" value="'.esc_url($options_instagram_acct).'">
                    </td>   
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">YouTube URL</th>
                    <td>
                        <input type="text" name="siteseo_options[youtube]" placeholder="eg : https://www.youtube.com/my-channel-url/" value="'.esc_url($options_youtube_acct).'">
                    </td>   
                </tr>
 
            </tbody>
        </table><input type="hidden" name="siteseo_options[social_account_tab]" value="1">';
    }

    static function twitter(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

		//$options = $siteseo->social_settings;
        $options = get_option('siteseo_social_option_name');

        //load data
        $option_enable_card = isset($options['social_twitter_card']) ? $options['social_twitter_card'] : 1;
        $options_og_card = isset($options['social_twitter_card_og']) ? $options['social_twitter_card_og'] : '';
		$option_image_size = isset($options['social_twitter_card_img_size']) ? $options['social_twitter_card_img_size'] : '';
		$option_twitter_img = isset($options['social_twitter_card_img']) ? $options['social_twitter_card_img'] : '';

        echo'<h3 class="siteseo-tabs">Twitter (Twitter card)</h3>
        <p class="description">Manage your Twitter card.</p>

        <div class="siteseo-notice">
            <span class="dashicons dashicons-info"></span>
            <div>
                <p>'.wp_kses_post(__('We generate the <strong>og:image</strong> meta in this order:', 'siteseo')).'</p>
                <ol>
                    <li> '.esc_html__('Custom OG Image from SEO metabox', 'siteseo').'</li>
                    <li> '.esc_html__('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo').'</li>
                    <li> '.esc_html__('First image of your post content', 'siteseo').'</li>
                    <li> '.esc_html__('Global OG Image set in SEO > Social > Open Graph', 'siteseo').'</li>
                    <li> '.esc_html__('Site icon from the Customizer', 'siteseo').'</li>
                </ol>
            </div>
        </div>
        
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user:select-auto;">Twitter (Twitter card)</th>
                    <td>Manage your Twitter card. </td>
                </tr>

                <tr>
                    <th scope="row" style="user:select-auto;">Enable Twitter card</th>
                    <td>
                       <label for="enable_twitter_card"><input id="enable_twitter_card" type="checkbox" name="siteseo_options[enable_twitter_card]" ' . (!empty($option_enable_card) ? 'checked="checked"' : 'value="1"') . '>' . esc_html('Enable Twitter card', 'siteseo') . '
						</label>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user:select-auto;">Use OG if no Twitter Cards</th>
                    <td>
                        <label>
                            <input id="enable_twitter_card" type="checkbox" name="siteseo_options[card_og]" ' . (!empty($options_og_card) ? 'checked="checked"' : 'value="1"') . '>'.esc_html(' Use OG if no Twitter Cards', 'siteseo').'
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user:select-auto;">Default Twitter Image</th>
                    <td>
                        <input type="text" id="twitter_logo_url" autocomplete="off" name="siteseo_options[twitter_img]" value="'.esc_url($option_twitter_img).'" placeholder="select your default thumbnail">
						<button id="twitter_logo" class="btn btnSecondary">'.esc_html('Upload a image','siteseo').'</button>
						<p class="description">Minimum size: 144x144px (300x157px with large card enabled), ideal ratio 1:1 (2:1 with large card), 5Mb max.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user:select-auto;">Twitter Card Image Size</th>
                    <td>
                        <select name="siteseo_options[image_size]">
                            <option value="Default" '.selected($option_image_size, 'Default', false).'>'.esc_html('Default','siteseo').'</option>
                            <option value="Large" '.selected($option_image_size, 'Large', false).'>'.esc_html('Large','siteseo').'</option>
                        </select>
                    </td>
                </tr>
				
            </tbody>
        </table><input type="hidden" name="siteseo_options[twitter_tab]" value="1">';
    }

    static function facebook(){
        global $siteseo;

        if(!empty($_POST['submit'])){
            self::save_settings();
        }

        // load seetings
		//$options = $siteseo->social_settings;
        $options = get_option('siteseo_social_option_name');

        $option_fb_enable_og = isset($options['social_facebook_og']) ? $options['social_facebook_og'] : 1;
        $option_fb_img = isset($options['social_facebook_img']) ? $options['social_facebook_img'] : '';
        $option_fb_defult_img = isset($options['social_facebook_img_default']) ? $options['social_facebook_img_default'] : '';
        $option_fb_ownership = isset($options['social_facebook_link_ownership_id']) ? $options['social_facebook_link_ownership_id'] : '';
        $option_fb_admin_id = isset($options['social_facebook_admin_id']) ? $options['social_facebook_admin_id'] : '';

        echo'<h3 class="siteseo-tabs">Facebook (Open Graph)</h3>
        <p class="description">Manage Open Graph data. These metatags will be used by Facebook, Pinterest, LinkedIn, WhatsApp... when a user shares a link on its own social network. Increase your click-through rate by providing relevant information such as an attractive image.<p>
        
        <div class="siteseo-notice">
            <span class="dashicons dashicons-info"></span>
            <div>
                <p> '.wp_kses_post(__('We generate the <strong>twitter:image</strong> meta in this order:', 'siteseo')).'</p>
                <ol>
                    <li> '.esc_html__('Custom Twitter image from SEO metabox', 'siteseo'). '</li>
                    <li> '.esc_html__('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo').' </li>
                    <li> '.esc_html__('First image of your post content', 'siteseo').'</li>
                    <li> '.esc_html__('Global Twitter:image set in SEO > Social > Twitter Card', 'siteseo').'</li>
                </ol>
            </div>
        </div>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row" style="user-select:auto;">Enable OG date</th>
                    <td>
                        <label for="facebook_graph_enable">
                        <input id="facebook_graph_enable" type="checkbox" name="siteseo_options[enable_fb_og]" '.(!empty($option_fb_enable_og) ? 'checked="yes"' : 'value="1"').'>'. esc_html('Enable OG data','siteseo') .'
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Default Image</th>
                    <td>
                        <input id="facebook_org_image_url" autocomplete="off" type="text" name="siteseo_options[fb_image]" value="'.esc_url($option_fb_img).'" palceholder="Select your default thumbnail">
                        <button id="facebook_upload_logo" class="btn btnSecondary">'.esc_html('Upload a image','siteseo').'</button>
						<p class="description">Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens)</p>
                        <p class="description">If no default image is set, we‘ll use your site icon defined from the Customizer.</p>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto;">Override Default Image</th>
                    <td>
                        <div class="siteseo_wrap_label">
                            <label for="override_image_tag">
                            <input id="override_image_tag" type="checkbox" name="siteseo_options[fb_default_img]" '.(!empty($option_fb_defult_img) ? 'checked="yes"' : 'value="1"').' >'.esc_html('Override every og:image tag with this default image (except if a custom og:image has already been set from the SEO metabox)','siteseo').'
                            </label>
                        </div>
                       <br /><div class="siteseo-notice is-warning"><p>Please define a <strong>default OG Image</strong> from the field above<p></div>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto">Link Ownership ID	</th>
                    <td>
                        <input type="text" placeholder="123456789" name="siteseo_options[fb_owership_id]" value="'.esc_html($option_fb_ownership).'">
                        <p class="description">One or more Facebook Page IDs that are associated with a URL in order to enable link editing and instant article publishing.</p>
                        <div class="siteseo-styles pre"><pre>' . esc_html('<meta property="fb:pages" content="page ID"/>') . '</pre></div>
                    </td>
                </tr>

                <tr>
                    <th scope="row" style="user-select:auto">Admin ID</th>
                    <td>
                        <input type="text" "placeholder="123456789" name="siteseo_options[fb_admin_id]" value="'. esc_html($option_fb_admin_id).'">
                        <p class="description">The ID (or comma-separated list for properties that can accept multiple IDs) of an app, person using the app, or Page Graph API object.</p>
                        <div class="siteseo-styles pre"><pre>' . esc_html('<meta property="fb:admins" content="admins ID"/>') . '</pre></div>
                    </td>
                </tr>

            </tbody>
        </table><input type="hidden" name="siteseo_options[facebook_tab]" value="1">';

    }

    static function save_settings(){

        global $siteseo;
		
		check_admin_referer('siteseo_social_settings');

		if(!current_user_can('manage_options') || !is_admin()){
			return;
		}
 
		$options = [];
       
		if(empty($_POST['siteseo_options'])){
			return;
		}
		
		if(isset($_POST['siteseo_options']['knowledge_graph_tab'])){
			
			$options['social_knowledge_type'] = isset($_POST['siteseo_options']['org_type']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_type'])) : '';
			
			$options['social_knowledge_name'] = isset($_POST['siteseo_options']['org_name']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_name'])) : '';
			
			$options['social_knowledge_img'] = isset($_POST['siteseo_options']['org_logo']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_logo'])) : '';
			
			$options['social_knowledge_phone'] = isset($_POST['siteseo_options']['org_contact_number']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_contact_number'])) : '';
			
			$options['social_knowledge_contact_type'] = isset($_POST['siteseo_options']['org_contact_type']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_contact_type'])) : '';
			
			$options['social_knowledge_contact_option'] = isset($_POST['siteseo_options']['org_contact_option']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['org_contact_option'])) : '';
		
		}

        if(isset($_POST['siteseo_options']['social_account_tab'])){
        
            $options['social_accounts_facebook'] = isset($_POST['siteseo_options']['facebook']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['facebook'])) : '';
        
            $options['social_accounts_twitter'] = isset($_POST['siteseo_options']['twitter']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['twitter'])) : '';

            $options['social_accounts_instagram'] = isset($_POST['siteseo_options']['instagram']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['instagram'])) : '';

            $options['social_accounts_youtube'] = isset($_POST['siteseo_options']['youtube']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['youtube'])) : '';

            $options['social_accounts_pinterest'] = isset($_POST['siteseo_options']['pinterest']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['pinterest'])) : '';
            
        }
		
		if(isset($_POST['siteseo_options']['facebook_tab'])){
			
			$options['social_facebook_og'] = isset($_POST['siteseo_options']['enable_fb_og']);
			
			$options['social_facebook_img'] = isset($_POST['siteseo_options']['fb_image']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['fb_image'])) : '';
			
			$options['social_facebook_img_default'] = isset($_POST['siteseo_options']['fb_default_img']);
			
			$options['social_facebook_link_ownership_id'] = isset($_POST['siteseo_options']['fb_owership_id']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['fb_owership_id'])) : '';
			
			$options['social_facebook_admin_id'] = isset($_POST['siteseo_options']['fb_admin_id']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['fb_admin_id'])) : '';
			
		}

        if(isset($_POST['siteseo_options']['twitter_tab'])){
			
            $options['social_twitter_card'] = isset($_POST['siteseo_options']['enable_twitter_card']);

            $options['social_twitter_card_og'] = isset($_POST['siteseo_options']['card_og']);
			
			$options['social_twitter_card_img'] = isset($_POST['siteseo_options']['twitter_img']) ? sanitize_url(wp_unslash($_POST['siteseo_options']['twitter_img'])) : '';

            $options['social_twitter_card_img_size'] = isset($_POST['siteseo_options']['image_size']) ? sanitize_text_field(wp_unslash($_POST['siteseo_options']['image_size'])) : '';

        }
        
        update_option('siteseo_social_option_name' , $options);
    }

}