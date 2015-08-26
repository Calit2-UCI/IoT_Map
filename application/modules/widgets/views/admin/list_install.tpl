{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_widgets_menu'}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_name' gid='widgets'}</th>
	<th class="w100">{l i='field_module' gid='widgets'}</th>
	<th class="w100 ">&nbsp;</th>
</tr>
{foreach from=$widgets item=item}
{counter print=false assign=counter}
{assign var='name' value=$item.gid+'_name'}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td>{$item.langs[$name]}</td>
	<td>{$item.module}</td>
	<td class="icons">
		<a href="{$site_url}admin/widgets/install_widget/{$item.module}/{$item.gid}">{l i='link_install_widget' gid='widgets'}</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="3" class="center">{l i='no_widgets_install' gid='widgets'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{include file="footer.tpl"}
