<div>
{foreach item=item from=$settings_errors}
<font class="req">{$item}</font><br>
{/foreach}
</div>
		<div class="form">
			<div class="row">
				<div class="h">{l i='field_order_key' gid='start'}: </div>
				<div class="v"><input type="text" value="{$settings_data.product_order_key}" name="product_order_key"></div>
			</div>
		</div>
