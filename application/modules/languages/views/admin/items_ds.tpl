{include file="header.tpl" load_type='editable|ui'}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_languages_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/languages/ds_items_edit/{$current_lang_id}/{$current_module_id}/{$current_gid}">{l i='link_add_ds_item' gid='languages'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;">{l i='link_resort_items' gid='languages'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		{foreach item=item key=lang_id from=$langs}
		<li class="{if $lang_id eq $current_lang_id}active{/if}"><a href="{$site_url}admin/languages/ds_items/{$lang_id}/{$current_module_id}/{$current_gid}">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<div class="filter-form" id="ds_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach item=item key=key from=$reference.option}
		<li id="item_{$key}">
			<div class="icons">
				<a href="{$site_url}admin/languages/ds_items_edit/{$current_lang_id}/{$current_module_id}/{$current_gid}/{$key}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='edit' gid='start'}" title="{l i='edit' gid='start'}"></a>
				<a href='#' onclick="if (confirm('{l i='note_delete_ds_item' gid='languages' type='js'}')) mlSorter.deleteItem('{$key}');return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='delete' gid='start'}" title="{l i='delete' gid='start'}"></a>
			</div>
			<div class="editable" id="{$key}">{$item}</div>
		</li>
		{/foreach}
	</ul>
</div>
<script>
var change_url = '{$site_url}admin/languages/ajax_ds_item_save/{$current_lang_id}/{$current_module_id}/{$current_gid}';
{literal}

var mlSorter;

$(function(){
	$('.editable').editable(change_url, {
		tooltip: '{/literal}{l i="default_editable_text" gid="languages" type="js"}{literal}',
		placeholder: '<font class="hide_text">{/literal}{l i="default_editable_text" gid="languages" type="js"}{literal}</font>',
		name : 'text',
		submit : 'Save',
		cancel : 'Cancel',
		height: 'auto',
		width: 300,
		callback: function(value, settings){
			$(this).html(settings.current);
		}
	});
	mlSorter = new multilevelSorter({
		siteUrl: '{/literal}{$site_url}{literal}', 
		itemsBlockID: 'pages',
		urlSaveSort: 'admin/languages/ajax_ds_item_save_sorter/{/literal}{$current_module_id}/{$current_gid}{literal}/',
		urlDeleteItem: 'admin/languages/ajax_ds_item_delete/{/literal}{$current_module_id}/{$current_gid}{literal}/',
//		success: function(data){
//			location.href = '{/literal}{$site_url}admin/languages/ds_items/{$current_lang_id}/{$current_module_id}/{$current_gid}{literal}';
//		}
	});
});
{/literal}</script>
{include file="footer.tpl"}
