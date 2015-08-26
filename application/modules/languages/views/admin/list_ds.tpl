{include file="header.tpl" load_type='editable'}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_languages_menu'}
<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/languages/ds_edit/{$current_lang_id}/{$current_module_id}">{l i='link_add_ds' gid='languages'}</a></div></li>
		<li><div class="l"><a href="{$site_url}admin/languages/ajax_ds_delete/{$current_lang_id}/{$current_module_id}" onclick="javascript:  if(!confirm('{l i='note_delete_dses' gid='languages'}')) return false; delete_strings(this.href); return false;">{l i='link_delete_selected' gid='languages'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		{foreach item=item key=lang_id from=$langs}
		<li class="{if $lang_id eq $current_lang_id}active{/if}"><a href="{$site_url}admin/languages/ds/{$lang_id}/{$current_module_id}">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<div class="filter-form">
	<div class="form">
	<select name="module_id" onchange="javascript: reload_page(this.value);">
	{foreach item=item key=module_id from=$modules}
	<option value="{$module_id}"{if $module_id eq $current_module_id} selected{/if}>{$item.module_gid} ({$item.module_name})</option>
	{/foreach}
	</select>
	</div>
</div>
{if $ds}
<table cellspacing="0" cellpadding="0" class="data" width="100%" id="pages_table">
<tr>
	<th class="first w30">&nbsp;</th>
	<th class="w150">{l i='field_gid' gid='languages'}</th>
	<th>{l i='field_ds_name' gid='languages'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item key=key from=$ds}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if} id="{$key}_tr">
	<td class="first center"><input type="checkbox" value="{$key}" class="del"></td>
	<td>{$key}</td>
	<td class="editable" id="{$key}">{$item.header}</td>
	<td class="icons">
		<a href="{$site_url}admin/languages/ds_items/{$current_lang_id}/{$current_module_id}/{$key}"><img src="{$site_root}{$img_folder}icon-list.png" width="16" height="16" border="0" alt="{l i='link_edit_ds_items' gid='languages'}" title="{l i='link_edit_ds_items' gid='languages'}"></a>
		<a href="{$site_url}admin/languages/ds_edit/{$current_lang_id}/{$current_module_id}/{$key}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_ds' gid='languages'}" title="{l i='link_edit_ds' gid='languages'}"></a>
		<a href="{$site_url}admin/languages/ds_delete/{$current_lang_id}/{$current_module_id}/{$key}" onclick="javascript: if(!confirm('{l i='note_delete_ds' gid='languages' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_ds' gid='languages'}" title="{l i='link_delete_ds' gid='languages'}"></a>
	</td>
</tr>
{/foreach}
</table>
{include file="pagination.tpl"}
{/if}
<script>
var reload_url = "{$site_url}admin/languages/ds/{$current_lang_id}/";
var change_url = '{$site_url}admin/languages/ajax_ds_save/{$current_lang_id}/{$current_module_id}';
{literal}

function reload_page(module_id){
	location.href=reload_url+module_id;
}

function delete_strings(url){
	var gidArr = new Object;
	$(".del:checked").each(function(i){
		gidArr[i] = $(this).val();
	});
	$.ajax({
		url: url, 
		type: 'POST',
		data: ({gids: gidArr}), 
		cache: false,
		success: function(data){
			for(i in gidArr){ $('#'+gidArr[i]+'_tr').remove(); }
			$('#pages_table tr').removeClass('zebra');
			$("#pages_table tr:odd").addClass("zebra");
		}
	});
}

$(function(){
	$('.editable').editable(change_url, {
				tooltip: '{/literal}{l i="default_editable_text" gid="languages" type="js"}{literal}',
				placeholder: '<font class="hide_text">{/literal}{l i="default_editable_text" gid="languages" type="js"}{literal}</font>',
				name : 'text',
				submit : 'Save',
				height: 'auto',
				width: 300,
				callback: function(value, settings){
					$(this).html(settings.current);
				}
			});
});
{/literal}</script>
{include file="footer.tpl"}
