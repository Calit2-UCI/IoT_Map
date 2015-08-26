{include file="header.tpl"}
<div class="edit-form n150">
	<div class="row header">{l i='admin_header_messages_edit' gid='mailbox'}</div>
	<div class="row">
		<div class="h">{l i='field_subject' gid='mailbox'}: </div>
		<div class="v">{$message.subject}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_sender' gid='mailbox'}: </div>
		<div class="v">{$message.sender.output_name}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_recipient' gid='mailbox'}: </div>
		<div class="v">{$message.recipient.output_name}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_message' gid='mailbox'}: </div>
		<div class="v">{$message.message}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_attaches' gid='mailbox'}: </div>
		<div class="v">
			{foreach item=item from=$message.attaches}
			<a href="">{$item.filename}</a> ({$item.filesize div 1024} {l i='text_size_kb' gid='mailbox'})
			{/foreach}
		</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_date_add' gid='mailbox'}: </div>
		<div class="v">{$message.date_add|date_format:$date_format}</div>
	</div>
</div>
<div class="btn"><div class="l"><a href="{$site_url}admin/mailbox/delete/{$message.id}/{$message.folder}">{l i='btn_delete' gid='start'}</a></div></div>
<a class="cancel" href="{$site_url}admin/mailbox/index/{$message.folder}">{l i='btn_cancel' gid='start'}</a>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>
{include file="footer.tpl"}
