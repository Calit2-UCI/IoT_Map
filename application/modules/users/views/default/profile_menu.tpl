<div class="tabs tab-size-15 noPrint">
	<ul>
		{depends module=wall_events}{l i='filter_section_wall' gid='users' assign='wall_section_name'}<li{if $action eq 'wall'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile' section-code='wall' section-name=$wall_section_name}">{$wall_section_name}</a></li>{/depends}
		{l i='filter_section_profile' gid='users' assign='profile_section_name'}<li{if $action eq 'view'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile' section-code='view' section-name=$profile_section_name}">{$profile_section_name}</a></li>
		{depends module=media}{l i='filter_section_gallery' gid='users' assign='gallery_section_name'}<li{if $action eq 'gallery'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='profile' section-code='gallery' section-name=$gallery_section_name}">{$gallery_section_name}</a></li>{/depends}
	</ul>
	{if $action eq 'wall'}
	<div class="fright">
		<span id="wall_permissions_link" class="fright" title="{l i='header_wall_settings' gid='wall_events'}" onclick="ajax_permissions_form(site_url+'wall_events/ajax_user_permissions/');">
			<i class="fa-cog icon-big edge hover zoom30"><i class="fa-mini-stack icon-lock"></i></i>
		</span>
	</div>
	{/if}
</div>
