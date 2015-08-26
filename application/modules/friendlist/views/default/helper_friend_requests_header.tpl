<li{if !$friend_requests_count} class="hide-always"{/if}>
	<a id="friendlist_link_friends_requests" href="{$site_url}friendlist/friends_requests">
		{l i='friends_requests' gid='friendlist'}: <b class="summand friend_requests_count fright">{$friend_requests_count}</b>
	</a>
</li>