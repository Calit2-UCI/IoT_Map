<div class="tabs tab-size-15 noPrint">
	<ul>
		{depends module=wall_events}{l i='filter_section_wall' gid='users' assign='wall_section_name'}<li{if $profile_section eq 'wall'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata section-code='wall' section-name=$wall_section_name}">{$wall_section_name}</a></li>{/depends}
		{l i='filter_section_profile' gid='users' assign='profile_section_name'}<li{if $profile_section eq 'profile'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata section-code='profile' section-name=$profile_section_name}">{$profile_section_name}</a></li>
		{depends module=media}{l i='filter_section_gallery' gid='users' assign='gallery_section_name'}<li{if $profile_section eq 'gallery'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='users' method='view' data=$seodata section-code='gallery' section-name=$gallery_section_name}">{$gallery_section_name}</a></li>{/depends}
	</ul>
</div>
