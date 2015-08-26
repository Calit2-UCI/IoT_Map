{include file="header.tpl" load_type='ui'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/menu/items_edit/{$menu_data.id}">{l i='link_add_menu_item' gid='menu'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false">{l i='link_save_sorter' gid='menu'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div id="menu_items">
<ul name="parent_0" class="sort connected" id="clsr0ul">
{include file="tree_level.tpl" module="menu" list=$menu}
</ul>
</div>

<script >{literal}
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}', 
			urlSaveSort: 'admin/menu/ajax_save_item_sorter',
			urlDeleteItem: 'admin/menu/ajax_item_delete/',
			urlActivateItem: 'admin/menu/ajax_item_activate/1/',
			urlDeactivateItem: 'admin/menu/ajax_item_activate/0/'
		});
	});
{/literal}</script>
{include file="footer.tpl"}