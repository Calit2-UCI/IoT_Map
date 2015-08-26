{include file="header.tpl"}

<div class="content-block">
	<h1>{l i='header_my_banners' gid='banners'}</h1>
	<div class="content-value">
		<a class="btn-link fright" href="{$site_url}banners/edit"><i class="fa-file-text-alt icon-big edge hover zoom30"><i class="fa-mini-stack icon-plus bottomright"></i></i><i>{l i='link_add_banner' gid='banners' type='button'}</i></a>
		<br><br>
		<table class="list vmiddle">
		<tr id="sorter_block">
			<th class="w30">{l i='field_number' gid='banners'}</th>
			<th class="w30">&nbsp;</th>
			<th>{l i='field_name' gid='banners'}</th>
			<th>{l i='field_approve' gid='banners'}</th>
			<th class="w150">&nbsp;</th>
		</tr>
		{foreach from=$banners item=banner}
			{counter print=false assign=counter}
			<tr>
				<td class="centered">{$counter}</td>
				<td class="view-banner">
					<a href='#' onclick="return false;" id="view_{$banner.id}" class="fa-eye-open icon-big edge hover zoom10" title="{l i='link_view_banner' gid='banners'}"></a>
					<div id="view_{$banner.id}_content" style="display: none">
						{if $banner.banner_type == 1}<img src="{$banner.media.banner_image.file_url}" width="{$banner.banner_place_obj.width}" height="{$banner.banner_place_obj.height}" alt="{$banner.alt_text|escape}" />{else}{$banner.html}{/if}
					</div>
				</td>
				<td>
					<b>{$banner.name}
					{if $banner.banner_place_obj}
					({$banner.banner_place_obj.name} {$banner.banner_place_obj.width}X{$banner.banner_place_obj.height})
					{/if}</b><br>
					{assign var="limit" value=''}
					{if $banner.number_of_views}
					{assign var="limit" value=true}
					{l i='shows' gid='banners'} - {$banner.number_of_views}
					<br/>
					{/if}
					{if $banner.number_of_clicks}
					{assign var="limit" value=true}
					{l i='clicks' gid='banners'} - {$banner.number_of_clicks}
					<br/>
					{/if}
					{if $banner.expiration_date and $banner.expiration_date != '0000-00-00 00:00:00'}
					{assign var="limit" value=true}
					{l i='till' gid='banners'} - {$banner.expiration_date|date_format:$page_data.date_format}
					{/if}
					{if !$limit}{if $banner.status}{l i='never_stop' gid='banners'}{else}&nbsp;{/if}{/if}
				</td>
				<td>
					{if $banner.approve eq '1'}<span class="status"><i class="fa-ok-sign icon-big"></i>&nbsp;{if $banner.status}{l i='text_banner_activated' gid='banners'}{else}{l i='approved' gid='banners'}{/if}</span>
					{elseif $banner.approve eq '-1'}<span class="status"><i class="fa-ban-circle icon-big"></i>&nbsp;{l i='declined' gid='banners'}</span>
					{else}<span class="status wait"><i class="fa-time g icon-big"></i>&nbsp;{l i='not_approved' gid='banners'}</span>{/if}
				</td>
				<td class="r righted">
					{if $banner.approve eq '1'}
						{if !$banner.status}<a href="{$site_url}banners/activate/{$banner.id}" class="fa-play icon-big edge hover mr10" title="{l i='link_banner_activate' gid='banners'}"></a>{/if}
						<a href="{$site_url}banners/statistic/{$banner.id}" class="fa-bar-chart icon-big edge hover mr10" title="{l i='link_banner_stat' gid='banners' type='button'}"></a>
					{/if}
					<a href="javascript:;" onclick="javascript: if(!confirm('{l i='note_delete_banner' gid='banners' type='js'}')) return false; locationHref('{$site_url}banners/delete/{$banner.id}');" class="fa-trash icon-big edge hover zoom30"></a>
				</td>
			</tr>
		{foreachelse}
			<tr>
				<td class="empty" colspan="5">{l i='no_banners' gid='banners'}</td>
			</tr>
		{/foreach}
		</table>
		
		{pagination data=$page_data type='full'}
		<br>
	</div>
</div>

<script type='text/javascript'>{literal}
	$(function(){
		loadScripts(
			"{/literal}{js file='easyTooltip.min.js' return='path'}{literal}", 
			function(){
				$("td.view-banner > a").each(function(){
					var id = $(this).attr('id')+'_content';
					$(this).easyTooltip({useElement: id});
				});
			}
		);
	});
</script>{/literal}

<div class="clr"></div>
{include file="footer.tpl"}
