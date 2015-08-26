<a id="btn_write_message_{$message_button_rand}" href="javascript:void(0);" class="link-r-margin" title="{l i='link_message_send' gid='mailbox' type='button'}"><i class="fa-envelope icon-big edge hover"></i></a>
<script>{literal}
	var access_available_view;
	$(function(){
		loadScripts(
			"{/literal}{js file='available_view.js' return='path'}{literal}", 
			function(){
				access_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: 'mailbox/ajax_available_access_mailbox/',
					buyAbilityAjaxUrl: 'mailbox/ajax_activate_send_message/',
					buyAbilityFormId: 'ability_form',
					buyAbilitySubmitId: 'ability_form_submit',
					success_request: function(message){mb.write_message({/literal}{$user_id}{literal}, 'short');},
					fail_request: function(message){error_object.show_error_block(message, 'error');},
				});
			},
			['access_message_available_view'],
			{async: false}
		);
		loadScripts(
			"{/literal}{js file='mailbox.js' module='mailbox' return='path'}{literal}", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					contactId: '',
					accessAvailableView: access_available_view,
					loadContent: true,
					writeMessageButton: '#btn_write_message_{/literal}{$message_button_rand}{literal}'
				});
			},
			['mb'],
			{async: false}
		);	
	});
{/literal}</script>
