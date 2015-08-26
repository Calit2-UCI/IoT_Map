<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
	{if $allow_pool_send || $allow_pool_delete}<th class="first w20 center"><input type="checkbox" id="grouping_all" onclick="javascript: checkAll(this.checked);"></th>{/if}
	<th class="w150 {if !$allow_pool_send && !$allow_pool_delete}first{/if}"><a href="{$sort_links.email}"{if $order eq 'email'} class="{$order_direction|lower}"{/if}>{l i='field_mail_to_email' gid='notifications'}</a></th>
	<th class="w150"><a href="{$sort_links.subject}"{if $order eq 'subject'} class="{$order_direction|lower}"{/if}>{l i='field_subject' gid='notifications'}</a></th>
	<th class="w50"><a href="{$sort_links.send_counter}"{if $order eq 'send_counter'} class="{$order_direction|lower}"{/if}>{l i='send_attempts' gid='notifications'}</a></th>
{if $allow_pool_send || $allow_pool_delete}<th class="w50">{l i='actions' gid='notifications'}</th>{/if}
</tr>
{foreach item=item from=$senders}
	{counter print=false assign=counter}
	<tr{if $counter is div by 2} class="zebra"{/if}>
	{if $allow_pool_send || $allow_pool_delete}<td class="first w20 center"><input type="checkbox" class="grouping" value="{$item.id}"></td>{/if}
	<td class="center">{$item.email}</td>
	<td class="center">{$item.subject}</td>
	<td class="center">{$item.send_counter}</td>
	{if $allow_pool_send || $allow_pool_delete}<td class="icons">
		{if $allow_pool_send}<a href="{$site_url}admin/notifications/pool_send/{$item.id}"><img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" alt="{l i='link_send_pool' gid='notifications'}" title="{l i='link_send_pool' gid='notifications'}"></a>{/if}
	{if $allow_pool_delete}<a href="{$site_url}admin/notifications/pool_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_pool' gid='notifications' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_pool' gid='notifications'}" title="{l i='link_delete_pool' gid='notifications'}"></a>{/if}
</td>{/if}
</tr>
{foreachelse}
	<tr><td colspan="5" class="center">{l i='no_pool' gid='notifications'}</td></tr>
	{/foreach}
    </table>
    {include file="pagination.tpl"}
