<form method="post" enctype="application/x-www-form-urlencoded" id="search_form" name="search_form">
	<div class="edit-form n150">
		<div class="row header">
			{l i='admin_header_subscription' gid='mail_list'}
		</div>
		<div class="row">
			<div class="h"><label for="id_subscription">{l i='field_subscription' gid='mail_list'}:</label></div>
			<div class="v">
				<select id="id_subscription" name="id_subscription" class="middle">
					{foreach item=subscription from=$subscriptions}
						<option value="{$subscription.id}" {if $subscription.id eq $data.id_subscription}selected="selected"{/if}>
							{$subscription.name}
						</option>
					{/foreach}
				</select>
			</div>
		</div>
		<br />
		<div class="row header">
			{l i='admin_header_users_data' gid='mail_list'}
		</div>
		<div class="row">
			<div class="h"><label for="email">{l i='field_email' gid='mail_list'}:</label></div>
			<div class="v"><input type="text" id="email" name="email" value="{$data.email|escape}" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><label for="name">{l i='field_nickname' gid='mail_list'}:</label></div>
			<div class="v"><input type="text" id="name" name="name" value="{$data.name|escape}" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><label for="date">{l i='field_registration_date' gid='mail_list'}:</label></div>
			<div class="v">
				<input type='text' value='{$data.date}' id="date" name="date" class="datepicker short_long" maxlength="10">
			</div>
		</div>
		<div class="row">
			<div class="h"><label for="user_type">{l i='field_user_type' gid='mail_list'}:</label></div>
			<div class="v">
				<select id="user_type" name="user_type" class="short_long">
						<option value="0" {if $user_type=='0'} selected{/if}>...</option>
						{foreach from=$user_types.option item=item key=key}
							<option value="{$key}"{if $data.user_type==$key} selected{/if}>{$item}</option>
						{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="location">{l i='field_location' gid='mail_list'}:</label>
			</div>
			<div class="v">
				{block name='location_select' module='countries' select_type='city' id_country=$data.id_country id_region=$data.id_region id_city=$data.id_city}
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_search" value="{l i='btn_search' gid='start' type='button'}">
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_cancel" value="{l i='btn_cancel' gid='start' type='button'}">
		</div>
	</div>
</form>
<div class="clr"></div>
{js file='jquery-ui.custom.min.js'}
<link href='{$site_root}{$js_folder}jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type='text/javascript'>{literal}
	$(function(){
		var minYear = -5;
		var yr = new Date().getFullYear() + minYear + ':' + new Date().getFullYear();
		$('.datepicker').datepicker({
			dateFormat:	'yy-mm-dd',
			changeYear: true,
			changeMonth:true,
			yearRange:	yr
		});
		$('div.row:odd').addClass('zebra');
		$('#btn_save').bind('click', function() {
			mail_list.save_filter($('#search_form').serializeArray());
		});
		$('#id_subscription').bind('change', function() {this.form.submit()});
	});
{/literal}</script>