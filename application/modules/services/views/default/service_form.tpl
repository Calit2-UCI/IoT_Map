{include file="header.tpl"}
{strip}
<div class="content">
<div class="content-block">
	<h1>{l i='header_service_settings' gid='services'}: {l i=$data.name_lang_gid gid='services'}</h1>

	<div class="content-value">
		<form method="post" action="{$site_url}services/form/{$data.gid}">
			<div class="edit_block">
				<div class="payment_table">
					<h3>{l i=$data.description_lang_gid gid='services'}</h3>
					<table>
						{foreach item=item from=$data.template.data_admin_array}
							<tr>
								<td>{$item.name}:</td>
								<td class="value">
								{if $item.type eq 'string' || $item.type eq 'int' || $item.type eq 'text'}
									{$item.value}
								{elseif $item.type eq 'price'}
									{block name='currency_format_output' module='start' value=$item.value}
								{elseif $item.type eq 'checkbox'}
									{if $item.value eq '1'}{l i='yes_checkbox_value' gid='services'}{else}{l i='no_checkbox_value' gid='services'}{/if}
								{/if}
								</td>
							</tr>
						{/foreach}

						{foreach item=item from=$data.template.data_user_array}
							{if $item.type eq 'hidden'}
								<input type="hidden" value="{$item.value}" name="data_user[{$item.gid}]">
							{else}
								<tr>
									<td>{$item.name}:</td>
									{if $item.type eq 'string'}
										<td class="value"><input type="text" value="{$item.value}" name="data_user[{$item.gid}]"></td>
									{elseif $item.type eq 'int'}
										<td class="value"><input type="text" value="{$item.value}" name="data_user[{$item.gid}]" class="short"></td>
									{elseif $item.type eq 'price'}
										<td class="value"><input type="text" value="{$item.value}" name="data_user[{$item.gid}]" class="short"> {block name='currency_format_output' module='start'}</td>
									{elseif $item.type eq 'text'}
										<td class="value"class="value"><textarea name="data_user[{$item.gid}]">{$item.value}</textarea></td>
									{elseif $item.type eq 'checkbox'}
										<td class="value"><input type="checkbox" value="1" name="data_user[{$item.gid}]" {if $item.value eq '1'}checked{/if}></td>
									{/if}
								</tr>
							{/if}
						{/foreach}
						{if $data.template.price_type eq '2'}
							<tr>
								<td>{l i='field_your_price' gid='services'}:</td>
								<td class="value"><input type="text" value="{$data.price}" name="price" class="short"> <b>{block name='currency_format_output' module='start'}</b></td>
							</tr>
						{elseif $data.template.price_type eq '3'}
							<tr>
								<td>{l i='field_price' gid='services'}:</td>
								<td class="value"><input type="hidden" value="{$data.price}" name="price" class="short"><b>{block name='currency_format_output' module='start' value=$data.price}</b></td>
							</tr>
						{else}
							<tr>
								<td>{l i='field_price' gid='services'}:</td>
								<td class="value"><b>{block name='currency_format_output' module='start' value=$data.price}</b></td>
							</tr>
						{/if}
					</table>
				</div>
			</div>
			{if $data.free_activate}
				<input type="submit" class="btn mtb10" value="{l i='btn_activate_free' gid='services' type='button'}" name="btn_account">
			{else}
				<div class="pt10">
					<label class="labeled">{l i='field_activate_immediately' gid='services'} <input type="checkbox" value="1" name="activate_immediately" checked /></label>
				</div>
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
			{/if}
		</form>
		<div class="pt10">
			<a class="btn-link" href="{seolink module='users' method=account action='services'}">
				<i class="icon-arrow-left icon-big edge hover"></i><i>{l i='back_to_payment_services' gid='services'}</i>
			</a>
		</div>
	</div>
</div>
<div class="clr"></div>
</div>
{/strip}
{include file="footer.tpl"}
