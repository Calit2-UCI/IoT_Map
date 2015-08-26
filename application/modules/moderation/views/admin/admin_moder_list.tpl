{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_moderation_menu'}
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li{if $type_name eq 'all'} class="active"{/if}><a href="{$site_url}admin/moderation">{l i='all_objects' gid='moderation'}</a></li>
		{foreach item=item from=$moder_types}
		{if $item.mtype >= 0}<li class="{if $type_name eq $item.name}active{/if}"><a href="{$site_url}admin/moderation/index/{$item.name}">
		{l i='mtype_'$item.name gid='moderation'} ({$item.count})</a></li>{/if}
		{/foreach}
	</ul>
	<div class="clr"></div>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_date_add' gid='moderation'}</th>
	{if $type_name eq 'all'}<th class="">{l i='moder_object_type' gid='moderation'}</th>{/if}
	<th>{l i='moder_object' gid='moderation'}</th>
	<th colspan="2" class="">&nbsp;</th>
</tr>

{foreach item=item from=$list}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center w150">{$item.date_add}</td>
	{if $type_name eq 'all'}<td class="center w150" style="text-transform: capitalize">{l i='mtype_'$item.type_name gid='moderation'}</td>{/if}
	<td>{$item.html}</td>
	<td class="icons w70">
		{if $item.view_link}<a href="{$item.view_link}" target="_blank"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" alt="{l i='view_object' gid='moderation'}" title="{l i='view_object' gid='moderation'}"></a>{/if}
		{if $item.edit_link}<a href="{$item.edit_link}" target="_blank"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='edit_object' gid='moderation'}" title="{l i='edit_object' gid='moderation'}"></a>{/if}
		{if $item.avail_delete}<a href='{$site_url}admin/moderation/delete_object/{$item.id}/' onclick="javascript: if(!confirm('{l i='note_delete_object' gid='moderation' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='delete_object' gid='moderation'}" title="{l i='delete_object' gid='moderation'}"></a>{/if}
		{if $item.mark_adult}<a href='{$site_url}admin/moderation/mark_adult_object/{$item.id}/'><img src="{$site_root}{$img_folder}icon_adult.png" width="16" height="16" alt="{l i='mark_adult' gid='moderation'}" title="{l i='mark_adult' gid='moderation'}"></a>{/if}
		
	</td>
	<td class="icons w50">
		<a href="{$site_url}admin/moderation/approve/{$item.id}/"><img src="{$site_root}{$img_folder}icon-approve.png" width="16" height="16" alt="{l i='approve_object' gid='moderation'}" title="{l i='approve_object' gid='moderation'}"></a>
		{if $item.avail_decline}<a href='{$site_url}admin/moderation/decline/{$item.id}/'><img src="{$site_root}{$img_folder}icon-decline.png" width="16" height="16" alt="{l i='decline_object' gid='moderation'}" title="{l i='decline_object' gid='moderation'}"></a>{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">{l i='no_objects' gid='moderation'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
