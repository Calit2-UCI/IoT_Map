{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_spam_menu'}
<div class="actions">
	&nbsp;
</div>
<form id="types_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w150">{l i='field_type_gid' gid='spam'}</th>
		<th class="w100">{l i='field_type_form_type' gid='spam'}</th>
		<th class="w70">{l i='field_type_send_mail' gid='spam'}</th>
		<th class="w70">{l i='field_type_status' gid='spam'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$types}
		{counter print=false assign=counter}
		{assign var=item_name value="stat_header_spam_`$item.gid`"}
		<tr{if $counter is div by 2} class="zebra"{/if}>				
			<td class="center">{l i=$item_name gid='spam'}</td>
			<td class="center">{$item.form}</td>
			</td>
			<td class="center">
				{if $item.send_mail}
				<a href="{$site_url}admin/spam/type_send_mail/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_send_mail_off' gid='spam' type='button'}" title="{l i='link_send_mail_off' gid='spam' type='button'}"></a>
				{else}
				<a href="{$site_url}admin/spam/type_send_mail/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_send_mail_on' gid='spam' type='button'}" title="{l i='link_send_mail_on' gid='spam' type='button'}"></a>
				{/if}
			</td>
			<td class="center">
				{if $item.status}
				<a href="{$site_url}admin/spam/type_activate/{$item.id}/0"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_type_deactivate' gid='spam' type='button'}" title="{l i='link_type_deactivate' gid='spam' type='button'}"></a>
				{else}
				<a href="{$site_url}admin/spam/type_activate/{$item.id}/1"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_type_activate' gid='spam' type='button'}" title="{l i='link_type_activate' gid='spam' type='button'}"></a>
				{/if}
			</td>
			<td class="icons">				
				<a href="{$site_url}admin/spam/types_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_types_edit' gid='spam' type='button'}" title="{l i='link_types_edit' gid='spam' type='button'}"></a>				
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="6" class="center">{l i='no_types' gid='spam'}</td></tr>
	{/foreach}
</table>
</form>
{include file="pagination.tpl"}
{include file="footer.tpl"}
