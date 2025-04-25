jQuery(document).ready(function($){
    
	$('#btn-site-title').click(function(e){
		e.preventDefault();
		var inputField = $('input[name="siteseo_options[site_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue + ' %%sitetitle%%');
		
		inputField.focus();
		
	});

	$('#btn-separator').click(function(e){
		e.preventDefault();

		var inputField = $('input[name="siteseo_options[site_title]"]');
		
		var currentValue = inputField.val();
		inputField.val(currentValue +'%%sep%%');
		inputField.focus();

	});

	$('#btn-tagline').click(function(e){
		e.preventDefault();

		var inputField = $('input[name="siteseo_options[site_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue + '%%tagline%%');
		inputField.focus();
	});

	$('#btn-tagline-meta').click(function(e){
        e.preventDefault();

        var inputField = $('textarea[name="siteseo_options[media_desc]"]');

        var currentValue = inputField.val();
        inputField.val(currentValue + ' %%tag_description%%').focus();
    });


	$('#btn-post-title').click(function(e){
		e.preventDefault();

		var inputField = $('input[name="siteseo_options[post_title]"]');

		var currentValue =inputField.val();
		inputField.val(currentValue + '%%post_title%%');
		inputField.focus();
	});

	$('#btn-post-separator').click(function(e){
		e.preventDefault();

		var inputField = $('input[name="siteseo_options[post_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue + '%%sep%%');
		inputField.focus();
	});

	
	$('#btn-post-site-title').click(function(e){
		e.preventDefault();

		var inputField = $('input[name="siteseo_options[post_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue + '%%sitetitle%%');
		inputField.focus();
	});


	$('#btn-posts-meta').click(function(e){
		e.preventDefault();
		var inputField = $('textarea[name="siteseo_options[post_desc]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%post_excerpt%%');
		inputField.focus();
	});


	$('#btn-page-title').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[page_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%post_title%%');
		inputField.focus();
	});


	$('#btn-page-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[page_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});


	$('#btn-page-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[page_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});


	$('#btn-page-meta').click(function(e){
		e.preventDefault();
		
		var inputField = $('textarea[name="siteseo_options[page_desc]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%post_excerpt%%');
		inputField.focus();
	});

	$('#btn-author-acrhive-title').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[author_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%post_author%%');
		inputField.focus();
	});

	$('#btn-author-acrhive-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[author_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});

	$('#btn-author-acrhive-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[author_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});

	
	$('#btn-date-archive').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[date_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%archive_date%%');
		inputField.focus();
	});

	$('#btn-date-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[date_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});


	$('#btn-date-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[date_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});

	$('#btn-search-keyword').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[search_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%search_keywords%%');
		inputField.focus();
	});

	$('#btn-search-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[search_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});

	$('#btn-search-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[search_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});


	$('#btn-404-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[title_404]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});


	$('#btn-404-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[title_404]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});


	
	$('#btn-cate-title').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[category_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%_category_title%%');
		inputField.focus();
	});


	$('#btn-cate-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[category_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});

	$('#btn-cate-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[category_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});


	$('#btn-cate-meta').click(function(e){
		e.preventDefault();
		
		var inputField = $('textarea[name="siteseo_options[category_desc]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%_category_description%%');
		inputField.focus();
	});

	$('#btn-tags-separator').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[tags_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sep%%');
		inputField.focus();
	});


	$('#btn-tags-sitetitle').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[tags_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%sitetitle%%');
		inputField.focus();
	});

	$('#btn-tags-title').click(function(e){
		e.preventDefault();
		
		var inputField = $('input[name="siteseo_options[tags_title]"]');

		var currentValue = inputField.val();
		inputField.val(currentValue+'%%tag_title%%');
		inputField.focus();
	});

	$('.siteseo-container a').on('click', function(e){
        e.preventDefault(); 
        $('.siteseo-container a').removeClass('active');
        $(this).addClass('active');
    });
	
	
	$('#siteseo-toggleSwitch-posts').click(function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$('#toggle_state_posts').text(' Click to hide any SEO metaboxes / columns for this post type');
			$('#enable_post_toggle').val('1');
		} else{
			$('#toggle_state_posts').text(' Click to show any SEO metaboxes / columns for this post type');
			$('#enable_post_toggle').val('0');
		}
	});
	
	
	$('#siteseo-toggleSwitch-pages').click(function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$('#toggle_state_pages').text(' Click to hide any SEO metaboxes / columns for this post type');
			$('#enable_toggle_page').val('1');
		} else{
			$('#toggle_state_pages').text(' Click to show any SEO metaboxes / columns for this post type');
			$('#enable_toggle_page').val('0');
		}
	});
		
	$('#siteseo-toggleSwitch-category').click(function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$('#toggle_state_category').text('Disable');
			$('#enable_category').val('1');
		} else{
			$('#toggle_state_category').text('Enable');
			$('#enable_category').val('0');
		}
	});
	
	
	$('#siteseo-toggleSwitch-post-tags').click(function(){
		$(this).toggleClass('active');
		if($(this).hasClass('active')){
			$('#toggle_state_posts_tags').text('Disable');
			$('#enable_posts_tag_toggle').val('1');
		} else{
			$('#toggle_state_posts_tags').text('Enable');
			$('#enable_posts_tag_toggle').val('0');
		}
	});
	
	// Generalized toggle handler function
	function handleToggle($toggle, toggleKey, action) {
		const $container = $toggle.closest('.siteseo-toggle-container');
		const $stateText = $container.find(`.toggle_state_${toggleKey}`);
		const $input = $(`#${toggleKey}`);

		$container.addClass('loading');
		$toggle.toggleClass('active');

		const newValue = $toggle.hasClass('active') ? '1' : '0';
		$input.val(newValue);
		$stateText.text($toggle.hasClass('active') ? 'Disable' : 'Enable');

		$.ajax({
			url: ajaxurl,
			type: 'POST',
			data: {
				action: action,
				toggle_value: newValue,
				nonce: $toggle.data('nonce')
			},
			success: function(response) {
				if (response.success) {
					console.log('Toggle saved successfully');
				} else {
					console.error('Failed to save toggle state');
					toggleRollback($toggle, $input, $stateText);
				}
			},
			error: function() {
				console.error('Ajax request failed');
				toggleRollback($toggle, $input, $stateText);
			},
			complete: function() {
				$container.removeClass('loading');
			}
		});
	}

	// Rollback function in case of AJAX error
	function toggleRollback($toggle, $input, $stateText) {
		$toggle.toggleClass('active');
		$input.val($toggle.hasClass('active') ? '1' : '0');
		$stateText.text($toggle.hasClass('active') ? 'Disable' : 'Enable');
	}

	// Event handler for all toggle buttons

	$('.siteseo-toggle-switch').on('click', function() {
		const $toggle = $(this);
		const toggleKey = $toggle.data('toggle-key');  // e.g., 'titles_meta_toggle', 'sitemap_toggle'
		const action = $toggle.data('action');  // e.g., 'save_titles_meta_toggle', 'save_sitemap_toggle'

		handleToggle($toggle, toggleKey, action);
	});


	
	
	// default off
	$('.siteseo-suggetion').hide();

    $('.tag-select-btn').click(function(e){
        e.preventDefault();
        e.stopPropagation();
		
        $('.siteseo-suggetion').not($(this).siblings('.siteseo-suggetion')).hide();
        
        $(this).siblings('.siteseo-suggetion').toggle();
    });

    $('.suggestions-container .section').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        
        var tag = $(this).find('.tag').text();
        var targetField = $(this).closest('.siteseo-suggetion').closest('.wrap-tags').prev('input[type="text"], textarea');
        
        insertAtCursor(targetField, tag);
        
        $(this).closest('.siteseo-suggetion').hide();
    });

    $(document).click(function(e){
        if (!$(e.target).closest('.wrap-tags').length){
            $('.siteseo-suggetion').hide();
        }
    });

    $('.search-box').on('input', function(){
        var searchText = $(this).val().toLowerCase();
        $(this).closest('.siteseo-suggetion').find('.section').each(function() {
            var sectionText = $(this).text().toLowerCase();
            $(this).toggle(sectionText.indexOf(searchText) > -1);
        });
    });

   function insertAtCursor(field, text){
		field = field[0];
		var scrollPos = field.scrollTop;
		var currentValue = field.value;

		//
		var newValue = currentValue + text;

		field.value = newValue;

		//end point
		var newPosition = newValue.length;
		field.setSelectionRange(newPosition, newPosition);

		field.scrollTop = scrollPos;
		field.focus();
	}
    
    $('.tag-title-btn').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var tag = '';
        var btnId = $(this).attr('id');
        if(btnId === 'btn-tagline-meta'){
            tag = '%%sitetitle%%'; // replace
        }
        
        if(tag){
            var targetField = $(this).closest('.wrap-tags').prev('input[type="text"], textarea');
            insertAtCursor(targetField, tag);
        }
    });
	
	// facebook upload Image
	$('#facebook_upload_logo').click(function(e){
		var mediaUploader;
		e.preventDefault();
		console.log('come here');
		if(mediaUploader){
			mediaUploader.open();
			return;
		}


		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Media',
			button:{
				text: 'Select'
			},
			multiple: false
		});


		mediaUploader.on('select', function(){
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#facebook_org_image_url').val(attachment.url);
		});

		mediaUploader.open();

	});
	
	//twitter cart image
	$('#twitter_logo').click(function(e){
		var mediaUploader;
		e.preventDefault();
		console.log('come here');
		if(mediaUploader){
			mediaUploader.open();
			return;
		}


		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Media',
			button:{
				text: 'Select'
			},
			multiple: false
		});


		mediaUploader.on('select', function(){
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#twitter_logo_url').val(attachment.url);
		});

		mediaUploader.open();

	});
	
	//knowledgen org
	$('#knowledge_org_logo').click(function(e){
		var mediaUploader;
		e.preventDefault();
		console.log('come here');
		if(mediaUploader){
			mediaUploader.open();
			return;
		}


		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Media',
			button:{
				text: 'Select'
			},
			multiple: false
		});


		mediaUploader.on('select', function(){
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#knowledge_org_logo_url').val(attachment.url);
		});

		mediaUploader.open();

	});


	function getDefaultTab(){
		return $('.siteseo-tab.active').attr('id') || 'tab_siteseo_home';
    }

    // save to local storage
    function setActiveTab(tabId){

        $('.nav-tab').removeClass('nav-tab-active');
        $('.siteseo-tab').removeClass('active');
        
        // Add active classes to selected tab
        $('[data-tab="' + tabId + '"]').addClass('nav-tab-active');
        $('#' + tabId).addClass('active');
        
        // save
        localStorage.setItem('siteseeo_active_tab', tabId);
    }

   
    var savedTab = localStorage.getItem('siteseeo_active_tab');
    var defaultTab = getDefaultTab();
    
    
    if(savedTab && $('#' + savedTab).length){
        setActiveTab(savedTab);
    } else{
        // other wise use default tab
        setActiveTab(defaultTab);
    }

    //click handle
    $('.nav-tab').on('click', function(e){
        e.preventDefault();
        var tabId = $(this).data('tab');
        setActiveTab(tabId);
    });
	

	//ajax htaccess file
	$('#siteseo_htaccess_btn').on('click', function(){
        event.preventDefault();
		
		let spinner = $(event.target).next('.spinner');

		if(spinner.length){
			spinner.addClass('is-active');
		}

        let htaccess_code = $('#siteseo_htaccess_file').val(),
        htaccess_enable = $('#siteseo_htaccess_enable').is(':checked') ? 1 : 0;

        $.ajax({
            url: siteseoAdminAjax.url,
            method: 'POST',
            data: {
                action: 'siteseo_update_htaccess',
                htaccess_code: htaccess_code,
                htaccess_enable: htaccess_enable,
                _ajax_nonce : siteseoAdminAjax.nonce
            },
            success: function(res){
				if(spinner.length){
					spinner.removeClass('is-active');
				}
				
				if(res.success){
					alert(res.data);
					return;
				}
				
				if(res.data){
					alert(res.data)
					return;
				}

				alert('Something went wrong, updating the file');
            }
        });
    });
	
	$('#siteseo-create-robots').on('click', function(){
		event.preventDefault();
		
		let spinner = $(event.target).next('.spinner');
		
		if(spinner.length){
			spinner.addClass('is-active');
		}

		$.ajax({
			method : 'POST',
			url : siteseoAdminAjax.url,
			data : {
				action : 'siteseo_create_robots',
				_ajax_nonce : siteseoAdminAjax.nonce
			},
			success: function(res){
				
				if(spinner.length){
					spinner.removeClass('is-active');
				}

				if(res.success){
					alert(res.data);
					window.location.reload();
					return;
				}

				alert('Unable to create the robots.txt file');
			}
		});
	});
	
	$('#siteseo-update-robots').on('click', function(){
		event.preventDefault();
		
		let spinner = $(event.target).next('.spinner');

		if(spinner.length){
			spinner.addClass('is-active');
		}

		$.ajax({
			method : 'POST',
			url : siteseoAdminAjax.url,
			data : {
				action : 'siteseo_update_robots',
				robots : $('#siteseo_robots_file_content').val(),
				_ajax_nonce : siteseoAdminAjax.nonce
			},
			success: function(res){
				
				if(spinner.length){
					spinner.removeClass('is-active');
				}

				if(res.success){
					alert(res.data);
					window.location.reload();
					return;
				}

				if(res.data){
					alert(res.data);
					return;
				}
				
				alert('Unable to create the robots.txt file');
			}
		});
	});
	
	
});