{include file="mailbox_menu.tpl" module="mailbox" theme="default"}
{include file="mailbox_top_panel.tpl" module="mailbox" theme=user}

{strip}
{if $messages}
	<div class="sorter short-line" id="sorter_block">
		<div class="fright">{pagination data=$page_data type='cute'}</div>
	</div>
{/if}
<div class="mailbox-list table-div wp100 list">
	{foreach item=item from=$messages}
		<dl class="pointer{if ($folder == 'inbox' || $folder == 'spam') && $item.is_new} bold{/if} btn_{if $folder eq 'drafts'}edit{else}read{/if}_message" data-id="{$item.id}">
			<dt class="w30"><input type="checkbox" name="select_message[{$item.id}]" data-role="msg-checkbox" data-id-msg="{$item.id}" value="{$item.id}" /></dt>
			<dt class="text-overflow w150 user">{if $folder == 'inbox' || $folder == 'spam'}{$item.sender.output_name|truncate:150}{elseif !$item.recipient}{l i='no_user' gid='mailbox'}{else}{$item.recipient.output_name|truncate:150}{/if}</dt>
			<dt class="icons">
				{if $item.attaches_count}<i class="icon-medium icon-paperclip{if !$item.is_new} g{/if}"></i>&nbsp;{/if}
				{if $folder == 'inbox' || $folder == 'spam'}<i class="icon-medium {if $item.is_new}icon-envelope{else}icon-envelope-alt g{/if}"></i>{/if}
			</dt>
			<dt><div class="text-overflow user">{if $item.subject}{$item.subject|truncate:100}{else}{l i='text_default_subject' gid='mailbox'}{/if}</div></dt>
			<dt class="righted w200">{$item.date_add|date_format:$page_data.date_time_format}</dt>
		</dl>
	{foreachelse}
		<div class="line top empty center">{l i='no_messages' gid='mailbox'}</div>
	{/foreach}
</div>

{if $messages}<div>{pagination data=$page_data type='full'}</div>{/if}
{/strip}

