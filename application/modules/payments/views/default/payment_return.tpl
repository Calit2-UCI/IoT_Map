{include file="header.tpl"}
<div class="lc">
	{helper func_name='login_form' module='users'}
</div>
<div class="rc">
	<div class="content-block">
	<h1 class="inl">{l i='header_payment_return' gid='payments'}</h1>

	<div class="content-value">
	<p>{l i='text_payment_return' gid='payments'}</p>
	<div class="edit_block">
		<div class="r">
			<div class="f">{l i='field_amount' gid='payments'}: </div>
			<div class="v"><b>{$payment.amount} {$payment.currency.abbr}</b></div>
		</div>
		<div class="r">
			<div class="f">{l i='field_payment_type' gid='payments'}: </div>
			<div class="v"><b>{$payment.payment_type_gid}({$payment.payment_data.name})</b></div>
		</div>
		<div class="r">
			<div class="f">{l i='field_billing_type' gid='payments'}: </div>
			<div class="v"><b>{$payment.system_gid}</b></div>
		</div>
		<div class="r">
			<div class="f">{l i='field_status' gid='payments'}: </div>
			<div class="v">
				{if $payment.status eq '1'}
					<font class="success"><b>{l i='payment_status_approved' gid='payments'}</b></font>
				{elseif $payment.status eq '-1'}
					<font class="error"><b>{l i='payment_status_declined' gid='payments'}</b></font>
				{else}
					<b>{l i='payment_status_wait' gid='payments'}</b>
				{/if}
			</div>
		</div>
	</div>

	<div class="r">
		<div class="l"><input type="button" class='btn' value="{l i='btn_cancel' gid='start' type='button'}" name="btn_save" onclick="javascript: locationHref('{seolink module='users' method=account action='payments_history'}');"></div>
		<div class="b">&nbsp;</div>
	</div>
	
	</div>
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
