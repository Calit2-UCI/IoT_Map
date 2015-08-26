{strip}
{foreach item=item key=key from=$albums}
	<div class="item album-cover" data-album-id="{$item.id}">
		<div class="album-bg"></div>
		<div class="user">
			<div class="photo">
				<img class="pointer" data-click="album" src="{if $item.mediafile.media}{$item.mediafile.media.mediafile.thumbs.big}{elseif $item.mediafile.video_content}{$item.mediafile.video_content.thumbs.big}{/if}" alt="{$item.name}" title="{$item.name}" />
				{if $item.mediafile.video_content}<div class="overlay-icon pointer" data-click="album"><i class="icon-play-sign w icon-4x opacity60"></i></div>{/if}
				{if $is_user_album_owner}<a href="{$site_url}media/delete_album/{$item.id}" class="delete-media plr5" data-album-id="{$item.id}"><i class="icon-remove w icon-big"></i></a>{/if}
				{if $item.description || $is_user_album_owner}
					<div class="info">
						<div class="info-icons">
							<s title="{/strip}{$item.description}{strip}">{$item.description|nl2br}</s>
							{if $is_user_album_owner}<a href="{$site_url}media/edit_album/{$item.id}" class="edit-album fright"><i class="icon-pencil edge w"></i></a>{/if}
						</div>
					</div>
				{/if}
			</div>
		</div>
		<div class="subinfo">
			<div class="text-overflow" title="{$item.name}">{$item.name}</div>
			<div class="text-overflow">{l i='album_items' gid='media'}: {$item[$albums_count_field]}</div>
		</div>
	</div>
{foreachelse}
	<div class="center">{l i='no_albums' gid='media'}</div>
	{if $is_user_album_owner}
		<div class="center pl10 mt10">
			<span class="pointer link-r-margin" id="create_album_button"><span class="a">{l i='create_album' gid='media'}</span></span>
			<span class="hide" id="create_album_container">
				<span class="input-w-btn">
					<input type='text' name='album_name' id='album_name'>
					<button id="save_album"><i class="icon-ok w"></i></button>
				</span>
			</span>
		</div>
	{/if}
{/foreach}
{/strip}

{if $albums_page == 1}
	<script type='text/javascript'>{literal}
			$(function(){
			loadScripts(
				"{/literal}{js file='albums.js' module='media' return='path'}{literal}", 
				function(){
					albums_list = new albums({
						siteUrl: site_url,
						contentDiv: '#gallery_content',
						edit_album_success_request: function(){
							mediagallery.properties.galleryContentPage = 1,
							mediagallery.properties.all_loaded = 0;
							mediagallery.load_content(1);
							error_object.show_error_block('{/literal}{l i="album_update_success" gid="media"}{literal}', 'success');
							this.windowObj.hide_load_block();
						},
					});
				},
				['albums_list'],
				{async: false}
			);
			});
	{/literal}</script>
{/if}
