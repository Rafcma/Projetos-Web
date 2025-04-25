jQuery(document).ready(function($){
	$('#siteseopro-pagespeed-results .siteseo-metabox-tab-label').click(function(){
		$('.siteseo-metabox-tab-label').removeClass('siteseo-metabox-tab-label-active');
		$('.siteseo-metabox-tab').hide();

		$(this).addClass('siteseo-metabox-tab-label-active');

		var activeTab = $(this).data('tab');
		$('.' + activeTab).show();
	});
	
	$('input[name="ps_device_type"]').on('change', function(){
		jEle = jQuery(this),
		val = jEle.val();
		
		if(val == 'mobile'){
			jQuery('#siteseo-ps-mobile').css('display', 'flex');
			jQuery('#siteseo-ps-mobile').find('.siteseo-metabox-tab-label:first-child').trigger('click');
			jQuery('#siteseo-ps-desktop').hide();
		} else {
			jQuery('#siteseo-ps-mobile').hide();
			jQuery('#siteseo-ps-desktop').css('display', 'flex');
			jQuery('#siteseo-ps-desktop').find('.siteseo-metabox-tab-label:first-child').trigger('click');
		}
		
	});

    $('#siteseopro-pagespeed-btn').on('click', function(){
		$('#siteseopro-pagespeed-results').empty();
    let spinner = $(this).next(),
		input = $(this).closest('div').find('input');

    spinner.addClass('is-active'),

		siteseo_pagespeed_request(input.val(), true);
		siteseo_pagespeed_request(input.val(), false);
    });

	$('#siteseopro-clear-Page-speed-insights').on('click', function(){
		$.ajax({
			url: siteseo_pro.ajax_url,
			type: 'POST',
			data: {
				action: 'siteseo_pro_pagespeed_insights_remove_results',
				nonce: siteseo_pro.nonce
			},
			success: function(response){
				$('#siteseopro-pagespeed-results').empty();
			}
		});

	});

	$('.siteseo-audit-title').next('.description').hide();

    $('.siteseo-audit-title').on('click', function(e){
        var description = $(this).next('.description');
        var icon = $(this).find(".toggle-icon");

        if(description.is(':visible')){
			description.hide();
			icon.addClass('class', 'toggle-icon dashicons dashicons-arrow-up-alt2');
        } else {
			description.show();
			icon.addClass('class', 'toggle-icon dashicons dashicons-arrow-down-alt2');
        }
    });
	
	//Woocommmerse
	$('#siteseo-toggleSwitch-woocommerce').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-woocommerce').text('Click to disable this feature');
			$('#toggle_state_woocommerce').val('1');
		} else {
			$('#siteseo-toggle-label-woocommerce').text('Click to enable this feature');
			$('#toggle_state_woocommerce').val('0');
		}
	});

	// Easy Digital Downloads Tab Toggle
	$('#siteseo-toggleSwitch-edd').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-edd').text('Click to disable this feature');
			$('#toggle_state_easy_digital').val('1');
		} else {
			$('#siteseo-toggle-label-edd').text('Click to enable this feature');
			$('#toggle_state_easy_digital').val('0');
		}
	});

	// PageSpeed Insights Tab Toggle
	$('#siteseo-toggleSwitch-pagespeed').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-pagespeed').text('Click to disable this feature');
			$('#toggle_page_speed_tab').val('1');
		} else {
			$('#siteseo-toggle-label-pagespeed').text('Click to enable this feature');
			$('#toggle_page_speed_tab').val('0');
		}
	});

	// Dublin Core Tab Toggle
	$('#siteseo-toggleSwitch-dublin').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-dublin').text('Click to disable this feature');
			$('#toggle_state_dublin_core').val('1');
		} else {
			$('#siteseo-toggle-label-dublin').text('Click to enable this feature');
			$('#toggle_state_dublin_core').val('0');
		}
	});    

	// local business Tab Toggle
	$('#siteseo-toggleSwitch-local').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-local').text('Click to disable this feature');
			$('#toggle_state_local_buz').val('1');
		} else {
			$('#siteseo-toggle-label-local').text('Click to enable this feature');
			$('#toggle_state_local_buz').val('0');
		}
	});

	// structural data tab toggle
	$('#siteseo-toggleSwitch-structured-data').click(function() {
		$(this).toggleClass('active');
		if ($(this).hasClass('active')) {
			$('#siteseo-toggle-label-stru-data').text('Click to disable this feature');
			$('#toggle_state_stru_data').val('1');
		} else {
			$('#siteseo-toggle-label-stru-data').text('Click to enable this feature');
			$('#toggle_state_stru_data').val('0');
		}
	});

	// media uploader for image logo 
	$('#siteseopro_structured_data_upload_img').click(function(e) {
		var mediaUploader;
		e.preventDefault();
		
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}

		
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Media',
			button: {
				text: 'Select'
			},
			multiple: false
		});

		
		mediaUploader.on('select', function() {
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#structured_data_image_url').val(attachment.url);
		});
		
		mediaUploader.open();

	});
	
});

async function siteseo_pagespeed_request(url, is_mobile = false){
	jQuery.ajax({
		url: siteseo_pro.ajax_url,
		type: 'POST',
		data: {
			action: 'siteseo_pro_get_pagespeed_insights',
			is_mobile : is_mobile,
			test_url : url,
			nonce: siteseo_pro.nonce
		},
		success: function(response){
			if(!response.success){
				alert(response.data ?? 'Something went wrong');
				return;
			}

			if(siteseo_pro.pagespeed_response){
				//spinner.removeClass('is-active');
				location.reload(true);
				return;
			}

			siteseo_pro['pagespeed_response'] = true;
		}
	});	
}