{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/ausers/edit">{l i='link_add_user' gid='ausers'}</a></div></li>
		{block name='add_moderator' module='moderators'}
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/ausers/index/all">{l i='filter_all_users' gid='moderators'} ({$filter_data.all})</a></li>
		<li class="active{if !$filter_data.moderator} hide{/if}"><a href="{$site_url}admin/moderators">{l i='filter_moderator_users' gid='moderators'} ({$filter_data.moderator})</a></li>
		<li class="{if !$filter_data.admin} hide{/if}"><a href="{$site_url}admin/ausers/index/admin">{l i='filter_admin_users' gid='ausers'} ({$filter_data.admin})</a></li>
		<li class="{if !$filter_data.not_active} hide{/if}"><a href="{$site_url}admin/ausers/index/not_active">{l i='filter_not_active_users' gid='moderators'} ({$filter_data.not_active})</a></li>
		<li class="{if !$filter_data.active} hide{/if}"><a href="{$site_url}admin/ausers/index/active">{l i='filter_active_users' gid='moderators'} ({$filter_data.active})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="{$sort_links.nickname'}"{if $order eq 'nickname'} class="{$order_direction|lower}"{/if}>{l i='field_nickname' gid='moderators'}</a></th>
	<th><a href="{$sort_links.name}"{if $order eq 'name'} class="{$order_direction|lower}"{/if}>{l i='field_name' gid='moderators'}</a></th>
	<th><a href="{$sort_links.email}"{if $order eq 'email'} class="{$order_direction|lower}"{/if}>{l i='field_email' gid='moderators'}</a></th>
	<th class="w150"><a href="{$sort_links.date_created}"{if $order eq 'date_created'} class="{$order_direction|lower}"{/if}>{l i='field_date_created' gid='moderators'}</a></th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$users}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.nickname}</td>
	<td>{$item.name}</td>
	<td>{$item.email}</td>
	<td class="center">{$item.date_created|date_format:$page_data.date_format}</td>
	<td class="icons">
		{if $item.status}
		<a href="{$site_root}admin/moderators/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_user' gid='moderators'}" title="{l i='link_deactivate_user' gid='moderators'}"></a>
		{else}
		<a href="{$site_root}admin/moderators/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_user' gid='moderators'}" title="{l i='link_activate_user' gid='moderators'}"></a>
		{/if}
		<a href="{$site_root}admin/moderators/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_user' gid='moderators'}" title="{l i='link_edit_user' gid='moderators'}"></a>
		<a href="{$site_root}admin/moderators/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_user' gid='moderators' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_user' gid='moderators'}" title="{l i='link_delete_user' gid='moderators'}"></a>
	</td>
</tr>
{foreachelse}
<tr>
	<td class="center" colspan="5">{l i='moderators_list_empty' gid='moderators'}</td>
</tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
