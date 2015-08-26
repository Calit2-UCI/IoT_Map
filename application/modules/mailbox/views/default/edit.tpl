{include file="header.tpl"}
{strip}
<div class="content-block mailbox">
	<h1>{seotag tag='header_text'}</h1>
	{assign var='folder' value=$message.folder}
	<div id="mailbox_content" class="edit_block">
		{include file="mailbox_menu.tpl" module="mailbox" theme="default"}
		{strip}
		<div class="tab-submenu">
			<div class="fleft">
				<ul>
					<li><s id="save_message" class="icon-save icon-big edge hover zoom20" title="{l i='link_message_save' gid='mailbox' type='button'}"></s></li>
					<li><s id="message_delete" class="icon-trash icon-big edge hover zoom30" title="{l i='link_message_delete' gid='mailbox' type='button'}"></s></li>
				</ul>
			</div>
			<div class="fright">
				<span id="save_status" class="hide">{l i='text_message_saved' gid='mailbox'}</a>
			</div>
		</div>
		{/strip}
		
		<h2>{l i='header_message_edit' gid='mailbox'}</h2>
		{include file="write_form.tpl" module="mailbox" theme="default"}
		
	</div>
</div>
<div class="clr"></div>
{/strip}
<script type="text/javascript">{literal}
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
					success_request: function(message){mb.save_message(function(){mb.send_message()}, true)},
					fail_request: function(message){error_object.show_error_block(message, 'error');},
				});
			},
			['send_message_available_view'],
			{async: false}
		);
		
		loadScripts(
			"{/literal}{js file='mailbox.js' module='mailbox' return='path'}{literal}", 
			function(){
				mb = new mailbox({
					siteUrl: site_url,
					folder: '{/literal}{$folder}{literal}',
					sendAvailableView: send_message_available_view,
					writeMessage: true,
					messageId: {/literal}{$message.id}{literal},
				});
			},
			['mb'],
			{async: false}
		);
	});
</script>{/literal}
{include file="footer.tpl"}
