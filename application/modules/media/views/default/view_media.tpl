{strip}
<div class="content-block load_content">
	<h1>{l i='view_media' gid='media'} <span id="media_position"></span></h1>
	<div class="m20 oh">
		<div class="edit-media-content" id="image_content"></div>
	</div>
	<div class="clr"></div>
</div>
{/strip}

<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='edit_media.js' module='media' return='path'}{literal}", 
			function(){
				ep = new editMedia({
					siteUrl: site_url,
					mediaId: '{/literal}{$media_id}{literal}',
					galleryContentParam: '{/literal}{$param}{literal}',
					albumId: '{/literal}{$album_id}{literal}',
					gallery_name: '{/literal}{if $gallery_name}{$gallery_name}{else}mediagallery{/if}{literal}',
					selections: {/literal}{json_encode data=$selections}{literal},
					success_request: function(message) {
						if (message){
							error_object.show_error_block(message, 'success');
						} else {
							error_object.show_error_block('{/literal}{l i="image_update_success" gid="media"}{literal}', 'success');
						}
					},
					fail_request: function(message) {error_object.show_error_block(message, 'error');},
					error_in_adding_to_favorites: '{/literal}{l i="error_in_adding_to_favorites" gid="media"}{literal}', 
					success_add_to_favorites: '{/literal}{l i="success_add_to_favorites" gid="media"}{literal}', 
					rand_param: {/literal}{$rand}{literal},
				});
			},
			['ep'],
			{async: true, cache: false}
		);
	});
{/literal}</script>