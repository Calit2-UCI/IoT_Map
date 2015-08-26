{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li><a href="{$site_url}admin/payments/systems/all">{l i='filter_all_systems' gid='payments'} ({$filter_data.all})</a></li>
		<li><a href="{$site_url}admin/payments/systems/used">{l i='filter_used_systems' gid='payments'} ({$filter_data.used})</a></li>
		<li class="active"><a href="{$site_url}admin/payments/install">{l i='filter_install_systems' gid='payments'}</a></li>
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_system_name' gid='payments'}</th>
	<th class="w100 ">&nbsp;</th>
</tr>
{foreach from=$payments item=item}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.name}</td>
	<td class="center">
		<a href="{$site_url}admin/payments/install_payment/{$item.gid}">{l i='link_install_payment' gid='payments'}</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3" class="center">{l i='no_payment_systems' gid='payments'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
