{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_seo_menu'}
<div class="actions">
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='settings_name_field' gid='seo'}</th>
	<th class="w30">&nbsp;</th>
</tr>
<tr class="zebra">
	<td class="first">{l i='default_seo_admin_field' gid='seo'}</td>
	<td class="icons">
		<a href="{$site_url}admin/seo/default_edit/admin"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_settings' gid='seo'}" title="{l i='link_edit_settings' gid='seo'}"></a>
	</td>
</tr>
<tr>
	<td class="first">{l i='default_seo_user_field' gid='seo'}</td>
	<td class="icons">
		<a href="{$site_url}admin/seo/default_edit/user"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_settings' gid='seo'}" title="{l i='link_edit_settings' gid='seo'}"></a>
	</td>
</tr>
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
