{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Module</th>
	<th >Description</th>
	<th class="w50">Version</th>
	<th class="w100">Installed</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item key=key from=$modules}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.module_gid}</b></td>
	<td>
		<b>{$item.module_name}</b><br>
		{$item.module_description}
		{if $item.dependencies}<br><br><b>Depend from:</b> {foreach item=depend key=dmod from=$item.dependencies}{/foreach}{/if}
	</td>
	<td class="center">{$item.version}</td>
	<td class="date">{$item.date_add|date_format:$page_data.date_format}</td>
	<td class="icons">
		<a href="{$site_url}admin/install/module_view/{$item.module_gid}"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" border="0" alt=""></a>
		<a href="{$site_url}admin/install/module_delete/{$item.module_gid}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt=""></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">No modules</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}
