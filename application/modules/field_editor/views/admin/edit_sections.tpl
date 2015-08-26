{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_section_change' gid='field_editor'}{else}{l i='admin_header_section_add' gid='field_editor'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='field_editor'}: </div>
			<div class="v">{if $data.id}<input type="hidden" value="{$data.gid}" name="gid" class="middle">{$data.gid}{else}<input type="text" value="{$data.gid}" name="gid" class="middle">{/if}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='field_editor'}: </div>
			<div class="v">
				<input type="text" class="middle" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]">
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
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/field_editor/sections">{l i='btn_cancel' gid='start'}</a>
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