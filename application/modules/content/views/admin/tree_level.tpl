{foreach item=item from=$list}
{assign var=id value=$item.id}
<li id="item_{$id}">

	<div id="clsr{$id}" class="closer expand{if count($item.sub)} visible{/if}"></div>
	<div class="icons">
		<a href="{$site_url}admin/content/edit/{$item.lang_id}/{$item.id}"><img src="{$site_root}{$img_folder}icon-copy.png" width="16" height="16" alt="{l i='link_create_subitem' gid='content'}" title="{l i='link_create_subitem' gid='content'}"></a>
		<a href="#" onclick="javascript: mlSorter.deactivateItem({$item.id});return false;" id="active_{$id}" {if $item.status ne 1}class="hide"{/if}><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" alt="{l i='make_inactive' gid='start'}" title="{l i='make_inactive' gid='start'}"></a>
		<a href="#" onclick="javascript: mlSorter.activateItem({$item.id});return false;" id="deactive_{$id}" {if $item.status eq 1}class="hide"{/if}><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" alt="{l i='make_active' gid='start'}" title="{l i='make_active' gid='start'}"></a>
		<a href="{$site_url}admin/content/edit/{$item.lang_id}/{$item.parent_id}/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" alt="{l i='edit' gid='start'}" title="{l i='btn_edit' gid='start'}"></a>
		<a href='#' onclick="if (confirm('{l i='note_delete_page' gid='content' type='js'}')) mlSorter.deleteItem({$item.id});return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='btn_delete' gid='start'}" title="{l i='btn_delete' gid='start'}"></a>
	</div>
	{$item.title}
	<ul id="clsr{$id}ul" class="sort connected{if count($item.sub)} hide{/if}" name="parent_{$id}">{include file="tree_level.tpl" module="content" list=$item.sub}</ul>
</li>
{/foreach}
