{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_notifications_menu'}
<div class="actions">
	{if $allow_edit}
		<ul>
			<li><div class="l"><a href="{$site_url}admin/notifications/edit">{l i='link_add_notification' gid='notifications'}</a></div></li>
		</ul>
	{/if}
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		{*<th class="first"><a href="{$sort_links.gid'}"{if $order eq 'gid'} class="{$order_direction|lower}"{/if}>{l i='field_notification_gid' gid='notifications'}</a></th>*}
		<th class="first w100">{l i='field_notification_name' gid='notifications'}</th>
		<th class="w100">{l i='field_send_type' gid='notifications'}</th>
		<th class="w100"><a href="{$sort_links.date_add}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_date_add' gid='notifications'}</a></th>
		<th class="w50">&nbsp;</th>
	</tr>
	{foreach item=item from=$notifications}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			{*<td class="first center">{$item.gid}</td>*}
			<td>{l i=$item.name_i gid='notifications'}</td>
			<td class="center">{l i='field_send_type_'+$item.send_type gid='notifications'}</td>
			<td class="center">{$item.date_add|date_format:$page_data.date_format}</td>
			<td class="icons">
				<a href="{$site_url}admin/notifications/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_notification' gid='notifications'}" title="{l i='link_edit_notification' gid='notifications'}"></a>
					{if $allow_edit}
					<a href="{$site_url}admin/notifications/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_notification' gid='notifications' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_notification' gid='notifications'}" title="{l i='link_delete_notification' gid='notifications'}"></a>
					{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="5" class="center">{l i='no_notifications' gid='notifications'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
