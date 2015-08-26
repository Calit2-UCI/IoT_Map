{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_kisses_menu'}

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/kisses/add/" id="btn_add" />{l i='btn_add' gid='kisses'}</a></div></li>
	</ul>
	&nbsp;
</div>

<form id="kisses_form" method="post" enctype="multipart/form-data" name="kisses_form" action="{$site_url}admin/kisses/edit/{$id}">
	<div class="edit-form n150">
	
	<img src="{$file_url|escape}" alt="{$kiss.id}">
	
	<div class="row header">{l i='admin_header_kisses_change' gid='kisses'}</div>
	
	<div class="row">
		<div class="h">{l i='field_name' gid='kisses'}: </div>
		<div class="v">
			{foreach item=lang_item key=lang_id from=$langs}
			{assign var='name' value='name_'+$lang_id}
			<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="data[name_{$lang_id}]" value="{$data[$name]|escape}" lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}" />
			{/foreach}
			<a href="#" lang-editor="button" lang-editor-type="data-name"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
		</div>
	</div>
	
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
	
</form>
{block name=lang_inline_editor module=start}
{include file="footer.tpl"}
