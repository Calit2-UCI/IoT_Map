{include file="header.tpl"}
<div class="menu-level2">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/themes/installed_themes">{l i='header_installed_themes' gid='themes'}</a></div></li>
		<li class="active"><div class="l"><a href="{$site_url}admin/themes/enable_themes">{l i='header_enable_themes' gid='themes'}</a></div></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="{if $type eq 'user'}active{/if}"><a href="{$site_url}admin/themes/enable_themes/user">{l i='filter_user_themes' gid='themes'}</a></li>
		<li class="{if $type eq 'admin'}active{/if}"><a href="{$site_url}admin/themes/enable_themes/admin">{l i='filter_admin_themes' gid='themes'}</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">&nbsp;</th>
	<th class="w100">{l i='field_theme' gid='themes'}</th>
	<th>{l i='field_description' gid='themes'}</th>
	<th class="w70">&nbsp;</th>
</tr>
{foreach item=item from=$themes}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first">{if $item.img}<img src="{$item.img}" class="img">{/if}</td>
	<td class="center">{$item.theme}</td>
	<td><b>{$item.theme_name}</b><br>{$item.theme_description}</td>
	<td class="icons">
		<a href="{$site_url}admin/themes/install/{$item.gid}">{*<img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_install_theme' gid='themes'}" title="{l i='link_install_theme' gid='themes'}">*}{l i='link_install_theme' gid='themes'}</a>
	</td>
</tr>
{foreachelse}
<tr><td colspan="4" class="center">{l i='no_themes' gid='themes'}</td></tr>
{/foreach}
</table>
{include file="footer.tpl"}
