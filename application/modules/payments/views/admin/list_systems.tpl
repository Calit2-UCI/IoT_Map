{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}"><a href="{$site_url}admin/payments/systems/all">{l i='filter_all_systems' gid='payments'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'used'}active{/if}"><a href="{$site_url}admin/payments/systems/used">{l i='filter_used_systems' gid='payments'} ({$filter_data.used})</a></li>
		<li class="{if $filter eq 'install'}active{/if}"><a href="{$site_url}admin/payments/install">{l i='filter_install_systems' gid='payments'}</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_system_name' gid='payments'}</th>
	<th class="w50">{l i='field_system_used' gid='payments'}</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item from=$systems}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.name}</td>
	<td class="center">
		{if $item.in_use}
		<a href="{$site_url}admin/payments/system_use/{$item.gid}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_system' gid='payments'}" title="{l i='link_deactivate_system' gid='payments'}"></a>
		{else}
		<a href="{$site_url}admin/payments/system_use/{$item.gid}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_system' gid='payments'}" title="{l i='link_activate_system' gid='payments'}"></a>
		{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/payments/system_edit/{$item.gid}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_payments' gid='payments'}" title="{l i='link_edit_payments' gid='payments'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_payment_systems' gid='payments'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
