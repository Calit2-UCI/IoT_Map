{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_payments_menu'}

<div class="actions">
	<ul>
		<li>
			<div class="l">
				<a href="{$site_root}admin/memberships/create">{l i='btn_add' gid='start'}</a>
			</div>
		</li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data memberships">
	<tr>
		<th class="first">{l i='field_name' gid='memberships'}</th>
		<th class="w100">{l i='field_price' gid='memberships'}</th>
		<th class="w100">{l i='field_status' gid='memberships'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$memberships}
	<tr>
		<td class="first center">{$item.name}</td>
		<td class="center">{block name='currency_format_output' module='start' value=$item.price}</td>
		<td class="center">
			{if $item.is_active}
			<a class="btn-activity" data-activity="true" data-id="{$item.id}" 
			   href="{$site_url}admin/memberships/deactivate/{$item.id}"
			   title="{l i='link_item_deactivate' gid='memberships' type='button'}">
				<i class="fa fa-circle"></i>
			</a>
			{else}
			<a class="btn-activity" data-activity="false" data-id="{$item.id}" 
			   href="{$site_url}admin/memberships/activate/{$item.id}"
			   title="{l i='link_item_activate' gid='memberships' type='button'}">
				<i class="inactive fa fa-circle"></i>
			</a>
			{/if}
		</td>
		<td class="icons">
			{strip}
			<a class="btn-edit" data-id="{$item.id}" href="{$site_url}admin/memberships/edit/{$item.id}">
				<img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" 
					 alt="{l i='link_item_edit' gid='memberships' type='button'}" 
					 title="{l i='link_item_edit' gid='memberships' type='button'}">
			</a>
			{/strip}
			{strip}
			<a class="btn-delete" data-id="{$item.id}" href="{$site_url}admin/memberships/delete/{$item.id}">
				<img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16"
					 alt="{l i='link_item_delete' gid='memberships' type='button'}" 
					 title="{l i='link_item_delete' gid='memberships'}">
			</a>
			{/strip}
		</td>
	</tr>
	{foreachelse}
	<tr>
		<td colspan="4" class="center">
			{l i='no_items' gid='memberships'}
		</td>
	</tr>
	{/foreach}
</table>
{include file="pagination.tpl"}
{js file='memberships-admin.js' module='memberships'}
<script>{literal}
	$(function(){
		new membershipsAdmin({
			siteUrl: '{/literal}{$site_url}{literal}',
			msgConfirmDeletion: '{/literal}{l i='confirm_delete_membership' gid='memberships'}{literal}'
		});
	});
{/literal}</script>

{include file="footer.tpl"}
