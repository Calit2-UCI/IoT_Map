{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_notifications_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/subscriptions/edit">{l i='link_add_subscriptions' gid='subscriptions'}</a></div></li>
	</ul>
	&nbsp;
</div>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_subscriptions_name' gid='subscriptions'}</th>
        <th class="w150"><a href="{$sort_links.subscribe_type'}"{if $order eq 'subscribe_type'} class="{$order_direction|lower}"{/if}>{l i='field_subscribe_type' gid='subscriptions'}</a></th>
	<th>{l i='field_sheduler' gid='subscriptions'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$subscriptions}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{l i=$item.name_i gid='subscriptions'}</td>
	<td class="center">{l i=$item.subscribe_type gid='subscriptions'}</td>
	<td class="center">{$item.scheduler_format}</td>
	<td class="icons">
		<a href="{$site_url}admin/subscriptions/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_subscriptions' gid='subscriptions'}" title="{l i='link_edit_subscriptions' gid='subscriptions'}"></a>
		<a href="{$site_url}admin/subscriptions/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_subscriptions' gid='subscriptions' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_subscriptions' gid='subscriptions'}" title="{l i='link_delete_subscriptions' gid='subscriptions'}"></a>
                <a id="link_start_subscribe" href="{$site_url}admin/subscriptions/ajax_start_subscribe/{$item.id}/" onclick="javascript: open_start_subscribe(this.href); return false;" ><img src="{$site_root}{$img_folder}icon-play.png" width="16" height="16" border="0" alt="{l i='start_subscribe' gid='subscriptions'}" title="{l i='start_subscribe' gid='subscriptions'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_subscriptions' gid='subscriptions'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
<script type="text/javascript">
 {literal}
$(function(){
	loading_funds = new loadingContent({
		linkerObjID: 'link_start_subscribe',
		loadBlockWidth: '350px',
		loadBlockLeftType: 'center',
		loadBlockTopType: 'bottom',
		closeBtnClass: 'w'
	});
});

function open_start_subscribe(url){
	$.ajax({
		url: url, 
		type: 'GET',
		cache: false,
		success: function(data){
			loading_funds.show_load_block(data);
		}
	});
}

 {/literal}
</script>
{include file="footer.tpl"}
