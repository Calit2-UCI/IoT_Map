{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/cronjob/edit">{l i='link_add_cronjob' gid='cronjob'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/cronjob/index/all">{l i='filter_all_cronjob' gid='cronjob'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'not_active'}active{/if}{if !$filter_data.not_active} hide{/if}"><a href="{$site_url}admin/cronjob/index/not_active">{l i='filter_not_active_cronjob' gid='cronjob'} ({$filter_data.not_active})</a></li>
		<li class="{if $filter eq 'active'}active{/if}{if !$filter_data.active} hide{/if}"><a href="{$site_url}admin/cronjob/index/active">{l i='filter_active_cronjob' gid='cronjob'} ({$filter_data.active})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_cron_title' gid='cronjob'}</th>
	<th class="w100">{l i='field_cron_tab' gid='cronjob'}</th>
	<th class="w150">{l i='field_date_add' gid='cronjob'} / {l i='field_date_execute' gid='cronjob'}</th>
	<th>{l i='field_expiried' gid='cronjob'}</th>
	<th>{l i='field_in_process' gid='cronjob'}</th>
	<th class="w150">&nbsp;</th>
</tr>
{foreach item=item from=$crontab}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td>{$item.cron_tab}</td>
	<td class="center">{$item.date_add|date_format:$page_data.date_format} / {if $item.date_execute != '0000-00-00 00:00:00'}{$item.date_execute|date_format:$page_data.date_format}{/if}</td>
	<td class="center">{if $item.expiried}{l i='crontab_expiried' gid='cronjob'}{/if}</td>
	<td class="center">{if $item.in_process}{l i='crontab_in_process' gid='cronjob'}{else}&nbsp;{/if}</td>
	<td class="icons">
		{if $item.status}
		<a href="{$site_url}admin/cronjob/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_cron' gid='cronjob'}" title="{l i='link_deactivate_cron' gid='cronjob'}"></a>
		{else}
		<a href="{$site_url}admin/cronjob/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_cron' gid='cronjob'}" title="{l i='link_activate_cron' gid='cronjob'}"></a>
		{/if}
		<a href="{$site_url}admin/cronjob/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_cron' gid='cronjob'}" title="{l i='link_edit_cron' gid='cronjob'}"></a>
		<a href="{$site_url}admin/cronjob/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_cron' gid='cronjob' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_cron' gid='cronjob'}" title="{l i='link_delete_cron' gid='cronjob'}"></a>
		<a href="{$site_url}admin/cronjob/run/{$item.id}"><img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" alt="{l i='link_run_cron' gid='cronjob'}" title="{l i='link_run_cron' gid='cronjob'}"></a>
		<a href="{$site_url}admin/cronjob/log/{$item.id}"><img src="{$site_root}{$img_folder}icon-copy.png" width="16" height="16" border="0" alt="{l i='link_log_cron' gid='cronjob'}" title="{l i='link_log_cron' gid='cronjob'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center">{l i='no_crontabs' gid='cronjob'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
