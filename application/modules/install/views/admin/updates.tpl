{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Module</th>
	<th >Description</th>
	<th class="w70">Old version</th>
	<th class="w70">New version</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item key=key from=$updates}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.module_gid}</b></td>
	<td>
		<b>{$item.update_name}</b><br>
		{$item.update_description}
	</td>
	<td class="center">{$item.version_from}</td>
	<td class="center">{$item.version_to}</td>
	<td class="icons">
		<a href="{$site_url}admin/install/update_install/{$item.module_gid}/{$key}">Install</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">No updates</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}