{include file="header.tpl"}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100">File</th>
	<th class="first w100">Name</th>
	<th >Description</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item key=key from=$updates}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$key}</b></td>
	<td>{$item.name}</td>
	<td>{$item.description}</td>
	<td class="icons"><a href="{$site_url}admin/install/product_update/{$key}">Install</a></td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">No updates</td></tr>
{/foreach}
</table>

{include file="pagination.tpl"}

{include file="footer.tpl"}
