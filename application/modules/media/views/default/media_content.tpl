{strip}
{if $media.upload_gid == 'gallery_video'}
	{if $media.media_video_data.status == 'start'}
		<div class="pos-rel">
			<div class="center lh0 pos-rel">
				{l i='text_media_photo' gid='media' type='button' assign='text_media_photo' replace_array=$media}
				<img data-image-src="{$media.video_content.thumbs.great}{$vers}" src="{$media.video_content.thumbs.great}{$vers}" alt="{$text_media_photo}" title="{$text_media_photo}">
				<div id="next_media" class="load_content_right media_view_scroller_right"></div>
				<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
			</div>
			<div class="subinfo box-sizing">
				<p>{l i='video_wait_converting' gid='media'}</p>
				{if $media.id_parent || !$is_user_media_owner}
					{if $media.id_parent}
						{if $media.permissions == 0}<p>{l i='permissions_restrict' gid='media'}</p>{/if}
						{if $media.video_content && !$media.media_video}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
					{/if}
					<span>
						{l i='media_owner' gid='media'}:&nbsp;
						{if $media.owner_info.id}
							<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
						{else}
							<span>{$media.owner_info.output_name}</span>
						{/if}
					</span>
				{/if}
			</div>
		</div>
	{else}
		<div class="plr50 pos-rel">
			<div style="width: {$media.video_content.width}px;" class="center-block">
				{$media.video_content.embed}
			</div>
			<div id="next_media" class="load_content_right media_view_scroller_right"></div>
			<div id="prev_media" class="load_content_left media_view_scroller_left"></div>
		</div>
		{if !$is_user_media_owner}
			<div>
				{l i='media_owner' gid='media'}:&nbsp;
				{if $media.owner_info.id}
					<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
				{else}
					<span>{$media.owner_info.output_name}</span>
				{/if}
			</div>
		{/if}
	{/if}
{elseif $media.upload_gid == 'gallery_image'}
	{l i='text_media_photo' gid='media' type='button' assign='text_media_photo' replace_array=$media}
	<div class="pos-rel">
		<div class="center lh0">
			<div class="photo-edit hide" data-area="recrop">
				<div class="source-box">
					<div id="photo_source_recrop_box" class="photo-source-box">
						<img data-image-src="{$media.media.mediafile.file_url}{$vers}" src="{$media.media.mediafile.file_url}{$vers}" id="photo_source_recrop" alt="{$text_media_photo}" title="{$text_media_photo}">
					</div>
					<div class="ptb5 oh tab-submenu" id="recrop_menu">
						<ul class="fleft" id="photo_sizes"></ul>
						<ul class="fright">
							<li><span data-section="view">{l i="view" gid="media"}</span></li>
						</ul>
						<ul class="fright ">
							<li><i id="photo_rotate_left" class="fa-rotate-left edge icon-medium hover"></i></li>
							<li class="mr20"><i id="photo_rotate_right" class="fa-rotate-right edge icon-medium hover"></i></li>
						</ul>
					</div>
				</div>
			</div>

			<div data-area="view">
				<img data-image-src="{$media.media.mediafile.thumbs.grand}{$vers}" src="{$media.media.mediafile.thumbs.grand}{$vers}" id="photo{$media.id}" alt="{$text_media_photo}" title="{$text_media_photo}">
				<div id="next_media" class="load_content_right"></div>
				<div id="prev_media" class="load_content_left"></div>
			</div>
		</div>

		{if $media.id_parent || !$is_user_media_owner}
			<div class="subinfo box-sizing">
				{if $media.id_parent}
					{if $media.permissions == 0}<p>{l i='permissions_restrict' gid='media'}</p>{/if}
					{if $media.media && !$media.mediafile}<p>{l i='media_deleted_by_owner' gid='media'}</p>{/if}
				{/if}
				<span>
					{l i='media_owner' gid='media'}:&nbsp;
					{if $media.owner_info.id}
						<a href="{seolink module='users' method='view' data=$media.owner_info}">{$media.owner_info.output_name}</a>
					{else}
						<span>{$media.owner_info.output_name}</span>
					{/if}
				</span>
			</div>
		{/if}
	</div>
{/if}

<div class="media-preloader hide" id="media_preloader"></div>

<div>
	<div class="ptb5 oh tab-submenu" data-area="view">
		<div class="fleft">
			{$media.date_add|date_format:$date_formats.date_time_format}
			<span class="ml20">
				{block name='like_block' module='likes' gid='media'.$media.id type=button}
			</span>
			{if !$is_user_media_owner}
				<span class="ml20">
					<span title="{l i='favorites' gid='media'}" class="to_favorites pointer {if $in_favorites} active{/if}" data-id="{$default_album.id}">
						<i class="{if $in_favorites}fa-star g{else}fa-star-empty{/if} pr5 status-icon"></i>
					</span>
				</span>
				<span class="ml20">
					{block name='mark_as_spam_block' module='spam' object_id=$media.id type_gid='media_object' template='minibutton'}
				</span>
			{/if}
			
		</div>
		<div class="fright">
			<ul id="media_menu">
				<li class="active"><span data-section="comments">{l i="comments" gid="media"}</span></li>
				{if $is_user_media_owner}<li><span data-section="access">{l i="access" gid="media"}</span></li>{/if}
				<li><span data-section="albums">{l i="albums" gid="media"}</span></li>
				{if $is_user_media_owner && $media.upload_gid == 'gallery_image'}<li><span data-section="recrop">{l i="recrop" gid="media"}</span></li>{/if}
				{capture assign='aviary_photo_source'}{strip}{literal}
					$('#photo{/literal}{$media.id}{literal}').prop('src')
				{/literal}{/strip}{/capture}
				{capture assign='aviary_save_callback'}{strip}{literal}
					function(imageID, newURL){
						var error_obj = new Errors();
						error_obj.show_error_block('{/literal}{strip}
							{l i='image_update_success' gid='media' type='js'}
							{/strip}{literal}', 'success');
						
						var img = document.getElementById(imageID);
						img.src = newURL;
									
						var photo_source = $('#photo{/literal}{$media.id}{literal}');
						photo_source.prop({src: newURL+'?'+(new Date().getTime())});
					}
				{/literal}{/strip}{/capture}
				{capture assign='aviary_editor_button'}
					{block name='aviary_editor_button' module='aviary' 
							id='photo'+$media.id 
							source=$aviary_photo_source 
							module_gid='media' 
							post_data=$aviary_post_data 
							save_callback=$aviary_save_callback}
				{/capture}
				{if $aviary_editor_button}<li><span data-section="aviary">{$aviary_editor_button}</span></li>{/if}
			</ul>
		</div>
	</div>
	{if $is_user_media_owner}
		<div class="contenteditable mt5" title="{l i='edit_description' gid='media' type='button'}">
			<div contenteditable>
				{if $media.description}{$media.description|nl2br}{/if}
			</div>
			<i class="edge icon- hover active"></i>
		</div>
	{else}
		{if $media.description}
			<div>{$media.description|nl2br}</div>
		{/if}
	{/if}
</div>


<div id="media_sections" class="pt10">
	<div data-section="comments">
		{block name='comments_form' module='comments' gid=media id_obj=$media.id hidden=0 max_height=500}
	</div>

	{if $is_user_media_owner}
		<div data-section="access" class="hide">
			<div class="h2">{l i='field_permitted_for' gid='media'}</div>
			{if !$is_user_media_owner}
				<div class="h3 error-text">{l i='only_owner_access' gid='media'}</div>
			{/if}
			<div class="perm">
				{ld gid='media' i='permissions'}
				<ul>
					{foreach item=item key=key from=$ld_permissions.option}
						<li><label><input type="radio"{if !$is_user_media_owner} disabled{/if} name="permissions" id="permissions" value="{$key}" {if $media.permissions eq $key} checked{/if}> {$item}</label></li>
					{/foreach}
				</ul>
			</div>
			{if $is_user_media_owner}
				<input type="button" class="btn" value="{l i='btn_apply' gid='start'}" name="save_permissions" id="save_permissions">
			{/if}
		</div>
	{/if}

	<div data-section="albums" class="hide"></div>
</div>
{/strip}
