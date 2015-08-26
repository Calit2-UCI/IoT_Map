{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_notifications_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a id="refresh" class="pool_link">{l i='refresh_pool' gid='notifications'}</a></div></li>
	{if $allow_pool_send}<li><div class="l"><a id="send" class="pool_link">{l i='send_pools' gid='notifications'}</a></div></li>{/if}
{if $allow_pool_delete}<li><div class="l"><a id="delete" class="pool_link">{l i='delete_pools' gid='notifications'}</a></div></li>{/if}
</ul>
&nbsp;
</div>
<style>
    {literal}
		.pool_link {
			cursor: pointer;
		}
    {/literal}
</style>
<form id="pool_form" name="pool_form">
	<div id="pool_data">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			<tr>
			{if $allow_pool_send || $allow_pool_delete}<th class="first w20 center"><input type="checkbox" id="grouping_all" onclick="javascript: checkAll(this.checked);"></th>{/if}
			<th class="w150 {if !$allow_pool_send && !$allow_pool_delete}first{/if}"><a href="{$sort_links.email}"{if $order eq 'email'} class="{$order_direction|lower}"{/if}>{l i='field_mail_to_email' gid='notifications'}</a></th>
			<th class="w150"><a href="{$sort_links.subject}"{if $order eq 'subject'} class="{$order_direction|lower}"{/if}>{l i='field_subject' gid='notifications'}</a></th>
			<th class="w50"><a href="{$sort_links.send_counter}"{if $order eq 'send_counter'} class="{$order_direction|lower}"{/if}>{l i='send_attempts' gid='notifications'}</a></th>
		{if $allow_pool_send || $allow_pool_delete}<th class="w50">{l i='actions' gid='notifications'}</th>{/if}
	</tr>
	{foreach item=item from=$senders}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
		{if $allow_pool_send || $allow_pool_delete}<td class="first w20 center"><input type="checkbox" class="grouping" value="{$item.id}"></td>{/if}
		<td class="center">{$item.email}</td>
		<td class="center">{$item.subject}</td>
		<td class="center">{$item.send_counter}</td>
		{if $allow_pool_send || $allow_pool_delete}<td class="icons">
			{if $allow_pool_send}<a href="{$site_url}admin/notifications/pool_send/{$item.id}"><img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" alt="{l i='link_send_pool' gid='notifications'}" title="{l i='link_send_pool' gid='notifications'}"></a>{/if}
		{if $allow_pool_delete}<a href="{$site_url}admin/notifications/pool_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_pool' gid='notifications' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_pool' gid='notifications'}" title="{l i='link_delete_pool' gid='notifications'}"></a>{/if}
	</td>{/if}
</tr>
{foreachelse}
	<tr><td colspan="5" class="center">{l i='no_pool' gid='notifications'}</td></tr>
	{/foreach}
    </table>
    {include file="pagination.tpl"}
</div>
</form>

<script>
	{literal}
	{/literal}{if $allow_pool_send || $allow_pool_delete}{literal}
	function checkAll(checked){
		if(checked)
			$('.grouping:enabled').prop('checked', true);
		else
			$('.grouping:enabled').prop('checked', false);
	}
	function checkBoxes(){
		if($('.grouping:checked').length > 0){
			return true;
		}else{
			return false;
		}
	}
	function getCheckBoxes(){
		var ProductID = [];
		$('[type=checkbox]').each(function() {
			if (this.checked) {
				ProductID[ProductID.length] = $(this).val();
			}
		});
		return ProductID;
	}
	{/literal}{/if}{literal}
	function refresh_pool() {
		$.ajax({
			url: '{/literal}{$ajax_pool_url}{literal}',
			cache: false,
			success: function(data){
				$('#pool_data').html(data);
			}
		});
	}
	$('document').ready(function(){
		$('#refresh').click(function(){
			refresh_pool();
			return false;
		});
	{/literal}{if $allow_pool_send}{literal}
		$('#send').click(function(){
			document.location.href = '{/literal}{$site_url}{literal}admin/notifications/pool_send/' + getCheckBoxes();
			return false;
		});
	{/literal}{/if}
	{if $allow_pool_delete}{literal}
		$('#delete').click(function(){
			document.location.href = '{/literal}{$site_url}{literal}admin/notifications/pool_delete/' + getCheckBoxes();
			return false;
		});
	{/literal}{/if}{literal}
	});
	{/literal}
</script>

{include file="footer.tpl"}
