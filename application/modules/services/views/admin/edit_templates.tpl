{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_template_change' gid='services'}{else}{l i='admin_header_template_add' gid='services'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='services'}: </div>
			<div class="v">
				<input type="text" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]">
				{if $languages_count > 1}
				&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='services'}</a><br>
				<div id="name_langs" class="hide p-top2">
					{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
					<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{$data.name}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
					{/if}{/foreach}
				</div>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_module' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.callback_module}" name="callback_module"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_model' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.callback_model}" name="callback_model"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_method_buy' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.callback_buy_method}" name="callback_buy_method"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_method_activate' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.callback_activate_method}" name="callback_activate_method"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_validate_method' gid='services'}: </div>
			<div class="v"><input type="text" value="{$data.callback_validate_method}" name="callback_validate_method"></div>
		</div>
	
		<div class="row">
			<div class="h">{l i='field_price_type' gid='services'}: </div>
			<div class="v"><select name="price_type">
				{foreach item=item key=key from=$price_type_lang.option}<option value="{$key}"{if $key eq $data.price_type} selected{/if}>{$item}</option>{/foreach}
			</select></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_moveable' gid='services'}: </div>
			<div class="v"><input type="checkbox" value="1" name="moveable" {if $data.moveable}checked{/if}></div>
		</div>

		<div class="row">
			<div class="h">{l i='field_data_admin' gid='services'}: </div>
			<div class="v"><textarea name="data_admin">{$data.data_admin}</textarea></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_data_user' gid='services'}: </div>
			<div class="v"><textarea name="data_user">{$data.data_user}</textarea></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_lds' gid='services'}: </div>
			<div class="v"><textarea name="lds">{$data.lds}</textarea></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/services/templates">{l i='btn_cancel' gid='start'}</a>
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