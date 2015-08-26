{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_shoutbox_menu'}
<div class="actions">
	<ul>
		{if $messages}
			<li>
				<div class="l">
					<a href="{$site_url}admin/shoutbox/messages_delete/" class="subscribe" id="delete_selected">
						{l i='link_delete_selected' gid='shoutbox'}
					</a>
				</div>
			</li>
		{/if}
	</ul>&nbsp;
</div>

<form id="messages_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><input type="checkbox" id="grouping_all"></th>
		<th class="w300"><a href="{$sort_links.message}"{if $order eq 'message'} class="{$order_direction|lower}"{/if}>{l i='field_message' gid='shoutbox'}</a></th>
		<th>{l i='field_author' gid='shoutbox'}</th>
		<th><a href="{$sort_links.date_created}"{if $order eq 'date_created'} class="{$order_direction|lower}"{/if}>{l i='field_date_created' gid='shoutbox'}</a></th>
		<th class="w30">&nbsp;</th>
	</tr>
	{foreach from=$messages item=message}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first w20 center"><input type="checkbox" class="grouping" value="{$message.id}" name="ids[]"></td>
			<td>{$message.message}</td>
			<td class="center">
				{if $message.user_info.nickname}
					{$message.user_info.nickname} {if $message.user_info.output_name ne ''}({$message.user_info.output_name}){/if}
				{else}
					<font class="error">{$message.user_info.output_name}</font>
				{/if}
			</td>
			<td class="center">
				{$message.date_created}
			</td>
			<td class="w50 icons">
				{block name='contact_user_link' module='tickets' id_user=$message.user_info.id}
				<a href="{$site_url}admin/shoutbox/delete/{$message.id}" onclick="javascript: if(!confirm('{l i='note_delete_message' gid='shoutbox' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_message' gid='shoutbox'}" title="{l i='link_delete_message' gid='shoutbox'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="5" class="center">{l i='no_messages' gid='shoutbox'}</td></tr>
	{/foreach}
</table>
</form>
{include file="pagination.tpl"}


<script type="text/javascript">
{literal}
$(function(){
	$('#grouping_all').bind('click', function(){
		var checked = $(this).is(':checked');
		if(checked){
			$('input.grouping').prop('checked', true);
		}else{
			$('input.grouping').prop('checked', false);
		}
	});
	$('#delete_selected').bind('click', function(){
		if(!$('input[type=checkbox].grouping').is(':checked')) return false; 
		if(!confirm('{/literal}{l i='note_alerts_delete_all' gid='shoutbox' type='js'}{literal}')) return false;
		$('#messages_form').attr('action', $(this).attr('href')).submit();		
		return false;
	});
});
{/literal}</script>
{include file="footer.tpl"}
