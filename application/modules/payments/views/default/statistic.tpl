{include file="header.tpl"}
<div class="content-block">
	<h1>{l i='header_my_payments_statistic' gid='payments'}</h1>

	<table class="list">
	<tr>
		<th class="w30">ID</th>
		<th class="w150">{l i='field_date_add' gid='payments'}</th>
		<th>{l i='field_amount' gid='payments'}</th>
		<th>{l i='field_payment_type' gid='payments'}</th>
		<th>{l i='field_billing_type' gid='payments'}</th>
		<th>{l i='field_status' gid='payments'}</th>
	</tr>
	{foreach item=item key=key from=$payments}
	<tr>
		<td>{$item.id}</td>
		<td>{$item.date_add|date_format:$page_data.date_format}</td>
		<td>{block name='currency_format_output' module='start' value=$item.amount cur_gid=$item.currency_gid use_gid='1'}</td>
		<td>{l i='payment_type_name_'+$item.payment_type_gid gid='payments'} ({$item.payment_data.name})</td>
		<td>{$item.system_gid}</td>
		<td>
			{if $item.status eq '1'}
				<font class="success">{l i='payment_status_approved' gid='payments'}</font>
			{elseif $item.status eq '-1'}
				<font class="error">{l i='payment_status_declined' gid='payments'}</font>
			{else}
				{l i='payment_status_wait' gid='payments'}
			{/if}
		</td>
	</tr>
	{foreachelse}
	<tr>
		<td class="center" colspan="6">{l i='no_payments' gid='payments'}</td>
	</tr>
	{/foreach}
	</table>
	<div id="pages_block_2">{pagination data=$page_data type='full'}</div>
</div>

<div class="clr"></div>
{include file="footer.tpl"}