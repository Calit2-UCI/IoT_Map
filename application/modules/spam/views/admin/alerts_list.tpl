{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_spam_menu'}

	<div class="actions">
		<ul>
			{if $alerts}
				<li>
					<div class="l">
						<a href="{$site_url}admin/spam/alerts_delete/without_object/" class="subscribe" id="delete_selected">
							{l i='link_delete_selected' gid='spam'}
						</a>
					</div>
				</li>
			{/if}
		</ul>&nbsp;
	</div>
{if $spam_types_count}
<div class="menu-level3">
	<ul>
		{foreach item=item from=$spam_types}
		{assign var=stat_header value="stat_header_spam_`$item.gid`"}
		{if $filter eq $item.gid}{assign var=form_type value=$item.form_type}{/if}
		<li class="{if $filter eq $item.gid}active{/if}{if !$item.obj_count} hide{/if}"><a href="{$site_url}admin/spam/index/{$item.gid}">{l i=$stat_header gid='spam'} ({$item.obj_count})</a></li>
		{/foreach}		
	</ul>
	&nbsp;
</div>
{/if}

<form id="alerts_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w20">{if $alerts}<input type="checkbox" id="grouping_all">{/if}</th>
		<th>{l i='field_alert_content' gid='spam'}</th>
		<th class="w100">{l i='field_alert_user' gid='spam'}</th>
		<th class="w100"><a href="{$sort_links.date_add}"{if $order eq 'date_add'} class="{$order_direction|lower}"{/if}>{l i='field_alert_date_add' gid='spam'}</a></th>
		<th class="w50">&nbsp;</th>
	</tr>
	{foreach item=item from=$alerts}
		{counter print=false assign=counter}
		{assign var=spam_status_name value='alert_status_'`$item.spam_status`}
		<tr{if $counter is div by 2} class="zebra"{/if}>				
			<td class="first center"><input type="checkbox" class="grouping" name="ids[]" value="{$item.id}"></td>
			<td class="spam_content">{if !$item.mark}<b>{/if}{if $item.reason}{l i='field_spam_reason' gid='spam'}:{$item.reason}<br/>{/if}{$item.content.content.list|replace:$item.content.rand:$item.id}{if !$item.mark}</b>{/if}</td>
			<td class="center">{if !$item.mark}<b>{/if}{$item.content.user_content}{if !$item.mark}</b>{/if}</td>
			<td class="center">{if !$item.mark}<b>{/if}{$item.date_add|date_format:$page_data.date_format}{if !$item.mark}</b>{/if}</td>
			<td class="icons" style="padding: 8px 15px;">	
				<a href="{$site_url}admin/spam/alerts_show/{$item.id}"><img src="{$site_root}{$img_folder}icon-view.png" width="16" height="16" border="0" alt="{l i='link_alerts_show' gid='spam' type='button'}" title="{l i='link_alerts_show' gid='spam' type='button'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="{if $form_type eq 'select_text'}6{else}5{/if}" class="center">{l i='no_alerts' gid='spam'}</td></tr>
	{/foreach}
</table>
</form>
{include file="pagination.tpl"}

<script>{literal}
var reload_link = "{/literal}{$site_url}admin/spam/index/{literal}";
var filter = '{/literal}{$filter}{literal}';
var order = '{/literal}{$order}{literal}';
var loading_content;
var order_direction = '{/literal}{$order_direction}{literal}';
$(function(){
	$("#grouping_all").click(function()
	{
		try {
			var checked_status = this.checked;
			$("input.grouping").each(function()
			{
				this.checked = checked_status;
			});
		} catch (err) {
			alert(err);
		}
	});
	$('#ban_all,#unban_all,#delete_object_all').bind('click', function(){
		if(!$('input[type=checkbox].grouping').is(':checked')) return false; 
		if(this.id == 'delete_object_all' && !confirm('{/literal}{l i='note_alerts_delete_object_all' gid='spam' type='js'}{literal}')) return false;
		if(this.id == 'delete_all' && !confirm('{/literal}{l i='note_alerts_delete_all' gid='spam' type='js'}{literal}')) return false;
		$('#alerts_form').attr('action', $(this).find('a').attr('href')).submit();		
		return false;
	});
	$('#delete_selected').bind('click', function(){
		if(!$('input[type=checkbox].grouping').is(':checked')) return false; 
		if(!confirm('{/literal}{l i='note_alerts_delete_all' gid='spam' type='js'}{literal}')) return false;
		$('#alerts_form').attr('action', $(this).attr('href')).submit();		
		return false;
	});
});
function reload_this_page(value){
	var link = reload_link + filter + '/' + value + '/' + order + '/' + order_direction;
	location.href=link;
}
var div_spam_content = $(".spam_content");
var iframe = div_spam_content.find('iframe');
if(iframe.length){
	iframe.css('width', '460px');
}
var a_href = div_spam_content.find('a');
if(a_href.length){
	a_href.css('width', '460px');
	a_href.css('height', '260px');
}
{/literal}</script>

{include file="footer.tpl"}
