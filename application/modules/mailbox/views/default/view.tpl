{include file="header.tpl"}
{strip}
<div class="content-block mailbox">
	<h1>{seotag tag='header_text'}</h1>
	{assign var='folder' value=$message.folder}
	<div id="mailbox_content">
		{/strip}
		{include file="mailbox_menu.tpl" module="mailbox" theme="default"}
		{strip}
		<div class="tab-submenu">
			<div class="fleft delim-space">
				<ul>
					{if $folder == 'inbox'}
						{if $message.sender.id>0}
							<li><s id="reply_message" class="icon-reply icon-big edge hover" title="{l i='link_message_reply' gid='mailbox' type='button'}"></s></li>
						{/if}
						<li><s id="message_mark_spam" class="icon-exclamation icon-big edge hover zoom30" title="{l i='link_message_mark_spam' gid='mailbox' type='button'}"></s></li>
						{if $message.sender.id > 0}
							{*block name='add_blacklist_button' module='users_lists' id_user=$message.id_from_user*}
							{block name='add_blacklist_button' module='blacklist' id_user=$message.id_from_user}
						{/if}
					{/if}
					{if $folder == 'drafts'}<li><s id="edit_message" class="icon-envelope icon-big edge hover" title="{l i='link_message_edit' gid='mailbox' type='button'}"></s></li>{/if}
					{if $folder == 'spam'}<li><s id="message_unmark_spam" class="icon-exclamation icon-big edge hover zoom30" title="{l i='link_message_unmark_spam' gid='mailbox' type='button'}"><del></del></s></li>{/if}
					{if $folder == 'trash'}<li><s id="message_untrash" class="icon-trash icon-big edge hover zoom30" title="{l i='link_message_untrash' gid='mailbox' type='button'}"><del></del></s></li>{/if}
					{if $folder != 'trash'}<li><s id="message_delete" class="icon-trash icon-big edge hover zoom30" title="{l i='link_message_delete' gid='mailbox' type='button'}"></s></li>{/if}
				</ul>
			</div>
			<div class="fright">
				{if $message.id_thread && $message.folder ne 'trash'}<a href="javascipt:void(0);" id="delete_thread">{l i='link_delete_thread' gid='mailbox'}</a>{/if}
			</div>
		</div>
		{if $thread_top_count}
		{capture assign='thread_top_block'}
			{foreach item=item from=$thread_top}
			{math equation='x-y' x=$thread_top_count y=1 assign='thread_top_count'}
			<li>
				<a id="btn_toggle_message_{$item.id}" class="btn-toggle-message"><i class="icon-caret-right g"></i></a>
				<div class="table-div">
					<dl>
						<dt>{l i='text_sender' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$item.sender}">{$item.sender.output_name}</a> {l i='text_recipient' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$item.recipient}">{$item.recipient.output_name}</a></dt>
						<dt class="righted">{$item.date_add|date_format:$page_data.date_time_format}</dt>
					</dl>
				</div>
				<div class="teaser">{if $item.is_new && $read_disabled}{l i='error_not_access' gid='mailbox'}{else}{$item.message}{/if}</div>
			</li>
			{/foreach}
		{/capture}
		
		<ul class="thread">			
			{if $thread_top_count}
			{l i='link_next_messages' gid='mailbox' assign='link_next_messages'}
			<li class="center"><a href="javascript:void(0);" class="btn_thread" data-id="{$message.id}" data-dir="next" data-page="1">{$link_next_messages|replace:'[messages]':$thread_top_count}</a></li>
			{/if}
			{$thread_top_block}
		</ul>
		{/if}
		
		<input type="checkbox" name="select_message{$message.id}]" value="{$message.id}" data-id-msg="{$message.id}" data-role="msg-checkbox" class="hide" checked>
		
		<h2>{if $message.subject}{$message.subject}{else}{l i='text_default_subject' gid='mailbox'}{/if}</h2>
		<div class="addressbar">
			<div class="fleft">{if $message.id_from_user eq $user_session_data.user_id}{l i='text_recipient' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$message.recipient}">{$message.recipient.output_name}</a>{else}{l i='text_sender' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$message.sender}">{$message.sender.output_name}</a>{/if}</div>
			<div class="fright">{$message.date_add|date_format:$page_data.date_time_format}</div>
			<div class="clr"></div>
		</div>
		
		{if $message.attaches_count}
			<div class="attachbox">
				<i class="ib w30 fleft icon-paperclip g icon-2x"></i>
				<ul>
					{foreach item=item from=$message.attaches}
					<li><a href="{$item.link}" target="_blank">{$item.filename}</a><br>{$item.size}</li>
					{/foreach}
				</ul>
				<div class="clr"></div>
			</div>
		{/if}
		
		<div class="view">{$message.message}</div>
		
		{if $thread_bottom_count}
		{capture assign='thread_bottom_block'}
			{foreach item=item from=$thread_bottom}
			{math equation='x-y' x=$thread_bottom_count y=1 assign='thread_bottom_count'}
			<li>
				<a id="btn_toggle_message_{$item.id}" class="btn-toggle-message" data-id="{$item.id}"><i class="icon-caret-right g"></i></a>
				<div class="table-div">
					<dl>
						<dt>{l i='text_sender' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$item.sender}">{$item.sender.output_name}</a> {l i='text_recipient' gid='mailbox'}: <a href="{seolink module='users' method='view' data=$item.recipient}">{$item.recipient.output_name}</a></dt>
						<dt class="righted">{$item.date_add|date_format:$page_data.date_time_format}</dt>
					</dl>
				</div>
				<div class="teaser">{if $item.is_new && $read_disabled}{l i='error_not_access' gid='mailbox'}{else}{$item.message}{/if}</div>
			</li>
			{/foreach}
		{/capture}
		<ul class="thread">
			{$thread_bottom_block}
			{if $thread_bottom_count}
			{l i='link_prev_messages' gid='mailbox' assign='link_prev_messages'}
			<li class="center"><a href="javascript:void(0);" class="btn_thread" data-id="{$message.id}" data-dir="prev" data-page="1">{$link_prev_messages|replace:'[messages]':$thread_bottom_count}</a></li>
			{/if}
		</ul>
		{/if}
		
		{if $folder eq 'inbox' && $message.sender.id>0}
		{assign var='is_reply' value='1'}
		{assign var='type' value='short'}
		{assign var='temp' value=$message}
		{assign var='message' value=$reply}
		<div id="reply_form">
			<h2>{l i='header_message_reply' gid='mailbox'}</h2>
			{include file="write_form.tpl" module="mailbox" theme="default"}
		</div>
		{assign var='message' value=$temp}
		{else}
		
		<div class="b outside">
			<a href="{seolink module='mailbox' method=$folder}" class="btn-link"><i class="icon-arrow-left icon-big edge hover"></i><i>{l i='link_back_to_'+$folder gid='mailbox'}</i></a>
		</div>
		<div class="clr"></div>
		{/if}
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
						success_request: function(message){mb.save_reply(function(){mb.reply_message()}, true)},
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
						messageId: {/literal}{$message.id}{literal},
						sendAvailableView: send_message_available_view,
					});
				},
				['mb'],
				{async: false}
			);
		});
</script>{/literal}
{include file="footer.tpl"}
