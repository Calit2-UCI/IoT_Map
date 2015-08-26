{include file="header.tpl"}
{* helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_users_menu' *}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/users/group_edit">{l i='link_add_group' gid='users'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_group_gid' gid='users'}</th>
	<th>{l i='field_group_name' gid='users'}</th>
	<th class="w150">{l i='field_date_created' gid='users'}</th>
	<th>{l i='field_group_default' gid='users'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$groups}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.gid}</td>
	<td><span class="js-select">{$item.group_name}</span></td>
	<td class="center">{$item.date_created|date_format:$page_data.date_format}</td>
	<td class="center"><a href="{$site_url}admin/users/group_set_default/{$item.id}"><img src="{$site_root}{$img_folder}{if $item.is_default}icon-full.png{else}icon-empty.png{/if}"></a></td>
	<td class="icons">
		<a href="{$site_url}admin/users/group_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_group' gid='users'}" title="{l i='link_edit_group' gid='users'}"></a>
		<a href="{$site_url}admin/users/group_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_group' gid='users' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_group' gid='users'}" title="{l i='link_delete_group' gid='users'}"></a>
	</td>
</tr>
{foreachelse}
<tr>
	<td class="center" colspan="5">{l i='no_groups' gid='users'}</td>
</tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
