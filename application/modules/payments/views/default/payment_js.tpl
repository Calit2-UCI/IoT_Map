{include file="header.tpl"}
{include file="left_panel.tpl" module="start"}
{if $need_redirect}
<script>document.location.href = '{$site_url}payments/js/{$data.id}/1';</script>
{else}
{$data.js}
<div class="rc">
	<div class="content-block">
		<h1 class="inl">{l i='header_payment_form' gid='payments'}</h1>
		<div class="content-value">
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
					</table>
				</div>
				<div class="b outside">{$js}</div>
			</div>
		</div>
	</div>
</div>
{/if}
<div class="clr"></div>
{include file="footer.tpl"}
