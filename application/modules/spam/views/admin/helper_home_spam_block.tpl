<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first" colspan=2>{l i='stat_header_spam' gid='spam'}</th>
	</tr>
	{if $stat_spam.index_method}
	{foreach item=item from=$stat_spam.types}
	{assign var=stat_header value="stat_header_spam_`$item.gid`"}
	{counter print=false assign='counter'}
	<tr {if $counter is div by 2}class="zebra"{/if}>
		<td class="first"><a id="spam_link_{$item.gid}" href="{$site_url}admin/spam/index/{$item.gid}">{l i=$stat_header gid='spam'}</a></td>
		<td class="w30"><a id="spam_link_{$item.gid}_num" href="{$site_url}admin/spam/index/{$item.gid}">{$item.obj_count}</a></td>
	</tr>
	{/foreach}
	{/if}
</table>

