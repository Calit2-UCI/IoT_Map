{strip}
<div>{$event.date_update|date_format:$date_format}</div>
<div class="ptb5">{if $event.event_type_gid == 'image_upload'}{l i='uploads_new_photos' gid='media'}{elseif $event.event_type_gid == 'video_upload'}{l i='uploads_new_videos' gid='media'}{/if} ({$event.media_count_all})</div>
<div class="user-gallery medium">
	{foreach from=$event.data item=edata key=key}
		<div class="item">
			<div class="user">
				<div class="photo">
					<span class="a spam_content" data-click="view-media" data-user-id="{$edata.id_owner}" data-id-media="{$edata.id}">
						{if $edata.video_content}
							{$edata.video_content.embed}<br/>
						{else}
						<img src="{if $edata.media}{$edata.media.mediafile.thumbs.hgreat}{elseif $edata.video_content}{$edata.video_content.thumbs.big}{/if}" />
						{/if}
					</span>
				</div>
			</div>
		</div>
	{/foreach}
</div>
{/strip}