{strip}
{block name='users_carousel' module='users' users=$helper_featured_users_data.users scroll='auto' class='small' thumb_name='middle'}
{if $helper_featured_users_data.buy_ability}
	<script>{literal}
		$(function(){
			loadScripts(				 
				["{/literal}{js file='available_view.js' return='path'}{literal}","{/literal}{js file='users-avatar.js' module='users' return='path'}{literal}"],
				function(){
					users_featured_available_view = new available_view({
						siteUrl: site_url,
						checkAvailableAjaxUrl: 'users/ajax_available_users_featured/',
						buyAbilityAjaxUrl: 'users/ajax_activate_users_featured/',
						buyAbilityFormId: 'ability_form',
						buyAbilitySubmitId: 'ability_form_submit',
						formType: 'list',
						success_request: function(message) {
							error_object.show_error_block(message, 'success');
							locationHref('');
						},
						fail_request: function(message) {error_object.show_error_block(message, 'error');},
					});
					var rand = '{/literal}{$helper_featured_users_data.rand}{literal}';
					var user_logo = '{/literal}{$helper_featured_users_data.users.0.user_logo}{literal}';
					user_avatar = new avatar({
						site_url: site_url,
						id_user: {/literal}{$helper_featured_users_data.user_id}{literal},
						photo_id: 'featured_add_'+rand,
					});
					$('#featured_add_'+rand).off('click').on('click', function(e){
						if(user_logo == ''){
							user_avatar.load_avatar();
						}else{
							users_featured_available_view.check_available();
						}
						return false;
					});
				},
				['users_featured_available_view'],
				['user_avatar'],
				{async: false}
			);
			
		});
	</script>{/literal}
{/if}
{/strip}