	<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2>{l i='stat_header_users' gid='users'}</th>
	</tr>
	{if $stat_users.index_method}
	<tr>
		<td class="first"><a id="users_link_all" href="{$site_url}admin/users/index/">{l i='stat_header_all' gid='users'}</a></td>
		<td class="w30"><a id="users_link_all_num" href="{$site_url}admin/users/index/">{$stat_users.all}</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="users_link_active" href="{$site_url}admin/users/index/active">{l i='stat_header_active' gid='users'}</a></td>
		<td class="w30"><a id="users_link_active_num" href="{$site_url}admin/users/index/active">{$stat_users.active}</a></td>
	</tr>
	<tr>
		<td class="first"><a id="users_link_not_active" href="{$site_url}admin/users/index/not_active">{l i='stat_header_blocked' gid='users'}</a></td>
		<td class="w30"><a id="users_link_not_active_num" href="{$site_url}admin/users/index/not_active">{$stat_users.blocked}</a></td>
	</tr>
	<tr class="zebra">
		<td class="first"><a id="users_link_not_confirm" href="{$site_url}admin/users/index/not_confirm">{l i='stat_header_unconfirmed' gid='users'}</a></td>
		<td class="w30"><a id="users_link_not_confirm_num" href="{$site_url}admin/users/index/not_confirm">{$stat_users.unconfirm}</a></td>
	</tr>
	{/if}
	{if $stat_users.moderation_method}
	<tr>
		<td class="first"><a id="users_link_user_logo" href="{$site_url}admin/moderation/index/user_logo">{l i='stat_header_moderation_icons' gid='users'}</a></td>
		<td class="w30"><a id="users_link_user_logo_num" href="{$site_url}admin/moderation/index/user_logo">{$stat_users.icons}</a></td>
	</tr>
	{/if}
	</table>
