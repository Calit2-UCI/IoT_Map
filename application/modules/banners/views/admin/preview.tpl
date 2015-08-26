{if $banner.banner_type == 1}
	<img src="{$banner.media.banner_image.file_url}" width="{$banner.banner_place_obj.width}" height="{$banner.banner_place_obj.height}" />
{else}
	{$banner.html}
{/if}