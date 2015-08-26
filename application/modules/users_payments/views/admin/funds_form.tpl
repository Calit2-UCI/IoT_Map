<div class="load_content_controller">
	<h1>{l i='header_add_funds_selected' gid='users_payments'}</h1>
	<div class="inside">
		<div class="edit-form n100">
			<div class="row">
				<div class="h">{l i='field_amount' gid='users_payments'}: </div>
				<div class="v"><input type="text" value="" name="amount" class="short" id="add_fund_amount" autofocus="autofocus" /> {block name='currency_format_output' module='start'}</div>
				<script>setTimeout("$('#add_fund_amount').focus()", 100);</script>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_add' gid='start' type='button'}" onclick="javascript: send_add_funds();"></div></div>
		<div class="clr"></div>
	</div>
</div>