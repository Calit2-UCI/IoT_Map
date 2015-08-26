{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Library</th>
	<th >Name</th>
	<th class="w100">Version</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item key=key from=$libraries}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.gid}</b></td>
	<td>{$item.name}</td>
	<td class="center">{$item.version}.00</td>
	<td class="icons">
		<a href="{$site_url}admin/install/library_install/{$item.gid}">Install</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">No new libraries</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}