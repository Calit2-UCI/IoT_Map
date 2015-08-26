{strip}
<div class="content-block">
	<div class="tab-submenu bg-highlight_bg">
		<div class="ib">
			<ul id="filters">
				{foreach item=item key=key from=$media_filters}
					<li data-param="{$key}" data-history="{$item.link}"{if $gallery_param == $key} class="active"{/if}><span>{$item.name}</span></li>
				{/foreach}
			</ul>
			<span id="album_id_container"{if $gallery_param != 'albums'} class="hide"{/if}>{$albums}</span>
			<span id="media_sorter"{if $gallery_param == 'albums'} class="hide"{/if}>
				<select>
					{foreach item=item key=key from=$media_sorter.links}
						<option value="{$key}"{if $key eq $media_sorter.order} selected{/if}>{$item}</option>
					{/foreach}
				</select>
				<i data-role="sorter-dir" class="fa-long-arrow {if $media_sorter.direction eq 'ASC'}up{else}down{/if} icon-big pointer plr5"></i>
			</span>
		</div>
		{if $is_owner}
			<div class="fright">
				<ul>
					<li><s id="add_photo" class="a btn-link"><i class="fa-camera icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i>{l i='add_photo' gid='media'}</i></s></li>
					<li><s id="add_video" class="a btn-link"><i class="fa-facetime-video icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i>{l i='add_video' gid='media'}</i></s></li>
				</ul>
			</div>
		{/if}
	</div>
	<div id="gallery_content" class="user-gallery media-gallery medium">
		{$content.content}
	</div>
	<div class="clr"></div>
	<div class="media-button-content" {if !$content.have_more}style="display:none;"{/if}><input id="media_button" type="button" value="{l i='show_more' gid='media'}"></div>
</div>
{/strip}

<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='media.js' module='media' return='path'}{literal}",
			function(){
				mediagallery = new media({
					siteUrl: site_url,
					galleryContentPage: {/literal}{$page}{literal},
					galleryContentParam: '{/literal}{$gallery_param}{literal}',
					idUser: {/literal}{$id_user}{literal},
					all_loaded: {/literal}{if $content.have_more}0{else}1{/if}{literal},
					lang_delete_confirm: "{/literal}{l i='delete_confirm' gid='media'}{literal}",
					lang_delete_confirm_album: "{/literal}{l i='delete_confirm_albums' gid='media'}{literal}",
				});
			},
			['mediagallery'],
			{async: true}
		);
	});
</script>{/literal}
