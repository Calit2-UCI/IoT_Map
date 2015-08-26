{include file="header.tpl"}
<form method="post" action="{$site_url}/admin/mail_list/users" id="frm_apply_filter">
	<input type="hidden" id="id_filter" name="id_filter" />
</form>
<div id="mail_list">
	{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_mail_list_menu'}
	<table id="tbl_filters" cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w100">{l i='field_filter_date' gid='mail_list'}</th>
		<th>{l i='field_filter_criteria' gid='mail_list'}</th>
		<th class="w50">&nbsp;</th>
	</tr>
	{foreach item=filter from=$filters}
	{counter print=false assign=counter}
	<tr id="filter_{$filter.id}"{if $counter is div by 2} class="zebra"{/if}>
		<td class="first center">{$filter.date_search}</td>
		<td>
			<dl>
				{if $filter.search_data.id_subscription}
					<dt>{l i='field_subscription' gid='mail_list'}:</dt>
					<dd>{$filter.search_data.subscription}</dd>
				{/if}
				{if $filter.search_data.email}
					<dt>{l i='field_email' gid='mail_list'}:</dt>
					<dd>{$filter.search_data.email}</dd>
				{/if}
				{if $filter.search_data.name}
					<dt>{l i='field_nickname' gid='mail_list'}:</dt>
					<dd>{$filter.search_data.name}</dd>
				{/if}
				{if $filter.search_data.user_type}
					{assign var=user_type value=$filter.search_data.user_type}
					<dt>{l i='field_user_type' gid='mail_list'}:</dt>
					<dd>{$user_types.option[$user_type]}</dd>
				{/if}
				{if $filter.search_data.date}
					<dt>{l i='field_date' gid='mail_list'}:</dt>
					<dd>{$filter.search_data.date}</dd>
				{/if}
				{if $filter.location}
					<dt>{l i='field_location' gid='mail_list'}:</dt>
					<dd>{$filter.location}</dd>
				{/if}
			</dl>
		</td>
		<td class="icons">
			<a class="link_delete" id="delete_{$filter.id}" href="javascript:void(0);">
				<img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" 
					 alt="{l i='link_delete' gid='mail_list'}" title="{l i='link_delete' gid='mail_list'}" />
			</a>
			<a class="link_search" id="apply_{$filter.id}"href="javascript:void(0);">
				<img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" 
					 alt="{l i='link_search' gid='mail_list'}" title="{l i='link_search' gid='mail_list'}" />
			</a>
		</td>
	</tr>
	{foreachelse}
	<tr><td colspan="4" class="center">{l i='no_searches' gid='mail_list'}</td></tr>
	{/foreach}
	</table>
	{include file="pagination.tpl"}
	<script type="text/javascript">{literal}
	var mail_list;
	$(function(){
		mail_list = new adminMailList({
			siteUrl: '{/literal}{$site_url}{literal}',
			imgsUrl: '{/literal}{$site_url}{$img_folder}{literal}'
		});
		mail_list.bind_filters_events();
	});
	{/literal}</script>
</div>
{include file="footer.tpl"}
