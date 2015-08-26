{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}"><a href="{$site_url}admin/payments/index/all/{$payment_type_gid}/{$system_gid}">{l i='filter_payments_all' gid='payments'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'wait'}active{/if}"><a href="{$site_url}admin/payments/index/wait/{$payment_type_gid}/{$system_gid}">{l i='filter_payments_wait' gid='payments'} ({$filter_data.wait})</a></li>
		<li class="{if $filter eq 'approve'}active{/if}"><a href="{$site_url}admin/payments/index/approve/{$payment_type_gid}/{$system_gid}">{l i='filter_payments_approve' gid='payments'} ({$filter_data.approve})</a></li>
		<li class="{if $filter eq 'decline'}active{/if}"><a href="{$site_url}admin/payments/index/decline/{$payment_type_gid}/{$system_gid}">{l i='filter_payments_decline' gid='payments'} ({$filter_data.decline})</a></li>
	</ul>
	&nbsp;
</div>

<div class="filter-form">
	<div class="row">
		<div class="h">{l i='filter_payment_type' gid='payments'}:</div>
		<div class="v">
			<select name="payment_type_gid" class="middle_short" onchange="javascript: reload_this_page(this.value, system_gid);">
			<option value="all">...</option>{foreach item=item key=key from=$payment_types}<option value="{$item.gid}" {if $payment_type_gid eq $item.gid}selected{/if}>{$item.gid}</option>{/foreach}
			</select>
		</div>
	</div>
	<div class="row">
		<div class="h">{l i='filter_billing_type' gid='payments'}:</div>
		<div class="v">
			<select name="system_gid" class="middle_short" onchange="javascript: reload_this_page(payment_type_gid, this.value);">
			<option value="all">...</option>{foreach item=item key=key from=$systems}<option value="{$item.gid}" {if $system_gid eq $item.gid}selected{/if}>{$item.name}</option>{/foreach}
			</select>
		</div>
	</div>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_payment_user' gid='payments'}</th>
	<th><a href="{$sort_links.amount'}"{if $order eq 'amount'} class="{$order_direction|lower}"{/if}>{l i='field_payment_amount' gid='payments'}</a></th>
	<th>{l i='field_payment_type' gid='payments'}</th>
	<th>{l i='field_payment_billing_system' gid='payments'}</th>
	<th><a href="{$sort_links.date_add'}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_payment_date' gid='payments'}</a></th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$payments}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center tooltip" id="hide_{$item.id}">
		{$item.user.output_name}
		<span id="span_hide_{$item.id}" class="hide"><div class="tooltip-info">
			{foreach item=param key=param_id from=$item.payment_data_formatted}
			<b>{$param.name}:</b> {$param.value}<br>
			{/foreach}
		</div></span>
	</td>
	<td class="center">{block name='currency_format_output' module='start' value=$item.amount}</td>
	<td class="center">{$item.payment_type_gid}</td>
	<td class="center">{$item.system_gid}</td>
	<td class="center">{$item.date_add|date_format:$page_data.date_format}</td>
	<td class="icons">
	{if $item.status eq '1'}
		<font class="success">{l i='payment_status_approved' gid='payments'}</font>
	{elseif $item.status eq '-1'}
		<font class="error">{l i='payment_status_declined' gid='payments'}</font>
	{else}
		<a href="{$site_url}admin/payments/payment_status/approve/{$item.id}"><img src="{$site_root}{$img_folder}icon-approve.png" width="16" height="16" border="0" alt="{l i='link_payment_approve' gid='payments'}" title="{l i='link_payment_approve' gid='payments'}"></a>
		<a href="{$site_url}admin/payments/payment_status/decline/{$item.id}"><img src="{$site_root}{$img_folder}icon-decline.png" width="16" height="16" border="0" alt="{l i='link_payment_decline' gid='payments'}" title="{l i='link_payment_decline' gid='payments'}"></a>
	</td>
	{/if}
</tr>
{foreachelse}
<tr><td colspan="6" class="center">{l i='no_payments' gid='payments'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{js file='easyTooltip.min.js'}
<script type="text/javascript">
var filter = '{$filter}';
var payment_type_gid = '{if $payment_type_gid}{$payment_type_gid}{else}all{/if}';
var system_gid = '{if $system_gid}{$system_gid}{else}all{/if}';
var order = '{$order}';
var order_direction = '{$order_direction}';
var reload_link = "{$site_url}admin/payments/index/";
{literal}
function reload_this_page(payment_type_gid, system_gid){
	var link = reload_link + filter + '/' + payment_type_gid + '/' + system_gid + '/' + order + '/' + order_direction;
	location.href=link;
}

$(function(){
	$(".tooltip").each(function(){
		$(this).easyTooltip({
			useElement: 'span_'+$(this).attr('id')
		});
	});
});
{/literal}
</script>

{include file="footer.tpl"}
