<div class="content-block">
	<table class="list">
		<tr>
			<th class="w30">ID</th>
			<th class="w150">{l i='field_date_add' gid='payments'}</th>
			<th>{l i='field_amount' gid='payments'}</th>
			<th>{l i='field_payment_type' gid='payments'}</th>
			<th>{l i='field_billing_type' gid='payments'}</th>
			<th>{l i='field_status' gid='payments'}</th>
		</tr>
		{foreach item=item key=key from=$payments_helper_payments}
		<tr>
			<td>{$item.id}</td>
			<td>{$item.date_add|date_format:$payments_helper_page_data.date_format}</td>
			<td>{block name='currency_format_output' module='start' value=$item.amount}</td>
			<td>{$item.payment_type_gid} ({$item.payment_data.name})</td>
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
			<td class="center" colspan="6">{l i='payment_history_empty_results' gid='payments'}</td>
		</tr>
		{/foreach}
	</table>
	<div>{pagination data=$payments_helper_page_data type='full'}</div>
</div>
