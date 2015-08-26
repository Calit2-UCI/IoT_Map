{include file="header.tpl" load_type='ui'}

{*helper func_name=get_admin_level1_menu helper_name=menu func_param='admin_dynblocks_menu'*}

<div class="actions">
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li><a href="{$site_url}admin/dynamic_blocks/area_blocks/{$area.id}">{l i='filter_area_blocks' gid='dynamic_blocks'}</a></li>
		<li><a href="{$site_url}admin/dynamic_blocks/area_layout/{$area.id}">{l i='filter_area_layout' gid='dynamic_blocks'}</a></li>
		<li class="active"><a href="{$site_url}admin/dynamic_blocks/area_preset/{$area.id}">{l i='filter_area_preset' gid='dynamic_blocks'}</a></li>
	</ul>
	&nbsp;
</div>



<div class="filter-form">
<form id="preset-form" action="" method="post">

{foreach item=item from=$presets}
{counter print=false assign='counter'}
<div class="{if $counter is div by 2}right{else}left{/if}-side">
	<h3>{$item.name}</h3>
	<div class="preset">
		<img src="{$item.media.logo.file_url}" alt="">
	</div>
	<div class="btn"><div class="l"><a href="{$site_url}admin/dynamic_blocks/set_preset/{$area.id}/{$item.id}">{l i='btn_apply' gid='start'}</a></div></div>
</div>
{if $counter is div by 2}<div class="clr"></div>{/if}
{foreachelse}
{l i='no_presets' gid='dynamic_blocks'}
{/foreach}
<div class="clr"></div>
</form>
</div>

{include file="pagination.tpl"}
{include file="footer.tpl"}
