{assign var=thumb_name value=$recent_thumb.name}
<div class="highlight p10 mb20 fltl" id="recent_photos">
    <h1>
		<span class="maxw230 ib text-overflow">{l i='header_recent_photos' gid='media'}</span>
		<span class="fright" id="refresh_recent_photos">
			<i class="fa-refresh icon-big edge hover"></i>
		</span>
	</h1>
    {foreach from=$recent_photos_data.media item=item}
		<span class="a" data-click="view-media" data-user-id="{$item.id_owner}" data-id-media="{$item.id}">
		   <div class="fleft small ml5">
				{l i='text_media_photo' gid='media' type='button' assign='text_media_photo' replace_array=$item}
				<img class="small" src="{$item.media.mediafile.thumbs[$thumb_name]}" width="{$recent_thumb.width}" alt="{$text_media_photo}" title="{$text_media_photo}" />
			</div>
		</span>
    {/foreach}
</div>
<script>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='media.js' module='media' return='path'}{literal}", 
			function(){
				recent_mediagallery = new media({
					siteUrl: site_url,
					gallery_name: 'recent_mediagallery',
					galleryContentPage: 1,
					idUser: 0,
					all_loaded: 1,
					lang_delete_confirm: "{/literal}{l i='delete_confirm' gid='media'}{literal}",
					galleryContentDiv: 'recent_photos',
					post_data: {filter_duplicate: 1},
					load_on_scroll: false,
					sorterId: '',
					direction: 'asc'
				});
			},
			'recent_mediagallery', 
			{async: false}
		);
	});
</script>{/literal}
