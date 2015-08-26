{include file="header.tpl"}
{js module='payments' file='admin-payments-settings.js'}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/payments/settings_edit">{l i='link_add_currency' gid='payments'}</a></div></li>
	</ul>
	&nbsp;
</div>

<form method="post" action="{$site_url}admin/payments/update_currency_rates" name="save_form" enctype="multipart/form-data">
<div class="filter-form-new">
	<div class="form">
		<b>{l i='header_currency_rates_update_auto' gid='payments'}</b>		
		<p class="rates_update">
			<input type="hidden" name="use_rates_update" value="0" />
			<input type="checkbox" name="use_rates_update" value="1" id="use_rates_update" {if $use_rates_update}checked{/if}> 
			<label>{l i='text_currency_rates_update' gid='payments'}</label>
		</p>
		<select name="rates_driver" id="driver_select">
			{foreach item=item from=$updaters}
			<option value="{$item}" {if $item eq $rates_update_driver}selected{/if}>{l i='currency_updater_'+$item gid='payments'}</option>
			{/foreach}
		</select>		
		<input type="image" name="bt_auto" src="{$site_root}{$img_folder}icon-approve.png" alt="{l i='link_currency_rates_update_auto' gid='payments' type='button'}" title="{l i='link_currency_rates_update_auto' gid='payments' type='button'}" id="rates_update_driver"><br><br>
		<b>{l i='header_currency_rates_update_manual' gid='payments'}</b><br><br>
		<select name="rates_driver" id="manual_select">
			{foreach item=item from=$updaters}
			<option value="{$item}">{l i='currency_updater_'+$item gid='payments'}</option>
			{/foreach}
		</select>
		<input type="image" name="bt_manual" src="{$site_root}{$img_folder}icn_update.png" alt="{l i='link_currency_rates_update_manual' gid='payments' type='button'}" title="{l i='link_currency_rates_update_manual' gid='payments' type='button'}" id="rates_update_manual"><br><br>
	</div>
</div>
</form>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_currency_gid' gid='payments'}</th>
	<th class="">{l i='field_currency_name' gid='payments'}</th>
	<th class="w100">{l i='field_currency_default' gid='payments'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$currency}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.gid}</td>
	<td class="center">{$item.name} ({$item.abbr})</td>
	<td class="center">
		{if $item.is_default}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" >
		{else}
		<a href="{$site_url}admin/payments/settings_use/{$item.id}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_default_currency' gid='payments'}" title="{l i='link_default_currency' gid='payments'}"></a>
		{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/payments/settings_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_currency' gid='payments'}" title="{l i='link_edit_currency' gid='payments'}"></a>
		<a href="{$site_url}admin/payments/settings_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_currency' gid='payments' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_currency' gid='payments'}" title="{l i='link_delete_currency' gid='payments'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_payment_currencies' gid='payments'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
<script>{literal}
	$(function(){
		new AdminPaymentsSettings({
			siteUrl: '{/literal}{$site_url}{literal}', 
		});	
	});
{/literal}</script>
{include file="footer.tpl"}
