{if count($services) > 0}
	<div class="oauth-links">
		<span class="mr5">{l i='sign_in_with' gid='users_connections'}</span>
		<ins>
			{foreach item=item from=$services}
				<a href="{$site_url}users_connections/oauth_login/{$item.id}" class="icon-{$item.gid} icon-big edge square hover" data-pjax="0" title="{$item.name}"></a>
			{/foreach}
		</ins>
	</div>
{/if}