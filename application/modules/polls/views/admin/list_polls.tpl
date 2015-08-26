{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_polls_menu'}

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/polls/edit">{l i='link_add_poll' gid='polls'}</a></div></li>

	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/polls/index/all">{l i='filter_all_polls' gid='polls'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'active'}active{/if}{if !$filter_data.active} hide{/if}"><a href="{$site_url}admin/polls/index/active">{l i='filter_active_polls' gid='polls'} ({$filter_data.active})</a></li>
		<li class="{if $filter eq 'feauture'}active{/if}{if !$filter_data.feauture} hide{/if}"><a href="{$site_url}admin/polls/index/feauture">{l i='filter_feauture_polls' gid='polls'} ({$filter_data.feauture})</a></li>
		<li class="{if $filter eq 'end'}active{/if}{if !$filter_data.end} hide{/if}"><a href="{$site_url}admin/polls/index/end">{l i='filter_end_polls' gid='polls'} ({$filter_data.end})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		{*<th class="first"><input type="checkbox" id="grouping_all"></th>*}
		<th class="first"><a href="{$sort_links.question'}"{if $order eq 'question'} class="{$order_direction|lower}"{/if}>{l i='field_question' gid='polls'}</a></th>
		<th><a href="{$sort_links.language'}"{if $order eq 'language'} class="{$order_direction|lower}"{/if}>{l i='field_language' gid='polls'}</a></th>
		<th><a href="{$sort_links.poll_type'}"{if $order eq 'poll_type'} class="{$order_direction|lower}"{/if}>{l i='field_poll_type' gid='polls'}</a></th>
		<th><a href="{$sort_links.date_start'}"{if $order eq 'date_start'} class="{$order_direction|lower}"{/if}>{l i='field_date_start' gid='polls'}</a></th>
		<th><a href="{$sort_links.date_end'}"{if $order eq 'date_end'} class="{$order_direction|lower}"{/if}>{l i='field_date_end' gid='polls'}</a></th>
		<th class="w150">&nbsp;</th>
	</tr>
	{foreach item=item from=$polls}
		{counter print=false assign=counter}
		{if $item.language}
			{assign var="cur_lang" value=$item.language}
		{/if}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first">{if $item.question[$cur_lang]}{$item.question[$cur_lang]}{else}{$item.question.default}{/if}</td>
			<td>{if $item.language}{assign var="lang_id" value=$item.language}{$languages[$lang_id].name}{else}{l i='all_languages' gid='polls'}{/if}</td>
			<td>{if $item.poll_type_val}{$item.poll_type_val}{elseif $item.poll_type == -1}{l i='poll_type_1' gid='polls'}{elseif $item.poll_type == -2}{l i='poll_type_2' gid='polls'}{else}{l i='poll_type_0' gid='polls'}{/if}</td>
			<td>{$item.date_start}</td>
			<td>{if $item.use_expiration}{$item.date_end}{else}{l i='field_unlim' gid='polls'}{/if}</td>
			<td class="icons">
			{if ($item.status && !$item.use_expiration) || $item.status}

					<a href="{$site_url}admin/polls/activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_deactivate_poll' gid='polls'}" title="{l i='link_deactivate_poll' gid='polls'}"></a>
				{else}
					<a href="{$site_url}admin/polls/activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_activate_poll' gid='polls'}" title="{l i='link_activate_poll' gid='polls'}"></a>
				{/if}
				<a href="{$site_url}admin/polls/results/{$item.id}"><img src="{$site_root}{$img_folder}icon-stats.png" width="16" height="16" border="0" alt="{l i='link_results' gid='polls'}" title="{l i='link_results' gid='polls'}"></a>
				<a href="{$site_url}admin/polls/answers/{$item.id}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_edit_answers' gid='polls'}" title="{l i='link_edit_answers' gid='polls'}"></a>
				<a href="{$site_url}admin/polls/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_poll' gid='polls'}" title="{l i='link_edit_poll' gid='polls'}"></a>
				<a href="{$site_url}admin/polls/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_poll' gid='polls' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_poll' gid='polls'}" title="{l i='link_delete_poll' gid='polls'}"></a>

			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="8" class="center">{l i='no_polls' gid='polls'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}

{literal}
	<script type="text/javascript">
		$(function(){
			$('input#grouping_all').bind('click', function(){
				var checked = $(this).is(':checked');
				if(checked){
					$('input[type=checkbox]').prop('checked', true);
				}else{
					$('input[type=checkbox]').prop('checked', false);
				}
			});
		});

	</script>
{/literal}

{include file="footer.tpl"}
