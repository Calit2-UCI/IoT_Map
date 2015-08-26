{include file="header.tpl" load_type='ui'}
{strip}
<div class="content-block">
	<h1>{seotag tag='header_text'}</h1>

	<div class="tab-submenu bg-highlight_bg">
		<div class="ib">
			<ul id="gallery_filters">
				<li data-param="all"{if $gallery_param == 'all'} class="active"{/if}><a href="{seolink module='media' method='all'}"><span>{l i='all' gid='media'}</span></a></li>
				<li data-param="photo"{if $gallery_param == 'photo'} class="active"{/if}><a href="{seolink module='media' method='photo'}"><span>{l i='photo' gid='media'}</span></a></li>
				<li data-param="video"{if $gallery_param == 'video'} class="active"{/if}><a href="{seolink module='media' method='video'}"><span>{l i='video' gid='media'}</span></a></li>
				<li data-param="albums"{if $gallery_param == 'albums'} class="active"{/if}><a href="{seolink module='media' method='albums'}"><span>{l i='albums' gid='media'}</span></a></li>
			</ul>
			<span id="gallery_albums"{if $gallery_param != 'albums'} class="hide"{/if}>{$albums}</span>
			<span id="gallery_media_sorter"{if $gallery_param == 'albums'} class="hide"{/if}>
				<select>
					{foreach item=item key=key from=$media_sorter.links}
						<option value="{$key}"{if $key eq $media_sorter.order} selected{/if}>{$item}</option>
					{/foreach}
				</select>
				<i data-role="sorter-dir" class="fa-long-arrow {if $media_sorter.direction eq 'ASC'}up{else}down{/if} icon-big pointer plr5"></i>
			</span>
		</div>
		<div class="fright">
			<ul>
				<li><s id="add_photo" class="a btn-link"><i class="fa-camera icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i>{l i='add_photo' gid='media'}</i></s></li>
				<li><s id="add_video" class="a btn-link"><i class="fa-facetime-video icon-big edge hover"><i class="fa-mini-stack fa-plus bottomright"></i></i><i>{l i='add_video' gid='media'}</i></s></li>
			</ul>
		</div>
	</div>
	
	<div class="edit_block">
		<div id="gallery" class="gallery user-gallery medium"></div>
	</div>
</div>
	
<div class="clr"></div>
{/strip}

<script>{literal}
	$(function(){
		loadScripts(
			{/literal}["{js module='media' file='gallery.js' return='path'}", "{js file='media.js' module='media' return='path'}"]{literal}, 
			function(){
				sitegallery = new gallery({
					id: 'gallery',
					site_url: site_url,
					button_title: '{/literal}{l i='show_more' gid='media'}{literal}',
					load_on_scroll: true,
					id_category: 0
				});
				mediagallery = new media({
					siteUrl: site_url,
					galleryContentPage: 1,
					idUser: 0,
					all_loaded: 1,
					lang_delete_confirm: "{/literal}{l i='delete_confirm' gid='media'}{literal}",
					galleryContentDiv: 'gallery',
					post_data: {filter_duplicate: 1},
					load_on_scroll: false,
					sorterId: 'gallery_media_sorter',
					is_guest: '{/literal}{$is_guest}{literal}'
				});
				sitegallery.init().load();
			},
			['sitegallery', 'mediagallery'], 
			{async: false}
		);
	});
</script>{/literal}

{include file="footer.tpl"}
