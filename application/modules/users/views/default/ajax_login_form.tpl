<div class="content-block load_content">
	<h1>{l i='header_login' gid='users'}</h1>

	<div class="inside logform">
		<form action="{$site_url}users/login" method="post">
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
		<div class="line top">
			<p class="header-comment">{l i='text_register_comment' gid='users'}</p>
			<a href="{seolink module='users' method='registration'}" class="btn-link"><i class="fa-arrow-right icon-big edge hover"></i><i>{l i='link_register' gid='users'}</i></a>
		</div>

	</div>
	<div class="clr"></div>
</div>
