{if count($applications) > 0}
	<h3>{l i='you_can_link' gid='users_connections'}</h3>
	{foreach item=item from=$applications}
		<a href="{$site_url}users_connections/oauth_link/{$item.id}">{$item.name}</a>
	{/foreach}
{/if}
{if count($un_applications) > 0}
	<h3>{l i='you_can_unlink' gid='users_connections'}</h3>
	{foreach item=item from=$un_applications}
		<a href="{$site_url}users_connections/oauth_unlink/{$item.id}">{$item.name}</a>
	{/foreach}
{/if}
