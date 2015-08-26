<li{if !$visitors_count} class="hide-always"{/if}>
	<a id="users_link_my_guests" href="{$site_url}users/my_guests">
		{l i='header_my_guests' gid='users'}: <b class="summand visitors_count fright">{$visitors_count}</b>
	</a>
</li>