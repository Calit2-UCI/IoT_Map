<div class="{$place.keyword|escape}-place">
{if $place.rotate_time > 0}
	<script>
		var place_data{$place.id} = {literal}{{/literal}id: {$place.id}, width: {$place.width}, height: {$place.height}, rotate_time: {$place.rotate_time}, keyword: '{$place.keyword}', div_id: 'banner-place-{$place.keyword}'{literal}}{/literal}
		
		var banner_data{$place.id} = new Array();
		
		{foreach from=$banners item=banner key=key}
			{if $banner.banner_type == 1}
				banner_data{$place.id}[{$key}] = {literal}{{/literal} id: {$banner.id}, banner_type: 'image', banner_src: '{$banner.media.banner_image.file_url|escape}', html: '<a href="{$site_url}banners/go/{$banner.id}" title="{$banner.alt_text|escape}" {if $banner.new_window == 1 || $banner.user_id}target="_blank"{/if}><img alt="{$banner.alt_text|escape}" width="{$place.width}" height="{$place.height}" src="{$banner.media.banner_image.file_url|escape}" class="banner"/></a>'{literal}}{/literal};
			{else}
				banner_data{$place.id}[{$key}] = {literal}{{/literal} id: {$banner.id}, banner_type: 'html', html: '{$banner.html|mysql_escape_string}'{literal}}{/literal};
			{/if}
		{/foreach}
		
		{literal}
		(function(){
			var banners_trys = 0;
			load_banners();
			function load_banners(){
				if(window.banners){
					banners.create_banner_area(place_data{/literal}{$place.id}{literal}, banner_data{/literal}{$place.id}{literal});
				}else if(banners_trys++ < 20){
					setTimeout(load_banners, 500);
				}
			};
		})();
		{/literal}
	</script>
	<div id="banner-place-{$place.keyword|escape}" style="width: {$place.width}px; height: {$place.height}px; overflow: hidden;"></div>
{elseif $banners}
	{assign var="banner" value=$banners[0]}
	<div id="banner-place-{$place.keyword|escape}" style="width: {$place.width}px; height: {$place.height}px;">
	{if $banner.banner_type == 1}
	<a href="{$site_url}banners/go/{$banner.id}" title="{$banner.alt_text|escape}" {if $banner.new_window == 1 || $banner.user_id}target="_blank"{/if}><img alt="{$banner.alt_text|escape}" width="{$place.width}" height="{$place.height}" src="{$banner.media.banner_image.file_url|escape}" class="banner"/></a>
	{else}
	{$banner.html}
	{/if}	
	</div>
{/if}
</div>
