{include file="header.tpl" load_type='ui'}

{js module='dynamic_blocks' file='dynamic_blocks_layout'}

<div class="actions">
	<ul>
		<li><div class="l"><a href="#" id="update_layout">{l i='link_save_block_sorting' gid='dynamic_blocks'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li><a href="{$site_url}admin/dynamic_blocks/area_blocks/{$area.id}">{l i='filter_area_blocks' gid='dynamic_blocks'}</a></li>
		<li class="active"><a href="{$site_url}admin/dynamic_blocks/area_layout/{$area.id}">{l i='filter_area_layout' gid='dynamic_blocks'}</a></li>
		{if $presets_count>1}<li><a href="{$site_url}admin/dynamic_blocks/area_preset/{$area.id}">{l i='filter_area_preset' gid='dynamic_blocks'}</a></li>{/if}
	</ul>
	&nbsp;
</div>

<div class="filter-form">
<form id="layout_form" action="{$site_url}admin/dynamic_blocks/save_layout/{$area.id}" method="post">
<div id="area_layout">
<ul name="parent_0" class="sort connected" id="clsr0ul">
<li class="sep">&nbsp;</li>
{foreach item=blocks key=row from=$layout}
	{foreach item=item key=key from=$blocks}
<li id="item_{$item.id}" class="col {if $item.width < 100}col{$item.width}{/if} {if $key eq 0}first{/if}" data-min-width={$item.block_data.min_width}>
	<h3>{l i=$item.block_data.name_i gid=$item.block_data.lang_gid}</h3>
	{if $item.block_data.params.html.type eq 'text'}<div>{$item.params.html|escape}</div>{/if}
	<input type="hidden" name="data[{$item.id}]" value="{$item.width}">
</li>
	{/foreach}
<li class="sep">&nbsp;</li>
{/foreach}
</ul>
</div>
</form>
</div>
<script>{literal}
	var layout;
	$(function(){
		layout = new dynamicBlocksLayout({
			siteUrl: '{/literal}{$site_url}{literal}',
		});
	});
{/literal}</script>
{include file="footer.tpl"}
