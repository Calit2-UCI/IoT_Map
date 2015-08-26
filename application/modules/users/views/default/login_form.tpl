{include file="header.tpl"}

<div class="content-block">

	<div class="inside logform">
		<h1>{l i='header_login' gid='users'}</h1>
		<form action="{$site_url}users/login" method="post" >
			<div class="r">
				<div class="f">{l i='field_email' gid='users'}: </div>
				<div class="v"><input type="text" name="email" {if $DEMO_MODE}value="{$demo_user_type_login_settings.login}"{/if}></div>
			</div>
			<div class="r">
				<div class="f">{l i='field_password' gid='users'}: </div>
				<div class="v">
					<input type="password" name="password" {if $DEMO_MODE}value="{$demo_user_type_login_settings.password}"{/if}>
					<span class="v-link"><a href="{seolink module='users' method='restore'}">{l i='link_restore' gid='users'}</a></span>
				</div>
			</div>
			{* Don't delete (openid) *}
			{*<h3>{l i='field_open_id' gid='users'}</h3>
			<div class="r">
				<div class="v"><input type="text" name="user_open_id" class="openid"></div>
			</div>
			<br>*}
			<div class="r">
				<input type="submit" value="{l i='btn_login' gid='start' type='button'}" name="logbtn">
			</div>
		</form>
		{helper func_name='show_social_networking_login' module='users_connections'}
	</div>
	<div class="clr"></div>
</div>

	<br><br><br>

<div class="clr"></div>
{include file="footer.tpl"}
