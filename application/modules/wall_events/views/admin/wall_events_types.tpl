{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_wall_events_menu'}

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first">{l i='field_number' gid='wall_events'}</th>
		<th>{l i='field_name' gid='wall_events'}</th>
		<th>{l i='field_join_period' gid='wall_events'}</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$wall_events_types item=wall_events_type}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first w20 center">{$counter}</td>
			<td>{l i='wetype_'$wall_events_type.gid gid='wall_events'}</td>
			<td class="center">{$wall_events_type.settings.join_period}</td>
			<td class="w150 icons">
				<span>
					<a href="javascript:void(0);" onclick="activateWallEventsType('{$wall_events_type.gid|escape:javascript}', 0, this);" {if !$wall_events_type.status}style="display:none;"{/if}><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="{l i='link_deactivate_wall_events_type' gid='wall_events'}" title="{l i='link_deactivate_wall_events_type' gid='wall_events'}"></a>
					<a href="javascript:void(0);" onclick="activateWallEventsType('{$wall_events_type.gid|escape:javascript}', 1, this);" {if $wall_events_type.status}style="display:none;"{/if}><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="{l i='link_activate_wall_events_type' gid='wall_events'}" title="{l i='link_activate_wall_events_type' gid='wall_events'}"></a>
				</span>
				<a href='{$site_url}admin/wall_events/edit_type/{$wall_events_type.gid}'><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_edit_wall_events_type' gid='wall_events'}" title="{l i='link_edit_wall_events_type' gid='wall_events'}"></a>
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="8" class="center">{l i='no_wall_events_types' gid='wall_events'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}

<script type='text/javascript'>
{literal}
	function activateWallEventsType(gid, status, a_obj){
		$.post(
			site_url+'admin/wall_events/ajax_activate_type/',
			{gid: gid, status: status},
			function(resp){
				if(resp.status){
					$(a_obj).parent().find('a:hidden').show();
					$(a_obj).hide();
					if (status==1) {
						 error_object.show_error_block("{/literal}{l i='wetype_activated' gid='wall_events'}{literal}", 'success');
					}else{
						 error_object.show_error_block("{/literal}{l i='wetype_deactivated' gid='wall_events'}{literal}", 'success');
					}
					
				}
			},
			'json'
		);
	}
{/literal}
</script>

{include file="footer.tpl"}
