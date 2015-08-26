<div class="h2">{l i='photo_in_common_albums' gid='media'}</div>
<div class="mtb10 albums" id="common_albums{if !$is_user_media_owner}_view{/if}">
	{foreach item=item key=key from=$media_albums.common}
		<div class="album album-item{if !$is_user_media_user || !$is_user_media_owner} disabled{/if}{in_array array=$media_in_album match=$item.id returnvalue=' active'}" id="common_album_{$item.id}"><i class="{in_array array=$media_in_album match=$item.id returnvalue='fa-check' elsereturnvalue='fa-check-empty'} status-icon"></i><span title="{$item.name}">{$item.name}</span></div>
	{foreachelse}
		{l i='no_common_albums' gid='media'}
	{/foreach}
</div>
{if $user_session_data.user_id}
	<div class="h2">{l i='you_added_this_photo_into_albums' gid='media'}</div>
	<div class="mtb10 albums" id='user_albums'>
		{foreach item=item key=key from=$media_albums.user}
			<div class="album album-item{in_array array=$media_in_album match=$item.id returnvalue=' active'}" id='user_album_{$item.id}'><i class="{in_array array=$media_in_album match=$item.id returnvalue='fa-check' elsereturnvalue='fa-check-empty'} status-icon"></i><span title="{$item.name}">{$item.name}</span></div>
		{foreachelse}
			<span id='no_user_albums'>{l i='no_user_albums' gid='media'}</span>
		{/foreach}
	</div>
	<div class="mtb10">
		<span class="pointer link-r-margin" id="create_album_button"><span class="a">{l i='create_album' gid='media'}</span></span>
		<span class="hide" id="create_album_container">
			<span class="input-w-btn">
				<input type='text' name='album_name' id='album_name'>
				<button id="save_album"><i class="fa-ok w"></i></button>
			</span>
		</span>
	</div>
{/if}
<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='albums.js' module='media' return='path'}{literal}",
			function(){
				albums_obj = new albums({siteUrl: site_url, create_album_success_request: function(){}});
			},
			['albums_obj'],
			{async: false}
		);
	});
</script>{/literal}
