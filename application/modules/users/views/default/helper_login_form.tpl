{if $auth_type eq 'user'}

<div class="content-block no-margin">
	<h1>{l i='header_profile' gid='users'}</h1>
	<div class="content-value small">
		{if $user_data.media.user_logo.thumbs.middle}
		<div class="logo-img">
			{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$user_data}
			<img src="{$user_data.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" />
		</div>
		{/if}
		<span class="nick">{$user_data.fname} {$user_data.sname}</span>
		<a href="{seolink module='users' method='profile'}">{$user_data.nickname}</a><br>
		<br>
		<span class="nick with_money">{block name='currency_format_output' module='start' value=$user_data.account}</span>
		<div class="clr"></div>
	</div>
</div>
<div class="user-menu">
{helper func_name='get_menu' helper_name='menu' func_param='account_menu'}
</div>
<div class="clr"></div>
<br>
{else}
{*
<div class="content-block logform">
	<h1>{l i='header_login' gid='users'}</h1>
	<div class="content-value small">
		<form action="{$site_url}users/login" method="post" >
		<div class="r">
			<div class="f">{l i='field_email' gid='users'}: </div>
			<div class="v"><input type="text" name="email"></div>
		</div>
		<div class="r">
			<div class="f">{l i='field_password' gid='users'}: </div>
			<div class="v"><input type="password" name="password"></div>
		</div>
		<h3>{l i='field_open_id' gid='users'}</h3>
		<div class="r">
			<div class="v"><input type="text" name="user_open_id" class="openid"></div>
		</div>
		<br>
		<div class="r">
			<div class="l">
				<input type="submit" class='btn' value="{l i='btn_login' gid='start' type='button'}" name="logbtn">
			</div>
			<div class="b">
				<a href="{seolink module='users' method='register'}">{l i='link_register' gid='users'}</a><br>
				<a href="{seolink module='users' method='restore'}">{l i='link_restore' gid='users'}</a>
			</div>
		</div>
		</form>
	</div>
</div>
*}
{/if}

