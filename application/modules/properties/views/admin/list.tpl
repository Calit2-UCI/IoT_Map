{include file="header.tpl" load_type='editable|ui'}
{js file='admin-multilevel-sorter.js'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/properties/property_items/{$current_lang_id}/{$current_gid}">{l i='link_add_ds_item' gid='properties'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;">{l i='link_resort_items' gid='properties'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		{foreach item=item key=lang_id from=$langs}
		<li class="{if $lang_id eq $current_lang_id}active{/if}"><a href="{$site_url}admin/properties/property/{$ds_gid}/{$lang_id}">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<div class="filter-form" id="ds_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach item=item key=key from=$reference.option}
		<li id="item_{$key}">
			<div class="icons">
				<a href="{$site_url}admin/properties/property_items/{$current_lang_id}/{$current_gid}/{$key}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='btn_edit' gid='start'}" title="{l i='btn_edit' gid='start'}"></a>
				<a href='#' onclick="if (confirm('{l i='note_delete_ds_item' gid='properties' type='js'}')) mlSorter.deleteItem('{$key}');return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='btn_delete' gid='start'}" title="{l i='btn_delete' gid='start'}"></a>
			</div>
			<div class="editable" id="{$key}">{$item}</div>
		</li>
		{/foreach}
	</ul>
</div>
<script>
{literal}
var mlSorter;

$(function(){
	mlSorter = new multilevelSorter({
		siteUrl: '{/literal}{$site_url}{literal}',
		itemsBlockID: 'pages',
		urlSaveSort: 'admin/properties/ajax_ds_item_save_sorter/{/literal}{$current_gid}{literal}/',
		urlDeleteItem: 'admin/properties/ajax_ds_item_delete/{/literal}{$current_gid}{literal}/',
	});
});
{/literal}</script>
{include file="footer.tpl"}
