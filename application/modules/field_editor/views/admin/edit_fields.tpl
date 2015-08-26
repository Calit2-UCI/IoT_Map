{include file="header.tpl"  load_type='ui'}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_field_change' gid='field_editor'}{else}{l i='admin_header_field_add' gid='field_editor'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='field_editor'}: </div>
			<div class="v">{$data.gid}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_section_data' gid='field_editor'}: </div>
			<div class="v">{$section_data.name}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_field_type' gid='field_editor'}: </div>
			<div class="v">
			{if $data.id}
				{foreach item=item key=key from=$field_type_lang.option}{if $key eq $data.field_type}{$item}{/if}{/foreach}
			{else}
				<select name="field_type">
					{foreach item=item key=key from=$field_type_lang.option}<option value="{$key}"{if $key eq $data.field_type} selected{/if}>{$item}</option>{/foreach}
				</select>
			{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='field_editor'}: </div>
			<div class="v">
				<input type="text" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]">
				{if $languages_count > 1}
				&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='field_editor'}</a><br>
				<div id="name_langs" class="hide p-top2">
					{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
					<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{$data.name}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
					{/if}{/foreach}
				</div>
				{/if}
			</div>
		</div>
		{if $type_settings.fulltext_use}
		<div class="row">
			<div class="h">{l i='field_fts' gid='field_editor'}: </div>
			<div class="v"><input type="checkbox" value="1" name="fts" {if $data.fts}checked{/if}></div>
		</div>
		{/if}
		<div id="type_block">
		{$type_block_content}
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/field_editor/fields/{$type}/{$section}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
function showLangs(divId){
	$('#'+divId).slideToggle();
}

{/literal}</script>

{include file="footer.tpl"}