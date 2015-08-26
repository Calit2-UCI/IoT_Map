{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_service_change' gid='services'}{else}{l i='admin_header_service_add' gid='services'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle_long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='services'}: </div>
			<div class="v">
				<input type="text"  class="middle_long" value="{if $validate_lang.name}{$validate_lang.name[$cur_lang]}{else}{$data.name}{/if}" name="langs[name][{$cur_lang}]">
				{if $languages_count > 1}
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='services'}</a><br>
					<div id="name_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}
							{if $lang_id ne $cur_lang}
								<input type="text" class="middle_long" value="{if $validate_lang.name}{$validate_lang.name[$lang_id]}{else}{$data.name}{/if}" name="langs[name][{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
							{/if}
						{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='services'}: </div>
			<div class="v">
				<input type="text"  class="middle_long" value="{if $validate_lang.description}{$validate_lang.description[$cur_lang]}{else}{$data.description}{/if}" name="langs[description][{$cur_lang}]">
				{if $languages_count > 1}
					&nbsp;&nbsp;<a href="#" onclick="showLangs('description_langs'); return false;">{l i='others_languages' gid='services'}</a><br>
					<div id="description_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}
							{if $lang_id ne $cur_lang}
								<input type="text" value="{if $validate_lang.description}{$validate_lang.description[$lang_id]}{else}{$data.description}{/if}" name="langs[description][{$lang_id}]" />&nbsp;|&nbsp;{$item.name}<br>
							{/if}
						{/foreach}
					</div>
				{/if}
			</div>
		</div>
		{*<div class="row">
			<div class="h">
				{l i='field_user_type_disabled' gid='services'}: 
			</div>
			<div class="v">
				{foreach item=item key=key from=$user_types}
				<input type="checkbox" name="data[user_types][]" id="user_type_{$key}"
					   value="{$key}" {if $data.user_types_array && in_array($key, $data.user_types_array)}checked{/if}>
				<label for="user_type_{$key}">{$item}</label>
				{/foreach}
			</div>
		</div>*}
		<div class="row">
			<div class="h">{l i='field_pay_type' gid='services'}: </div>
			<div class="v"><select name="pay_type" class="middle_long">
				{foreach item=item key=key from=$pay_type_lang.option}<option value="{$key}"{if $key eq $data.pay_type} selected{/if}>{$item}</option>{/foreach}
			</select></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_status' gid='services'}: </div>
			<div class="v"><input type="checkbox" value="1" {if $data.status}checked{/if} name="status"></div>
		</div>

		<div class="row">
			<div class="h">{l i='field_template' gid='services'}: </div>
			<div class="v">
			{if $template}
				{$template.name}<input type="hidden" name="template_gid" value="{$data.template_gid}">
			{else}
				<select name="template_gid" onchange="javascript: load_param_block(this.value);">
					{foreach item=item from=$templates}<option value="{$item.gid}">{$item.name}</option>{/foreach}
				</select>
			{/if}
			</div>
		</div>

		<div id="admin_params">
		{$template_block}
		</div>
		<div id="lds_params">
		{$lds_block}
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/services">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
function showLangs(divId){
	$('#'+divId).slideToggle();
}

function load_param_block(id){
	$('#admin_params').load('{/literal}{$site_url}admin/services/ajax_get_template_admin_param_block/{literal}'+id);
	$("div.row:odd").addClass("zebra");
}

{/literal}</script>

{include file="footer.tpl"}
