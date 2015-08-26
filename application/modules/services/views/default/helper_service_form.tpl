{strip}
<div id="services_helper">
	<form method="post" action="{$site_url}services/form/{$data.gid}">
		<input type="hidden" value="1" name="without_activation">
		{foreach item=item from=$data.template.data_user_array}
			<input type="hidden" value="{$item.value}" name="data_user[{$item.gid}]" id="service_{$item.gid}">
		{/foreach}
		{if $data.template.price_type eq '2'}
			<tr>
				<td>{l i='field_your_price' gid='services'}:</td>
				<td class="value"><input type="text" value="{$data.price}" name="price" class="short"> <b>{block name='currency_format_output' module='start'}</b></td>
			</tr>
		{elseif $data.template.price_type eq '3'}
			<tr>
				<td class="value"><input type="hidden" value="{$data.price}" name="price" class="short"></td>
			</tr>
		{else}
			<tr>
				<td>{l i='field_price' gid='services'}:</td>
				<td class="value"><b>{block name='currency_format_output' module='start' value=$data.price}</b></td>
			</tr>
		{/if}
		{if ($data.pay_type eq 1 || $data.pay_type eq 2) && $is_module_installed}
			<h2 class="line top bottom">{l i='account_payment' gid='services'}</h2>
			{if $data.disable_account_pay}{l i='error_account_less_then_service_price' gid='services'} <a href="{seolink module='users' method='account' action='update'}">{l i="link_add_founds" gid='services'}</a>
			{else}
				{l i='on_your_account_now' gid='services'}: <b>{block name='currency_format_output' module='start' value=$data.user_account}</b>
				<div class="b outside">
					<input type="submit" value="{l i='btn_pay_account' gid='services' type='button'}" name="btn_account">
				</div>
			{/if}
		{/if}
		{if $data.pay_type eq 2 || $data.pay_type eq 3}
			<h2 class="line top bottom">{l i='payment_systems' gid='services'}</h2>
			{if $billing_systems}
				<input type="hidden" id="system_gid" name="system_gid" value="">
				{foreach item=item from=$billing_systems}
					<button type="submit" name="btn_system" value="1" class="mrb20" onclick="$('#system_gid').val('{$item.gid}');">{$item.name}</button>
				{/foreach}
			{elseif $data.pay_type eq 3}
				{l i='error_empty_billing_system_list' gid='service'}
			{/if}
		{/if}
	</form>
</div>
{/strip}