{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/packages/package_edit">{l i='link_add_package' gid='packages'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first">{l i='field_package_name' gid='packages'}</th>
		<th class="w100">{l i='field_status' gid='packages'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$packages}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first center">{$item.name}</td>
			<td class="center">
				{if $item.status}
					<a href="{$site_url}admin/packages/activate_package/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_service' gid='packages'}" title="{l i='link_deactivate_service' gid='packages'}"></a>
				{else}
					<a href="{$site_url}admin/packages/activate_package/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_service' gid='packages'}" title="{l i='link_activate_service' gid='packages'}"></a>
				{/if}
			</td>
			<td class="icons">
				<a href="{$site_url}admin/packages/package_services/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_package_services' gid='packages'}" title="{l i='link_edit_service' gid='packages'}"></a>
				<a href="{$site_url}admin/packages/package_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_service' gid='packages'}" title="{l i='link_edit_service' gid='packages'}"></a>
				<a href="{$site_url}admin/packages/package_delete/{$item.id}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="4" class="center">{l i='no_packages' gid='packages'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
