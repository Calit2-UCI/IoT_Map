<div class="menu-level2">
	<ul>
		<li{if $section eq 'overview'} class="active"{/if}><div class="l"><a href="{$site_url}admin/start/settings/overview">{l i='sett_overview_item' gid='start'}</a></div></li>
		<li{if $section eq 'numerics'} class="active"{/if}><div class="l"><a href="{$site_url}admin/start/settings/numerics">{l i='sett_numerics_item' gid='start'}</a></div></li>
		{foreach item=item key=key from=$other_settings}
		<li{if $key eq $section} class="active"{/if}><div class="l"><a href="{$site_url}admin/start/settings/{$key}">{l i='sett_'+$key+'_item' gid='start'}</a></div></li>
		{/foreach}
		<li{if $section eq 'date_formats'} class="active"{/if}><div class="l"><a href="{$site_url}admin/start/settings/date_formats">{l i='sett_date_formats_item' gid='start'}</a></div></li>
	</ul>
	&nbsp;
</div>