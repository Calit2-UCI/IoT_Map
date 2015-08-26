<menu id="users-top-menu" class="header-item" label="{l i='on_account_header' gid='users_payments'}">
	<i class="fa-comments"></i>&nbsp;<b class="sum"></b>&nbsp;
	<i class="fa-caret-down"></i>
	<div class="drop w300">
		<span>{l i='notifications', gid='users'}</span>
		<menu>
			{block name='new_messages' module='mailbox' template='header'}
			{block name='admin_new_messages' module='tickets' template='header' is_admin='1'}
			{*block name='friend_requests' module='users_lists' template='header'*}
			{block name='friend_requests' module='friendlist' template='header'}
			{block name='winks_count' module='winks' template='header'}
			{block name='visitors' module='users' template='header'}
			{block name='new_kisses' module='kisses' template='header'}
			<li class="no-notifications hide-always">{l i='no_notifications', gid='users'}</li>
		</menu>
	</div>
</menu>
<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			["{/literal}{js file='top-menu.js' module='users' return='path'}{literal}"],
			function(){
				new topMenu({
					siteUrl: site_url
				});
			}
		);
	});
</script>{/literal}
