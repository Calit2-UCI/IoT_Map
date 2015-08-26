{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_contacts_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_root}admin/contact_us/edit">{l i='link_add_reason' gid='contact_us'}</a></div></li>
	</ul>
	&nbsp;
</div>


<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first">{l i='field_reason' gid='contact_us'}</th>
	<th>{l i='field_mails' gid='contact_us'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$reasons}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center">{$item.name}</td>
	<td>{$item.mails_string}</td>
	<td class="icons">
		<a href="{$site_url}admin/contact_us/edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_contact_us' gid='contact_us'}" title="{l i='link_edit_contact_us' gid='contact_us'}"></a>
		<a href="{$site_url}admin/contact_us/delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_contact_us' gid='contact_us' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_contact_us' gid='contact_us'}" title="{l i='link_delete_contact_us' gid='contact_us'}"></a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_contact_us' gid='contact_us'}</td></tr>
{/foreach}
</table>
{include file="pagination.tpl"}


{include file="footer.tpl"}
