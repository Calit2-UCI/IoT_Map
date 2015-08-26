{include file="header.tpl"}
{strip}
<div class="content-block mailbox">
	<h1>{seotag tag='header_text'}</h1>
	<div id="mailbox_content">{$content}</div>
</div>
<div class="clr"></div>
{/strip}
<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='available_view.js' return='path'}{literal}", 
			function(){
				read_message_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: 'mailbox/ajax_available_read_message/',
					buyAbilityAjaxUrl: 'mailbox/ajax_activate_read_message/',
					buyAbilityFormId: 'ability_form',
					buyAbilitySubmitId: 'ability_form_submit',
					success_request: function(message){mb.read_message();},
					fail_request: function(message){error_object.show_error_block(message, 'error');},
				});
			},
			['read_message_available_view'],
			{async: false}
		);
		loadScripts(
			"{/literal}{js file='mailbox.js' module='mailbox' return='path'}{literal}", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					folder: '{/literal}{$folder}{literal}',
					readAvailableView: read_message_available_view,
				});
			},
			['mb'],
			{async: false}
		);
	});
</script>{/literal}
{include file="footer.tpl"}
