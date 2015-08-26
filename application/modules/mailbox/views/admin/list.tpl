{include file="header.tpl"}
<div class="actions">
	<ul></ul>
	&nbsp;
</div>

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_mailbox_menu'}
<form method="post" action="" name="save_form" enctype="multipart/form-data">
<div class="filter-form">
	<div class="form">
		<div class="row">
			<div class="h">{l i='field_user' gid='mailbox'}:</div>
			<div class="v">
				{user_select selected=$id_user max=1 var_name='id_user'}
			</div>
		</div>
		<div class="row">
			<div class="h">
				<input type="submit" name="filter-submit" value="{l i='header_filters_set' gid='mailbox' type='button'}">
				<input type="submit" name="filter-reset" value="{l i='header_filters_reset' gid='mailbox' type='button'}">
			</div>
		</div>
	</div>
</div>
</form>

<form id="mailbox_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w20"><input type="checkbox" id="grouping_all"></th>
		<th class="w100">{if $folder == 'inbox'}{l i='field_sender' gid='mailbox'}{else}{l i='field_recipient' gid='mailbox'}{/if}</th>
		<th class="w200">{l i='field_subject' gid='mailbox'}</th>
		<th class="w70">&nbsp;</th>
	</tr>
	{foreach item=item from=$messages}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>				
			<td class="center"><input type="checkbox" name="ids[]" class="grouping" value="{$item.id}"></td>
			<td>{if $folder == 'inbox'}{$item.sender.output_name|truncate:50}{else}{$item.recipient.output_name|truncate:50}{/if}</td>
			<td>{$item.subject|truncate:100}</td>
			<td class="icons">
				<a href="{$site_url}admin/mailbox/view/{$item.id}"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" border="0" alt="{l i='link_message_show' gid='mailbox' type='button'}" title="{l i='link_message_show' gid='mailbox' type='button'}"></a>
				<a href="{$site_url}admin/mailbox/delete/{$item.id}/{$folder}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_message_delete' gid='mailbox' type='button'}" title="{l i='link_message_delete' gid='mailbox' type='button'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="6" class="center">{l i='no_messages' gid='mailbox'}</td></tr>
	{/foreach}
</table>
</form>
{include file="pagination.tpl"}
<script>{literal}
var reload_link = "{/literal}{$site_url}admin/mailbox/index/{literal}";
var filter = '{/literal}{$filter}{literal}';
var order = '{/literal}{$order}{literal}';
var order_direction = '{/literal}{$order_direction}{literal}';
$(function(){
	$('#grouping_all').bind('click', function(){
		var checked = $(this).is(':checked');
		if(checked){
			$('input[type=checkbox].grouping').prop('checked', true);
		}else{
			$('input[type=checkbox].grouping').prop('checked', false);
		}
	});
});

function reload_this_page(value){
	var link = reload_link + value + '/' + filter + '/' + order + '/' + order_direction;
	location.href=link;
}
{/literal}</script>
{include file="footer.tpl"}
