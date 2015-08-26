{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/users/edit/personal/">{l i='link_add_user' gid='users'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/users/index/all/{$user_type}">{l i='filter_all_users' gid='users'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'not_active'}active{/if}{if !$filter_data.not_active} hide{/if}"><a href="{$site_url}admin/users/index/not_active/{$user_type}">{l i='filter_not_active_users' gid='users'} ({$filter_data.not_active})</a></li>
		<li class="{if $filter eq 'active'}active{/if}{if !$filter_data.active} hide{/if}"><a href="{$site_url}admin/users/index/active/{$user_type}">{l i='filter_active_users' gid='users'} ({$filter_data.active})</a></li>
		<li class="{if $filter eq 'not_confirm'}active{/if}{if !$filter_data.not_confirm} hide{/if}"><a href="{$site_url}admin/users/index/not_confirm/{$user_type}">{l i='filter_not_confirm_users' gid='users'} ({$filter_data.not_confirm})</a></li>
		<li class="{if $filter eq 'deleted'}active{/if}{if !$filter_data.deleted} hide{/if}"><a href="{$site_url}admin/users/deleted">{l i='filter_deleted_users' gid='users'} ({$filter_data.deleted})</a></li>
                {helper func_name='not_registered_add_filter' module='incomplete_signup' func_param=$filter}
        </ul>
	&nbsp;
</div>
<form method="post" enctype="multipart/form-data">
	<input type="hidden" name="filter" value="{$filter}">
	<input type="hidden" name="order" value="{$order}">
	<input type="hidden" name="order_direction" value="{$order_direction}">
	<div class="filter-form">
		<div class="row">
			<div class="h">{l i='search_by' gid='users'}:</div>
			<div class="v">
				<input type="text" name="val_text" value="{$search_param.text}" class="short_long">
				<select name="type_text" class="ml20 short_long">
					<option value="all" {if $search_param.type=='all'} selected{/if}>{l i='filter_all' gid='users'}</option>
					<option value="email" {if $search_param.type=='email'} selected{/if}>{l i='field_email' gid='users'}</option>
					<option value="fname" {if $search_param.type=='fname'} selected{/if}>{l i='field_fname' gid='users'}</option>
					<option value="sname" {if $search_param.type=='sname'} selected{/if}>{l i='field_sname' gid='users'}</option>
					<option value="nickname" {if $search_param.type=='nickname'} selected{/if}>{l i='field_nickname' gid='users'}</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_deleted_from' gid='users'}:</div>
			<div class="v">
				<input type="text" id="date_deleted_from" name="date_deleted_from" maxlength="10" class="short_long" value="{$search_param.date_deleted.from}">
				<label for="date_deleted_to">{l i='to' gid='users'}</label>
				<input type="text" id="date_deleted_to" name="date_deleted_to" maxlength="10" class="short_long" value="{$search_param.date_deleted.to}">
			</div>
		</div>
		<div class="row">
			<div class="btn">
				<div class="l">
					<input type="submit" value="{l i='header_user_find' gid='users'}" name="btn_search">
				</div>
			</div>
		</div>		
	</div>
</form>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="{$sort_links.nickname}"{if $order eq 'nickname'} class="{$order_direction|lower}"{/if}>{l i='field_nickname' gid='users'}</a></th>
	<th><a href="{$sort_links.email}"{if $order eq 'email'} class="{$order_direction|lower}"{/if}>{l i='field_email' gid='users'}</a></th>
	<th class=""><a href="{$sort_links.date_deleted}"{if $order eq 'date_deleted'} class="{$order_direction|lower}"{/if}>{l i='field_deleted_date' gid='users'}</a></th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$users}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first"><b>{$item.nickname}</b><br>{$item.fname} {$item.sname}</td>
	<td>{$item.email}</td>
	<td class="center">{$item.date_deleted|date_format:$page_data.date_format}</td>
	<td class="icons">
		{block name='delete_select_block' module='users' id_user=$item.id_user callback_user=$item.data deleted=1}
	</td>
</tr>
{foreachelse}
<tr><td colspan="7" class="center">{l i='no_users' gid='users'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{js file='jquery-ui.custom.min.js'}
<link href='{$site_root}{$js_folder}jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
<script type="text/javascript">

var reload_link = "{$site_url}admin/users/deleted/";
var filter = '{$filter}';
var order = '{$order}';
var loading_content;
var order_direction = '{$order_direction}';

{literal}

$(function(){
	now = new Date();
	yr =  (new Date(now.getYear() - 80, 0, 1).getFullYear()) + ':' + (new Date(now.getYear() - 18, 0, 1).getFullYear());
	$( "#date_deleted_from" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#date_deleted_to" ).datepicker( "option", "minDate", selectedDate );
		}
    });
    $( "#date_deleted_to" ).datepicker({
		defaultDate: "+1w",
		changeMonth: true,
		numberOfMonths: 1,
		dateFormat :'yy-mm-dd',
		onClose: function( selectedDate ) {
			$( "#date_deleted_from" ).datepicker( "option", "maxDate", selectedDate );
		}
    });
});
function reload_this_page(value){
	var link = reload_link + filter + '/' + value + '/' + order + '/' + order_direction;
	location.href=link;
}
delete_select_block = new loadingContent({
	loadBlockWidth: '620px',
	loadBlockLeftType: 'center',
	loadBlockTopType: 'center',
	loadBlockTopPoint: 100,
	closeBtnClass: 'w'
}).update_css_styles({'z-index': 2000}).update_css_styles({'z-index': 2000}, 'bg');
function reload_this_page(value){
	var link = reload_link + filter + '/' + value + '/' + order + '/' + order_direction;
	location.href=link;
}
{/literal}</script>

{include file="footer.tpl"}
