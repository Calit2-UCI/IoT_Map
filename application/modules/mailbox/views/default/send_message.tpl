<div class="content-block load_content" id="mailbox_content">
	<h1>{l i='header_message_write' gid='mailbox'}</h1>
	<div class="inside edit_block">
		{include file="write_form.tpl" module="mailbox" theme="default"}
	</div>
</div>
<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='available_view.js' return='path'}{literal}", 
			function(){
				send_message_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: 'mailbox/ajax_available_send_message/',
					buyAbilityAjaxUrl: 'mailbox/ajax_activate_send_message/',
					buyAbilityFormId: 'ability_form',
					buyAbilitySubmitId: 'ability_form_submit',
					success_request: function(message){mb_content.save_message(function(){mb_content.send_message()}, true)},
					fail_request: function(message){error_object.show_error_block(message, 'error')},
				});
			},
			['send_message_available_view'],
			{async: false}
		);
		
		loadScripts(
			"{/literal}{js file='mailbox.js' module='mailbox' return='path'}{literal}", 
			function(){
				mb_content = new mailbox({
					siteUrl: site_url,
					accessAvailableView: access_available_view,
					sendAvailableView: send_message_available_view,
					writeMessage: true,
				});
			},
			['mb_content'],
			{async: false}
		);
	});
</script>{/literal}
