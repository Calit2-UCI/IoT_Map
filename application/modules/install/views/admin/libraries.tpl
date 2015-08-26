{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Library</th>
	<th >Name</th>
	<th class="w100">Version</th>
	<th class="w100">Installed</th>
</tr>
{foreach item=item key=key from=$libraries}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="center">
	{if $item.update}
	<font class="req">{$item.version}.00</font> <br><a href="{$site_url}admin/install/library_update_install/{$item.gid}">Update</a>
	{else}{$item.version}.00{/if}
	</td>
	<td class="center date">{$item.date_add|date_format:$page_data.date_format}</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">No libraries</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}