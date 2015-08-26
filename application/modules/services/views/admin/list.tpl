{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">
{if $page_data.add_service_link}
	<ul>
		<li><div class="l"><a href="{$site_url}admin/services/edit">{l i='link_add_service' gid='services'}</a></div></li>
	</ul>
{/if}
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_service_name' gid='services'}</th>
	<th class="w100">{l i='field_status' gid='services'}</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item from=$services}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.name}</td>
	<td class="center">
	{if $item.status}
		<a href="{$site_url}admin/services/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_service' gid='services'}" title="{l i='link_deactivate_service' gid='services'}"></a>
		{else}
		<a href="{$site_url}admin/services/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_service' gid='services'}" title="{l i='link_activate_service' gid='services'}"></a>
	{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/services/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_service' gid='services'}" title="{l i='link_edit_service' gid='services'}"></a>
		{if $item.template.moveable}
		<a href="{$site_url}admin/services/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_service' gid='services' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='services'}" title="{l i='link_delete_service' gid='services'}"></a>
		{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_services' gid='services'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
