{include file="header.tpl"}
{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}</h1>

	<div class="edit_block">
		{if $user.user_open_id && !$user.password}
			<p>{l i='text_open_id_use' gid='users'} <b>{$user.user_open_id}</b></p>
			<p>{l i='text_open_id_create_password' gid='users'}</p>
			<form action="" method="post" >
				<div class="r">
					<div class="f">{l i='field_email' gid='users'}: </div>
					<div class="v"><input type="text" name="email" value="{$user.email}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_password' gid='users'}: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				{if !empty($use_repassword)}
					<div class="r">
						<div class="f">{l i='field_repassword' gid='users'}: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				{/if}
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="{l i='btn_save' gid='start' type='button'}" name="btn_account_create"></div>
				</div>
			</form>
		{elseif $user.user_open_id && $user.password}
			<p>{l i='text_open_id_use' gid='users'} <b>{$user.user_open_id}</b></p>
			<p>{l i='text_open_id_and_password_used' gid='users'}</p>
			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f">{l i='field_email' gid='users'}: </div>
					<div class="v"><input type="text" name="email" value="{$user.email}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_phone' gid='users'}: </div>
					<div class="v"><input type="text" name="phone" value="{$user.phone}"></div>
				</div>
				<div class="r hide">
					<div class="v"><input type="checkbox" name="show_adult" id="show_adult" value="1"{if $user.show_adult} checked{/if} /> <label for="show_adult">{l i='field_show_adult' gid='users'}</label></div>
				</div>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="{l i='btn_save' gid='start' type='button'}" name="btn_contact_save"></div>
				</div>
			</form>

			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f">{l i='field_password' gid='users'}: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				{if !empty($use_repassword)}
					<div class="r">
						<div class="f">{l i='field_repassword' gid='users'}: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				{/if}
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="{l i='btn_save' gid='start' type='button'}" name="btn_password_save"></div>
				</div>
			</form>
		{else}
			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f">{l i='field_email' gid='users'}: </div>
					<div class="v"><input type="text" name="email" value="{$user.email}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_phone' gid='users'}: </div>
					<div class="v"><input type="text" name="phone" value="{$user.phone}"></div>
				</div>
				<div class="r hide">
					<div class="v"><input type="checkbox" name="show_adult" id="show_adult" value="1"{if $user.show_adult} checked{/if} /> <label for="show_adult">{l i='field_show_adult' gid='users'}</label></div>
				</div>
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="{l i='btn_save' gid='start' type='button'}" name="btn_contact_save"></div>
				</div>
			</form>

			<form action="" method="post" class="ptb10">
				<div class="r">
					<div class="f">{l i='field_password' gid='users'}: </div>
					<div class="v"><input type="password" name="password"></div>
				</div>
				{if !empty($use_repassword)}
					<div class="r">
						<div class="f">{l i='field_repassword' gid='users'}: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				{/if}
				<div class="r">
					<div class="b"><input type="submit" class='btn' value="{l i='btn_save' gid='start' type='button'}" name="btn_password_save"></div>
				</div>
			</form>
		{/if}
		{helper func_name='show_social_networking_link' module='users_connections'}
	</div>
	<div class="ptb10 hide">
		{helper func_name='get_user_subscriptions_form' module='subscriptions' func_param='account'}
	</div>
	<div class="ptb10 line top">
		<span class="a" onclick="ability_delete_available_view.check_available();">{l i='delete_account' gid='users'}</span>
	</div>
</div>
<div class="clr"></div>
{/strip}

<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='available_view.js' return='path'}{literal}", 
			function(){
				ability_delete_available_view = new available_view({
					siteUrl: site_url,
					checkAvailableAjaxUrl: 'users/ajax_available_ability_delete/',
					buyAbilityAjaxUrl: 'users/ajax_activate_ability_delete/',
					buyAbilityFormId: 'ability_form',
					buyAbilitySubmitId: 'ability_form_submit',
					formType: 'list',
					alert_type: 'delete_profile',
					alert_ok_button: "{/literal}{l i='btn_confirm_delete_profile' gid='users'}{literal}",
					alert_cancel_button: "{/literal}{l i='btn_not_confirm_delete_profile' gid='users'}{literal}",
					lang_delete_confirm: "{/literal}{l i='text_delete_confirm' gid='users'}{literal}",
					success_request: function(message) {
						error_object.show_error_block(message, 'success');
						locationHref(site_url);
					},
					fail_request: function(message) {error_object.show_error_block(message, 'error');},
				});
			},
			['ability_delete_available_view'],
			{async: false}
		);
	});
</script>{/literal}

{include file="footer.tpl"}