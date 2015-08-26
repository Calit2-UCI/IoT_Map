{strip}
<menu class="header-item settings-menu">
	<a href="{$site_url}users/profile">{$user_session_data.output_name}</a>&nbsp;
	<i class="fa-cog icon hover-spin"></i>
	<div class="drop w150">
		<menu>
			{foreach key=key item=item from=$menu}
				<li class="righted{if !empty($item.active) || !empty($item.in_chain)} active{/if}">
					<a id="settings_{$item.gid}" href="{$item.link}">{$item.value}</a>
				</li>
			{/foreach}
		</menu>
	</div>
</menu>
{/strip}