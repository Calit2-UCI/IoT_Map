{include file="header.tpl"}
{strip}
{$data.js}
<div class="content-block">

	<h1 class="inl">{l i='header_payment_form' gid='payments'}</h1>

	<div class="content-value">
		{*<p>{l i='text_payment_form' gid='payments'}</p>*}

		<form method="post">
		<input type="hidden" name="payment_type_gid" value="{$data.payment_type_gid}">
		<input type="hidden" name="amount" value="{$data.amount}">
		<input type="hidden" name="currency_gid" value="{$data.currency_gid}">
		<input type="hidden" name="system_gid" value="{$data.system_gid}">
		<input type="hidden" name="payment_data[name]" value="{$data.payment_data.name|escape}">

		<div class="edit_block">
			<div class="payment_table">
				<table>
					<tr>
						<td>{l i='field_payment_name' gid='payments'}:</td>
						<td class="value">{$data.payment_data.name} ({$data.payment_type_gid})</td>
					</tr>
					<tr>
						<td>{l i='field_amount' gid='payments'}:</td>
						<td class="value">{block name='currency_format_output' module='start' value=$data.amount cur_gid=$data.currency_gid}</td>
					</tr>
					<tr>
						<td>{l i='field_billing_type' gid='payments'}:</td>
						<td class="value">{$data.system.name}</td>
					</tr>
					{if $data.system.info_data}
						<tr>
							<td>{l i='field_info_data' gid='payments'}:</td>
							<td class="value">{$data.system.info_data}</td>
						</tr>
					{/if}
					{foreach item=item key=key from=$data.map}
					<tr>
						<td>{$item.name}:</td>
						<td class="value">
							{if $item.type eq 'text'}
							<input type="text" name="map[{$key}]" value="{$data.payment_data[$key]}" {if $item.size eq 'small'}class="short"{elseif $item.size eq 'big'}class="long"{/if}>
							{elseif $item.type eq 'textarea'}
							<textarea name="map[{$key}]" rows="10" cols="40">{$data.payment_data[$key]}</textarea>
							{/if}
						</td>
					</tr>
					{/foreach}

				</table>
			</div>
			<div class="b outside">
				<input type="submit" class='btn' value="{l i='btn_send' gid='start' type='button'}" name="btn_save">
				<a class="btn-link" href="{seolink module='users' method=account action='payments_history'}">
					<i class="icon-arrow-left icon-big edge hover"></i><i>{l i='btn_cancel' gid='start'}</i>
				</a>
			</div>
		</div>
		</form>
	</div>
</div>
<div class="clr"></div>
{/strip}
{include file="footer.tpl"}
