{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_banners_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a id="banners_link_add" href="{$site_url}admin/banners/edit">{l i='link_add_banner' gid='banners'}</a></div></li>
		<li><div class="l"><a id="banners_link_update" href="{$site_url}admin/banners/update_hour_statistic">{l i='update_statistic_manually' gid='banners'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class='menu-level3'>
	<ul>
		<li{if $page_data.view_type eq 'admin'} class="active"{/if}><a href="{$site_url}admin/banners/index/admin">{l i='filter_admin_banners' gid='banners'}</a></li>
		<li{if $page_data.view_type eq 'user'} class="active"{/if}><a href="{$site_url}admin/banners/index/user">{l i='filter_users_banners' gid='banners'}</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_number' gid='banners'}</th>
	<th>&nbsp;</th>
	<th>{l i='field_name' gid='banners'}</th>
	<th>{l i='field_location' gid='banners'}</th>
	<th>{l i='field_limitations' gid='banners'}</th>
	<th>&nbsp;</th>
</tr>
{foreach from=$banners item=banner}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first w20 center">{$counter}</td>
	<td class="center view-banner">
		<a href="javascript:void(0)" id="view_{$banner.id}"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" alt="{l i='link_view_banner' gid='banners'}" title="{l i='link_view_banner' gid='banners'}"></a>
		<div id="view_{$banner.id}_content" class="preview"></div>
	</td>
	<td>{$banner.name} {if $page_data.view_type eq 'user'}({$banner.user.output_name}){/if}</td>
	<td class="w150 center">
		{if $banner.banner_place_obj}
		{$banner.banner_place_obj.name} {$banner.banner_place_obj.width}X{$banner.banner_place_obj.height}
		{/if}
	</td>
	<td class="w150 center">&nbsp;
		{if $banner.approve eq -1}
			{l i='declined' gid='banners'}
		{else}
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
			{if !$limit}{if $banner.status}{l i='never_stop' gid='banners'}{else}{l i='text_banner_inactivated' gid='banners'}{/if}{/if}
		{/if}
	</td>
	<td class="w150 icons">
		<a href='{$site_url}admin/banners/statistic/{$banner.id}/'><img src="{$site_root}{$img_folder}icon-stats.png" width="16" height="16" alt="{l i='link_view_statistic' gid='banners'}" title="{l i='link_view_statistic' gid='banners'}"></a>
	{if $page_data.view_type eq "admin"}
		{if $banner.status == 1}
		<a class="tooltip" id="banner_deactivate_{$banner.id}" href='{$site_url}admin/banners/activate/{$banner.id}/0'><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="{l i='link_deactivate_banner' gid='banners'}" title="{l i='link_deactivate_banner' gid='banners'}"></a>
		<span id="span_banner_deactivate_{$banner.id}" class="hide"><div class="tooltip-info">
			{if $banner.views_left}<b>{l i='shows_left' gid='banners'}:</b> {$banner.views_left}<br>{/if}
			{if $banner.clicks_left}<b>{l i='clicks_left' gid='banners'}:</b> {$banner.clicks_left}<br>{/if}
			{if $banner.expiration_date and $banner.expiration_date != '0000-00-00 00:00:00'}<b>{l i='till' gid='banners'}:</b> {$banner.expiration_date|date_format:$page_data.date_format}<br>{/if}
		</div></span>
		{else}
		<a href='{$site_url}admin/banners/activate/{$banner.id}/1'><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="{l i='link_activate_banner' gid='banners'}" title="{l i='link_activate_banner' gid='banners'}"></a>
		{/if}
	{/if}
	{if $page_data.view_type eq "user" && $banner.status eq 1}
		<a href='{$site_url}admin/banners/view/{$banner.id}'><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" alt="{l i='link_view_banner' gid='banners'}" title="{l i='link_view_banner' gid='banners'}"></a>
	{else}
		{if $banner.approve ne -1}
		<a href='{$site_url}admin/banners/edit/{$banner.id}'><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_edit_banner' gid='banners'}" title="{l i='link_edit_banner' gid='banners'}"></a>
		{/if}
	{/if}
		<a href="{$site_url}admin/banners/delete/{$banner.id}" onClick="return confirm('{l i='note_delete_banner' gid='banners' type='js'}');"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='link_delete_banner' gid='banners'}" title="{l i='link_delete_banner' gid='banners'}"></a>
	{if $page_data.view_type eq "user" && $banner.approve eq 0}
		<a href="{$site_url}admin/banners/approve/{$banner.id}/1"><img src="{$site_root}{$img_folder}icon-approve.png" width="16" height="16" border="0" alt="{l i='link_banner_approve' gid='banners'}" title="{l i='link_banner_approve' gid='banners'}"></a>
		<a href="{$site_url}admin/banners/approve/{$banner.id}/-1"><img src="{$site_root}{$img_folder}icon-decline.png" width="16" height="16" border="0" alt="{l i='link_banner_decline' gid='banners'}" title="{l i='link_banner_decline' gid='banners'}"></a>
	{/if}
	</td>
</tr>
{foreachelse}
<tr><td colspan="8" class="center">{l i='no_banners' gid='banners'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{js file='easyTooltip.min.js'}
<script type='text/javascript'>
{literal}
$(function(){
	var tt_id;
	$(".tooltip").each(function(){
		tt_id = 'span_' + $(this).attr('id');
		if($('#' + tt_id + '>div').html().trim()) {
			$(this).easyTooltip({
				useElement: tt_id
			});
		};
	});
	$("td.view-banner > a").click(function(){
		$("td.view-banner > .preview").html('');
		var banner_id =  $(this).attr('id').replace(/\D+/g, '');
		$.ajax({
			url: '{/literal}{$site_url}{literal}admin/banners/preview/' + banner_id,
			success: function(data){
				$('#view_' + banner_id + '_content').html(data).show();
			}
		});
	});
	$(document).click(function(){$("td.view-banner > .preview").html('')});
});
{/literal}
</script>
{include file="footer.tpl"}
