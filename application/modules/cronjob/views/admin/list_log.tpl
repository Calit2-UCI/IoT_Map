{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/cronjob/delete_log/{$cron_data.id}">{l i='link_clear_log' gid='cronjob'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">{l i='field_date_execute' gid='cronjob'}</th>
	<th>{l i='field_output' gid='cronjob'}</th>
	<th class="w150">{l i='field_errors' gid='cronjob'}</th>
	<th class="w150">{l i='field_execution_time' gid='cronjob'}</th>
</tr>
{foreach item=item from=$logs}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.date_add|date_format:$page_data.date_format}</td>
	<td>{$item.output}</td>
	<td>{$item.errors}</td>
	<td class="center">{$item.execution_time} {l i='crontab_sec' gid='cronjob'}</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_log' gid='cronjob'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}