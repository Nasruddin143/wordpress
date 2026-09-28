jQuery(function($) {
	var send_command = wp_optimize.send_command;
	var block_ui = wp_optimize.block_ui;
	
	/**
	 * Handle show avatars WordPress core setting on the Advanced settings tab.
	 */
	$('#wpo-show-avatars').on('change', function () {
		var show_avatars_setting = $(this);
		var show_avatars = show_avatars_setting.is(':checked');
		
		block_ui(wpoptimize.saving);
		
		send_command('change_show_avatars', {
			show_avatars: show_avatars
		}, function (response) {
			if (response.success) {
				$('.wpo-show-avatars-save-done')
				.stop(true, true)
				.css('display', '')
				.removeClass('display-none')
				.delay(5000)
				.fadeOut('fast', function() {
					$(this).addClass('display-none').css('display', '');
				});
				$.unblockUI();
				toggle_host_gravatars_locally_setting(show_avatars);
			} else {
				show_avatars_setting.prop('checked', !show_avatars);
				$.blockUI({
					message: response.message,
					onOverlayClick: $.unblockUI,
					baseZ: 160001,
					css: {
						width: '400px',
						padding: '20px',
						cursor: 'pointer'
					}
				});
			}
		});
	});
	
	// Handle host gravatar locally display settings
	function toggle_host_gravatars_locally_setting(show_avatars) {
		if (show_avatars) {
			$('#wpo-host-gravatars-locally-container').show();
		} else {
			$('#wpo-host-gravatars-locally').prop('checked', false);
			$('#wpo-host-gravatars-locally-container').hide();
		}
	}
	
	/**
	 * Handle delete from cache on the Advanced settings tab.
	 */
	$('.wpo-exclude-from-cache').on('click', function () {
		var btn = $(this),
			post_id = btn.data('id');

		send_command('change_post_disable_option', {
			post_id: post_id,
			meta_key: '_wpo_disable_caching',
			disable: 0
		}, function (response) {
			if (response.result) {
				var row = btn.closest('tr');
				row.fadeOut('fast', function () {
					if (!(row.prev().is('tr') || row.next().is('tr'))) {
						row.closest('table').remove();
					}
					row.remove();
				});
			}
		});
	});

	/**
	 * Check the response from save cache settings.
	 */
	$(document).on('validate_cloudflare_settings', function(event, response) {
		if (response && response.hasOwnProperty('cloudflare_error')) {
			$('.wpo-error__cloudflare-cache').removeClass('wpo_hidden').find('p').text(response.cloudflare_error);
		} else {
			$('.wpo-error__cloudflare-cache').addClass('wpo_hidden').find('p').text('');
		}
	});

	/**
	 * Handle change "Purge Cloudflare cached pages" checkbox state.
	 */
	$('#purge_cloudflare_cache').on('change', function() {
		var checkbox = $(this),
			cloudflare_credentials_div = $('#wpo_cloudflare_credentials');

		if (checkbox.prop('checked')) {
			cloudflare_credentials_div.show();
		} else {
			cloudflare_credentials_div.hide();
		}
	});

	/**
	 * Allow select only one element from group of elements with .wpo-select-group class.
	 */
	$('.wpo-select-group').on('change', function() {
		var current = $(this);
		if (current.hasClass('wpo-select-group-processing')) return;
		update_select_group_view(current);
	});

	if ($('.wpo-select-group:checked').length > 0) {
		update_select_group_view($('.wpo-select-group:checked').first());
	}

	/**
	 * Update .wpo-select-group elements state.
	 *
	 * @param {Object} current
	 */
	function update_select_group_view(current) {
		$('.wpo-select-group').each(function() {
			var el = $(this);
			if (current[0] != el[0]) {
				el.prop('checked', false)
				.addClass('wpo-select-group-processing')
				.trigger('change')
				.removeClass('wpo-select-group-processing')
				.prop('disabled', current.prop('checked'));
			}
		});
	}

	// Handle Cache Specific URLs Only enable/disable settings
	$('#cache_specific_urls_only').on('change', function() {
		if ($(this).is(':checked')) {
			$('#cache_include_urls').prop('disabled', false);
			$('#cache_exception_urls').prop('disabled', true);
		} else {
			$('#cache_include_urls').prop('disabled', true);
			$('#cache_exception_urls').prop('disabled', false);
		}
	});
});