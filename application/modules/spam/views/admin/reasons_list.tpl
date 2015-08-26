{include file="header.tpl" load_type='editable|ui'}
{js file='admin-multilevel-sorter.js'}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_spam_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/spam/reasons_edit">{l i='btn_reasons_create' gid='spam'}</a></div></li>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;">{l i='btn_reasons_resort' gid='spam'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		{foreach item=item key=lang_id from=$langs}
		<li class="{if $lang_id eq $current_lang_id}active{/if}"><a href="{$site_url}admin/spam/reasons/{$lang_id}">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<div class="filter-form" id="ds_items">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach item=item key=key from=$reference.option}
		<li id="item_{$key}">
			<div class="icons">
				<a href="{$site_url}admin/spam/reasons_edit/{$current_lang_id}/{$key}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='link_reasons_edit' gid='spam' type='button'}" title="{l i='link_reasons_edit' gid='spam' type='button'}"></a>
				<a href='#' onclick="if (confirm('{l i='note_reasons_delete' gid='spam' type='js'}')) mlSorter.deleteItem('{$key}');return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='link_reasons_delete' gid='spam' type='button'}" title="{l i='link_reasons_delete' gid='spam' type='button'}"></a>
			</div>
			<div class="editable" id="{$key}">{$item|default:'&nbsp;'}</div>
		</li>
		{/foreach}
	</ul>
</div>
<script>
{literal}
var mlSorter;

$(function(){
	mlSorter = new multilevelSorter({
		siteUrl: '{/literal}{$site_root}{literal}',
		itemsBlockID: 'pages',
		urlSaveSort: 'admin/spam/ajax_reasons_save_sorter/',
		urlDeleteItem: 'admin/spam/ajax_reasons_delete/',
	});
});
{/literal}</script>
{include file="footer.tpl"}
