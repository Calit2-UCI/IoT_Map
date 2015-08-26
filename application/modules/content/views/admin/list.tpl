{include file="header.tpl" load_type='ui'}
<div class="menu-level2">
	<ul>
		{foreach item=item key=lang_id from=$languages}
		<li{if $lang_id eq $current_lang} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/index/{$lang_id}">{$item.name}</a></div></li>
		{/foreach}
	</ul>
	&nbsp;
</div>

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/content/edit/{$current_lang}/0">{l i='link_add_page' gid='content'}</a></div></li>
		{if $pages}
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false">{l i='link_save_sorter' gid='content'}</a></div></li>
		{/if}
	</ul>
	&nbsp;
</div>

<div id="pages">
<ul name="parent_0" class="sort connected" id="clsr0ul">
{include file="tree_level.tpl" module="content" list=$pages}
</ul>
</div>

<script >{literal}
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}', 
			itemsBlockID: 'pages',
			urlSaveSort: 'admin/content/ajax_save_sorter',
			urlDeleteItem: 'admin/content/ajax_delete/',
			urlActivateItem: 'admin/content/ajax_activate/1/',
			urlDeactivateItem: 'admin/content/ajax_activate/0/'
		});
	});
{/literal}</script>
{include file="footer.tpl"}