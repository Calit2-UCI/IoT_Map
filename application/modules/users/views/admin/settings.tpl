{include file="header.tpl"}
<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='header_settings' gid='users'}</div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_allow">{l i='field_guest_view_profile_allow' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_allow" type="checkbox" name="guest_view_profile_allow" 
					   value="1" {if $users_settings.guest_view_profile_allow}checked="checked"{/if}>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_limit">{l i='field_guest_view_profile_limit' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_limit" type="checkbox" name="guest_view_profile_limit" 
					   value="1" {if $users_settings.guest_view_profile_limit}checked="checked"{/if}>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_num">{l i='field_guest_view_profile_num' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_num" type="number" min="0" max="1000" name="guest_view_profile_num" 
					   value="{$users_settings.guest_view_profile_num}" class="short">
			</div>
		</div>

		<div class="row">
			<div class="h">
				<label for="user_approve">{l i='field_user_approve' gid='users'}:</label>
			</div>
			<div class="v">
				<select id="user_approve" name="user_approve" class="middle_short">
					<option value="0"{if $users_settings.user_approve eq '0'}selected="selected"{/if}>
						{l i='field_user_approve_no_value' gid='users'}
					</option>
					<option value="1"{if $users_settings.user_approve eq '1'}selected="selected"{/if}>
						{l i='field_user_approve_admin_value' gid='users'}
					</option>
					<option value="2"{if $users_settings.user_approve eq '2'}selected="selected"{/if}>
						{l i='field_user_approve_service_value' gid='users'}
					</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="user_confirm">{l i='field_user_confirm' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="user_confirm" type="checkbox" name="user_confirm" 
					   value="1" {if $users_settings.user_confirm}checked="checked"{/if}>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="hide_user_names">{l i='field_hide_user_names' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="hide_user_names" type="checkbox" name="hide_user_names" 
					   value="1" {if $users_settings.hide_user_names}checked="checked"{/if}>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="age_min">{l i='field_age_min' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="age_min" type="number" name="age_min" value="{$users_settings.age_min}" class="short">
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="age_max">{l i='field_age_max' gid='users'}:</label>
			</div>
			<div class="v">
				<input id="age_max" type="number" name="age_max" value="{$users_settings.age_max}" class="short">
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}">
		</div>
	</div>
	<a class="cancel" href="{$site_url}admin/start/menu/system-items">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{js module='users' file='users-settings.js'}
<script>{literal}
	$(function () {
		new usersSettings();
		$('div.row:odd').addClass('zebra');
	});
{/literal}</script>
{include file="footer.tpl"}