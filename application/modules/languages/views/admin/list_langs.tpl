{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_languages_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/languages/lang_edit">{l i='link_add_lang' gid='languages'}</a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_language_name' gid='languages'}</th>
	<th class="w50">{l i='field_default' gid='languages'}</th>
	<th class="w50">{l i='field_active' gid='languages'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$languages}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{$item.name}</td>
	<td class="center">
		{if $item.is_default}
		<img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" >
		{else}
		<a href="{$site_url}admin/languages/lang_default/{$item.id}" title="{l i='note_set_default' gid='languages'}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" ></a>
		{/if}
	</td>
	<td class="center">
		{if $item.status}
		<a href="{$site_url}admin/languages/lang_active/{$item.id}/0" title="{l i='note_deactivate' gid='languages'}"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" ></a>
		{else}
		<a href="{$site_url}admin/languages/lang_active/{$item.id}/1" title="{l i='note_activate' gid='languages'}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" ></a>
		{/if}
	</td>
	<td class="icons">
		<a href="{$site_url}admin/languages/lang_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_lang' gid='languages'}" title="{l i='link_edit_lang' gid='languages'}"></a>
		{if $user_session_data.lang_id ne $item.id && $default_lang_id ne $item.id}
			<a href="{$site_url}admin/languages/lang_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_lang' gid='languages' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_lang' gid='languages'}" title="{l i='link_delete_lang' gid='languages'}"></a>
		{/if}
	</td>
</tr>
{/foreach}
</table>

{include file="footer.tpl"}
