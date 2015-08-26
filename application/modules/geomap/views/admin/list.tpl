{include file="header.tpl"}
{*<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/geomap/edit">{l i='link_add_geomap' gid='geomap'}</a></div></li>
	</ul>
	&nbsp;
</div>*}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_title' gid='geomap'}</th>
	<th class="w50">{l i='field_status' gid='geomap'}</th>
	<th>{l i='field_regkey' gid='geomap'}</th>
	<th class="w50">{l i='field_link' gid='geomap'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$drivers}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td class="center">
		{if $item.status}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_active_geomap' gid='geomap' type='button'}" title="{l i='link_active_geomap' gid='geomap' type='button'}">
		{else}
		<a href="{$site_url}admin/geomap/activate/{$item.gid}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_geomap' gid='geomap' type='button'}" title="{l i='link_activate_geomap' gid='geomap' type='button'}"></a>
		{/if}
	</td>
	<td>
		{if $item.need_regkey}
			{$item.regkey|truncate:50:"...":true}
		{else}
			{l i='driver_key_notrequired' gid='geomap'}
		{/if}
	</td>
	<td class="center">
		<a href="{$item.link}" target="_blank">
			{if $item.need_regkey}
				{l i='driver_registration' gid='geomap'}
			{else}
				{l i='driver_info' gid='geomap'}
			{/if}
		</a>
	</td>
	<td class="icons">
		{if $item.need_regkey}<a href="{$site_url}admin/geomap/edit/{$item.gid}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_driver' gid='geomap' type='button'}" title="{l i='link_edit_driver' gid='geomap' type='button'}"></a>{/if}
		<a href="{$site_url}admin/geomap/settings/{$item.gid}"><img src="{$site_root}{$img_folder}icon-settings.png" width="16" height="16" border="0" alt="{l i='link_settings_driver' gid='geomap' type='button'}" title="{l i='link_settings_driver' gid='geomap' type='button'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">{l i='no_drivers' gid='geomap'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
