<div>
{foreach item=item from=$settings_errors}
<font class="req">{$item}</font><br>
{/foreach}
</div>
		<div class="form">
			<div class="row">
				<div class="h">{l i='field_nickname' gid='ausers'}: </div>
				<div class="v"><input type="text" value="{$settings_data.nickname}" name="nickname"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_password' gid='ausers'}: </div>
				<div class="v"><input type="password" value="{$settings_data.password}" name="password"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_name' gid='ausers'}: </div>
				<div class="v"><input type="text" value="{$settings_data.name}" name="name"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_email' gid='ausers'}: </div>
				<div class="v"><input type="text" value="{$settings_data.email}" name="email"></div>
			</div>
		</div>
