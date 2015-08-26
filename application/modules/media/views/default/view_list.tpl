{strip}
{if $media_count && $album.media_count > $media_count}
	<div class="fixmargin mtb5">{l i='no_permissions_for_view_part' gid='media'}</div>
{/if}

{foreach item=item key=key from=$media}
	<div class="item">
		<div class="user{if $item.id_user != $item.id_owner} not-owner{/if}">
			<div class="photo">
				<span class="a" data-click="view-media" data-id-media="{$item.id}">
					{if $item.video_content}<div class="overlay-icon pointer"><i class="fa-play-sign w icon-4x opacity60"></i></div>{/if}
					{l i='text_media_photo' gid='media' type='button' assign='text_media_photo' replace_array=$item}
					<img src="{if $item.media}{$item.media.mediafile.thumbs.big}{elseif $item.video_content}{$item.video_content.thumbs.big}{/if}" alt="{$text_media_photo}" title="{$text_media_photo}" />
				</span>
				<div class="info">
					<div class="info-icons">
						{if $item.id_parent && (($item.media && !$item.mediafile) || ($item.video_content && !$item.media_video))}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
						<div>
							<span class="mr10" data-gid="media{$item.id}"><i class="fa-eye-open edge w">&nbsp;</i><span class="view_num">{$item.views}</span></span>
							<span class="mr10">{block name='like_block' module='likes' gid='media'.$item.id type=button btn_class="edge w"}</span>
							{if $item.is_adult}<i class="fa-female edge w">&nbsp;</i><span>18+</span>{/if}
							{if !$item.is_owner}<span style="float:right">{block name='mark_as_spam_block' module='spam' object_id=$item.id type_gid='media_object' template='whitebutton'}</span>{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{foreachelse}
	<div class="fixmargin">{if $album.media_count}{l i='no_permissions_for_view_all' gid='media'}{else}{l i='no_media' gid='media'}{/if}</div>
{/foreach}
{/strip}
