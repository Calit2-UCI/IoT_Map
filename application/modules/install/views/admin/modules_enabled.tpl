{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">Module</th>
	<th >Description</th>
	<th class="w50">Version</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item key=key from=$modules}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.module}</b></td>
	<td><b>{$item.install_name}</b><br>{$item.install_descr}</td>
	<td class="center">{$item.version}</td>
	<td class="icons">
		<a href="{$site_url}admin/install/module_install/{$item.module}">Install</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">No new modules</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}