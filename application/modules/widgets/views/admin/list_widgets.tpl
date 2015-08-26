{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_widgets_menu'}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_name' gid='widgets'}</th>
	<th class="w100">{l i='field_module' gid='widgets'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach from=$widgets item=item}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td>{l i=$item.gid+'_name' gid='widgets'}</td>
	<td>{$item.module}</td>
	<td class="icons">
		<a href="{$site_url}admin/widgets/edit/{$item.gid}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_widget' gid='widgets' type='button'}" title="{l i='link_edit_widget' gid='widgets' type='button'}"></a>
		<a href="{$site_url}admin/widgets/delete/{$item.gid}" onclick="javascript: if(!confirm('{l i='note_delete_widget' gid='widgets' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_widget' gid='widgets' type='button'}" title="{l i='link_delete_widget' gid='widgets' type='button'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3" class="center">{l i='no_widgets' gid='widgets'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
