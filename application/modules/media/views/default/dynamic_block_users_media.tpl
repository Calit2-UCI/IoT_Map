{strip}
{switch from=$dynamic_block_users_media_data.view}
	{case value='big_thumbs'}{assign var=block_class value='big'}{assign var=thumb_name value='great'}
	{case value='medium_thumbs'}{assign var=block_class value='medium'}{assign var=thumb_name value='big'}
	{case value='small_thumbs'}{assign var=block_class value='small'}{assign var=thumb_name value='middle'}
	{case value='small_thumbs_w_descr'}{assign var=block_class value='small w-descr'}{assign var=thumb_name value='middle'}
	{case value='carousel'}{assign var=block_class value='small'}{assign var=thumb_name value='middle'}
	{case value='carousel_w_descr'}{assign var=block_class value='small w-descr'}{assign var=thumb_name value='middle'}
	{case}{assign var=block_class value='medium'}{assign var=thumb_name value='big'}
{/switch}
<div id="dynamic_block_gallery_{$dynamic_block_users_media_data.rand}">
	<h2 class="text-overflow" title="{$dynamic_block_users_media_data.title|escape}">{$dynamic_block_users_media_data.title}</h2>
	{if $dynamic_block_users_media_data.view == 'carousel' || $dynamic_block_users_media_data.view == 'carousel_w_descr'}
		{block name='media_carousel' module='media' media=$dynamic_block_users_media_data.media.media scroll='auto' class=$block_class thumb_name=$thumb_name}
	{else}
		<div id="dynamic_block_users_media_{$dynamic_block_users_media_data.rand}" class="user-gallery {$block_class}">
			{foreach item=item from=$dynamic_block_users_media_data.media.media}
				<div class="item">
					<div class="user">
						<div class="photo">
							{if $item.video_content}<div class="overlay-icon pointer" data-click="view-media" data-id-media="{$item.id}" data-place="site_gallery"><i class="icon-play-sign w icon-4x opacity60"></i></div>{/if}
							{l i='text_media_photo' gid='media' type='button' assign='text_media_photo' replace_array=$item}
							<img class="pointer" data-click="view-media" data-id-media="{$item.id}" data-place="site_gallery" src="{if $item.media}{$item.media.mediafile.thumbs[$thumb_name]}{elseif $item.video_content}{$item.video_content.thumbs[$thumb_name]}{/if}" alt="{$text_media_photo}" title="{$text_media_photo}" />
							<div class="info">
								<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item.user_info}">{$item.user_info.output_name}</a>, {$item.user_info.age}</div>
								{if $item.user_info.location}<div class="text-overflow">{$item.user_info.location}</div>{/if}
							</div>
						</div>
					</div>
					<div class="descr hide">
						<div><a href="{seolink module='users' method='view' data=$item.user_info}">{$item.user_info.output_name}</a>, {$item.user_info.age}</div>
						{if $item.user_info.location}<div>{$item.user_info.location}</div>{/if}
					</div>
				</div>
			{/foreach}
		</div>
	{/if}
</div>
{/strip}

<script>{literal}
	$('#dynamic_block_users_media_{/literal}{$dynamic_block_users_media_data.rand}{literal}').not('.w-descr')
		.off('mouseenter', '.photo').on('mouseenter', '.photo', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave', '.photo').on('mouseleave', '.photo', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
		
	$(document).one('pjax:start', function(){
		$('#dynamic_block_users_media_{/literal}{$dynamic_block_users_media_data.rand}{literal}').not('.w-descr').off('mouseenter', '.photo').off('mouseleave', '.photo');
	});
	
	$(function(){
		loadScripts(
			"{/literal}{js file='media.js' module='media' return='path'}{literal}", 
			function(){
				mediagallery{/literal}{$dynamic_block_users_media_data.rand}{literal} = new media({
					siteUrl: site_url,
					galleryContentPage: 1,
					galleryContentParam: '{/literal}{$dynamic_block_users_media_data.type}{literal}',
					galleryContentDiv: 'dynamic_block_gallery_{/literal}{$dynamic_block_users_media_data.rand}{literal}',
					idUser: 0,
					all_loaded: 1,
					load_on_scroll: false,
					lang_delete_confirm: "{/literal}{l i='delete_confirm' gid='media'}{literal}",
					gallery_name: 'mediagallery{/literal}{$dynamic_block_users_media_data.rand}{literal}'
				});
			},
			['mediagallery{/literal}{$dynamic_block_users_media_data.rand}{literal}'],
			{async: true}
		);
	});
</script>{/literal}
