{strip}
<div class="content-block load_content">
	<h1>{$header}</h1>
	<div class="inside">
		{if $load_map_scripts}
			{block name=show_default_map gid='profile_view' module=geomap 
				markers=$markers 
				settings=$map_settings 
				width='600' 
				height='400' 
				only_load_scripts = 1
				map_id='users_map_container'}			
		{/if}
	
		{block name=show_default_map gid='profile_view' module=geomap 
			markers=$markers 
			settings=$map_settings 
			width='600' 
			height='400' 
			only_load_content = 1
			map_id='users_map_container'}
	</div>
	<div class="clr"></div>
</div>
{/strip}
