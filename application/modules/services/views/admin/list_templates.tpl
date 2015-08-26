{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/services/template_edit">{l i='link_add_template' gid='services'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_template_name' gid='services'}</th>
	<th class="w50">&nbsp;</th>
</tr>
{foreach item=item from=$templates}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.name}</td>
	<td class="icons">
		<a href="{$site_url}admin/services/template_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_template' gid='services'}" title="{l i='link_edit_template' gid='services'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_templates' gid='services'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
